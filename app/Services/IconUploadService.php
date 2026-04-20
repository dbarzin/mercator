<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Mercator\Core\Contracts\HasIcon;
use Mercator\Core\Models\Document;

class IconUploadService
{
    /**
     * @param  Model  $model
     */
    public function handle(FormRequest $request, Model $model): void
    {
        if ($request->hasFile('iconFile')) {
            $file = $request->file('iconFile');
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());
            $document->save();

            $file->move(storage_path('docs'), strval($document->id));
            $model->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->input('iconSelect', ''))) {
            $model->icon_id = ((int) $request->input('iconSelect'));
        } else {
            $model->icon_id = null;
        }
    }
}
