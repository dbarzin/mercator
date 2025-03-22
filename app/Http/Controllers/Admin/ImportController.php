<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Throwable;

class ImportController extends Controller
{
    public function export(Request $request)
    {
        $modelName = $request->get("model");
        if($modelName==null)
            return back()->withInput()->withErrors(['msg' => 'Model empty']);

        $apiClassName = 'App\\Http\\Controllers\\API\\' . ucfirst($modelName) ."Controller";
        if (!class_exists($apiClassName))
            return back()->withInput()->withErrors(['msg' => 'API Class not found']);

        $controller = app()->make($apiClassName);

        // Appeler le contrôleur
        $response = app()->call([$controller, 'index']);

        // Récupérer les données JSON en tableau associatif
        $rawData = $response->getData(true);

        // Exclure les colonnes techniques
        $columnsToExclude = ['created_at', 'updated_at', 'deleted_at'];

        $data = array_map(function ($row) use ($columnsToExclude) {
            return collect($row)->except($columnsToExclude)->all();
        }, $rawData);

        $header = array_keys($data[0] ?? []);

        return Excel::download(new GenericExport($data, $header), $modelName . '-'. Carbon::today()->format('Ymd') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        $modelName = $request->get("model");
        if($modelName==null)
            return back()->withInput()->withErrors(['msg' => 'Model empty']);

        $modelClass = 'App\\' . ucfirst($modelName);
        if (!class_exists($modelClass))
            return back()->withInput()->withErrors(['msg' => 'Class not found']);

        $controller = app()->make($modelClass);

        // Inititialize counters
        $deleteCount = 0;
        $insertCount = 0;
        $updateCount = 0;
        //
        $errors = [];
        $rows = Excel::toCollection(null, $request->file('file'))->first();
        $header = $rows->shift(); // Première ligne = entête

        DB::beginTransaction();
        try {
            $simulatedErrors = [];

            foreach ($rows as $index => $row) {
                $rowData = $header->combine($row);
                $id = $rowData->get('id');

                try {
                    if ($id && $rowData->filter()->count() === 1) {
                        // Suppression
                        $record = $modelClass::find($id);
                        if ($record) {
                            $record->delete();
                            $deleteCount++;
                        } else {
                            throw new \Exception("ID $id introuvable");
                        }
                    } elseif (!$id) {
                        // Insertion
                        $validator = Validator::make($rowData->except('id')->toArray(), $modelClass::$excelValidation ?? []);
                        if ($validator->fails()) {
                            throw new \Exception(implode(', ', $validator->errors()->all()));
                        }
                        $modelClass::create($rowData->except('id')->toArray());
                        $insertCount++;
                    } else {
                        // Update
                        $record = $modelClass::find($id);
                        if ($record) {
                            $record->update($rowData->except('id')->toArray());
                            $updateCount++;
                        } else {
                            throw new \Exception("ID $id introuvable");
                        }
                    }
                } catch (Throwable $e) {
                    $simulatedErrors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                    if (sizeof($simulatedErrors)>=10)
                        break;
                }
            }

            if (count($simulatedErrors)) {
                DB::rollBack();
                return back()->withInput()->withErrors($simulatedErrors);
            }

            DB::commit();
            return back()->withInput()
                ->withMessage("Sucess : {$insertCount} lines inserted, {$updateCount} lines updated and {$deleteCount} lines deleted");
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($simulatedErrors);
        }
    }

    private function resolveModelClass($modelName)
    {
        $modelClass = 'App\\' . Str::studly(Str::singular($modelName));
        if (!class_exists($modelClass)) {
            abort(404, "Modèle [$modelName] introuvable.");
        }
        return $modelClass;
    }

    private function checkGate($modelName, $action)
    {
        $permission = strtolower($modelName . '_' . $action);
        abort_if(Gate::denies($permission), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        $headerRange = 'A1:' . $endColumn . '1';

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
