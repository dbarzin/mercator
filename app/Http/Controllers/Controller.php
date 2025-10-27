<?php


namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function handleIconUpload(FormRequest $request, Model $model)
    {
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());
            $document->save();
            $file->move(storage_path('docs'), $document->id);
            $model->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $model->icon_id = intval($request->iconSelect);
        } else {
            $model->icon_id = null;
        }
    }
}
