<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

use App\Exports\GenericExport;

class ExcelSyncController extends Controller
{
    public function export(Request $request)
    {
        $modelName = $request->get ("export-model");
        dd($modelName);

        $modelClass = $this->resolveModelClass("app/".$modelName);
        $this->checkGate($modelName, 'access');

        $data = $modelClass::all()->toArray();
        $header = array_keys($data[0] ?? []);

        return Excel::download(new GenericExport($data, $header), $modelName . 's.xlsx');
    }

    public function import(Request $request, $modelName)
    {
        $modelClass = $this->resolveModelClass($modelName);
        $this->checkGate($modelName, 'edit');

        $request->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

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
                    } else {
                        // Update
                        $record = $modelClass::find($id);
                        if ($record) {
                            $record->update($rowData->except('id')->toArray());
                        } else {
                            throw new \Exception("ID $id introuvable");
                        }
                    }
                } catch (Throwable $e) {
                    $simulatedErrors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if (count($simulatedErrors)) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'errors' => $simulatedErrors], 422);
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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
