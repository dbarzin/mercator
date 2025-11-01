<?php


namespace App\Http\Requests;

use App\Rules\Cidr;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreLogicalFlowRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('logical_flow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'min:0',
                'max:64',
            ],
            'source_ip_range' => [
                new Cidr(),
                'nullable',
                'required_without:src_id',
            ],
            'src_id' => [
                'nullable',
                'required_without:source_ip_range',
            ],
            'dest_ip_range' => [
                new Cidr(),
                'nullable',
                'required_without:dest_id',
            ],
            'dest_id' => [
                'nullable',
                'required_without:dest_ip_range',
            ],
        ];
    }
}
