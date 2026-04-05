<?php

namespace App\Http\Requests;

use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreBackupRequest extends BaseFormRequest
{
    protected array $htmlFields = [];

    public function authorize() : bool
    {
        abort_if(Gate::denies('backup_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
        ];
    }
}
