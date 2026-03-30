<?php
// ============================================================
// MassUpdateDocumentRequest.php
// App\Http\Requests\MassUpdateDocumentRequest
// ============================================================

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MassUpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                'items'            => ['required', 'array', 'min:1'],
                'items.*.id'       => ['required', 'integer', 'exists:documents,id'],
                'items.*.filename' => ['nullable', 'string', 'max:255'],
        ];
    }
}
 