<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;

class ImportController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'object' => 'required',
        ]);

        // Model name from request
        $modelName = $request->get('object');

        // Check permission
        abort_if(Gate::denies($this->permission($modelName, 'access')), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get class
        $modelClass = $this->resolveModelClass($modelName);

        // Récupération brute des enregistrements
        $items = $modelClass::all();

        $data = [];
        foreach ($items as $item) {
            $row = $item->toArray();

            // Traite les belongsToMany : transforme en liste d'IDs
            foreach ((new ReflectionClass($item))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                // On évite les méthodes héritées de Model ou autres (ex: getKey, save, etc.)
                if ($method->class !== $item::class) {
                    continue;
                }
                if ($method->getNumberOfParameters() !== 0) {
                    continue;
                }
                $returnType = $method->getReturnType();
                if (! $returnType instanceof \ReflectionNamedType) {
                    continue;
                }
                try {
                    $result = $method->invoke($item);
                    if ($result instanceof BelongsToMany) {
                        $relationName = $method->getName();
                        $row[$relationName] = $item->$relationName()->pluck('id')->implode(', ');
                    }
                } catch (\Throwable $e) {
                    // Ignore toute méthode non relationnelle qui lancerait une erreur
                    continue;
                }
            }

            // Exclure les colonnes inutiles
            unset($row['created_at'], $row['updated_at'], $row['deleted_at']);

            $data[] = $row;
        }

        // Get header
        $header = array_keys($data[0] ?? []);

        return Excel::download(new GenericExport($data, $header), $modelName.'-'.Carbon::today()->format('Ymd').'.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
            'object' => 'required',
        ]);

        $modelName = $request->get('object');
        $modelClass = $this->resolveModelClass($modelName);

        // Get store validation rules
        $storeRequestClass = '\\App\\Http\\Requests\\Store'.$modelName.'Request';
        $storeRequestInstance = new $storeRequestClass;
        $storeRules = $storeRequestInstance->rules();

        // Get update validation rules
        $updateRequestClass = '\\App\\Http\\Requests\\Update'.$modelName.'Request';
        $updateRequestInstance = new $updateRequestClass;

        abort_if(Gate::denies($this->permission($modelName, 'edit')), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deleteCount = 0;
        $insertCount = 0;
        $updateCount = 0;
        $simulatedErrors = [];

        $rows = Excel::toCollection(null, $request->file('file'))->first();
        $header = $rows->shift();

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowData = $header->combine($row);
                $id = $rowData->get('id');

                try {
                    $attributes = $rowData->except('id');
                    $relations = [];

                    // Identify relations and remove them from attributes
                    foreach ($attributes as $key => $value) {
                        if (! method_exists($modelClass, $key)) {
                            continue;
                        }

                        try {
                            $relationInstance = (new $modelClass)->{$key}();
                            if ($relationInstance instanceof BelongsToMany) {
                                $relations[$key] = array_filter(array_map('trim', explode(',', $value)));
                                $attributes->forget($key);
                            }
                        } catch (\Throwable $e) {
                            // ignorer si ce n'est pas une relation
                        }
                    }
                    if ($id && $rowData->filter()->count() === 1) {
                        // Delete
                        $record = $modelClass::find($id);
                        if ($record) {
                            $record->delete();
                            $deleteCount++;
                        }
                    } elseif (! $id) {
                        // Create
                        $validator = Validator::make($attributes->toArray(), $storeRules);
                        if ($validator->fails()) {
                            throw new \Exception(implode(', ', $validator->errors()->all()));
                        }
                        $record = $modelClass::create($attributes->toArray());
                        foreach ($relations as $rel => $ids) {
                            if ($rowData->has($rel) && ! empty($ids)) {
                                $record->{$rel}()->sync($ids);
                            }
                        }
                        $insertCount++;
                    } else {
                        // Update
                        $record = $modelClass::find($id);
                        if ($record) {
                            $updateRequestInstance->id = $id;
                            $updateRules = $updateRequestInstance->rules();
                            $validator = Validator::make($attributes->toArray(), $updateRules);
                            if ($validator->fails()) {
                                throw new \Exception(implode(', ', $validator->errors()->all()));
                            }
                            $record->update($attributes->toArray());
                            foreach ($relations as $rel => $ids) {
                                if ($rowData->has($rel)) {
                                    $record->{$rel}()->sync($ids);
                                }
                            }
                            $updateCount++;
                        } else {
                            throw new \Exception("record {$id} not found");
                        }
                    }
                } catch (\Throwable $e) {
                    $simulatedErrors[] = 'Ligne '.($index + 2).': '.$e->getMessage();
                    if (count($simulatedErrors) >= 10) {
                        break;
                    }
                }
            }

            if (count($simulatedErrors)) {
                DB::rollBack();

                return back()->withInput()->withErrors($simulatedErrors);
            }

            DB::commit();

            return back()->withInput()
                ->withMessage("Success : {$insertCount} inserted, {$updateCount} updated, {$deleteCount} deleted");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['msg' => $e->getMessage()]);
        }
    }

    private function resolveModelClass($modelName)
    {
        $modelClass = 'App\\'.$modelName;
        if (! class_exists($modelClass)) {
            abort(404, "Modèle [{$modelName}] introuvable.");
        }

        return $modelClass;
    }

    private static function permission($modelName, $action)
    {
        return Str::snake($modelName, '_').'_'.$action;
    }
}

/**
 * Classe d'export Excel générique.
 */
class GenericExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    protected $headers;

    public function __construct(array $data, array $headers)
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->headers);
        $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount);
        $headerRange = 'A1:'.$endColumn.'1';

        return [
            $headerRange => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '94e5ff'],
                ],
            ],
        ];
    }
}
