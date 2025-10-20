<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StorePhysicalLinkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_link_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            // Sources
            'peripheral_src_id' => [
                'nullable',
                'integer',
            ],
            'phone_src_id' => [
                'nullable',
                'integer',
            ],
            'physical_router_src_id' => [
                'nullable',
                'integer',
            ],
            'physical_security_device_src_id' => [
                'nullable',
                'integer',
            ],
            'physical_server_src_id' => [
                'nullable',
                'integer',
            ],
            'physical_switch_src_id' => [
                'nullable',
                'integer',
            ],
            'storage_device_src_id' => [
                'nullable',
                'integer',
            ],
            'wifi_terminal_src_id' => [
                'nullable',
                'integer',
            ],
            'workstation_src_id' => [
                'nullable',
                'integer',
            ],
            'logical_server_src_id' => [
                'nullable',
                'integer',
            ],
            'network_switch_src_id' => [
                'nullable',
                'integer',
            ],
            'router_src_id' => [
                'nullable',
                'integer',
            ],
            // Destinations
            'peripheral_dest_id' => [
                'nullable',
                'integer',
            ],
            'phone_dest_id' => [
                'nullable',
                'integer',
            ],
            'physical_router_dest_id' => [
                'nullable',
                'integer',
            ],
            'physical_security_device_dest_id' => [
                'nullable',
                'integer',
            ],
            'physical_server_dest_id' => [
                'nullable',
                'integer',
            ],
            'physical_switch_dest_id' => [
                'nullable',
                'integer',
            ],
            'storage_device_dest_id' => [
                'nullable',
                'integer',
            ],
            'wifi_terminal_dest_id' => [
                'nullable',
                'integer',
            ],
            'workstation_dest_id' => [
                'nullable',
                'integer',
            ],
            'logical_server_dest_id' => [
                'nullable',
                'integer',
            ],
            'network_switch_dest_id' => [
                'nullable',
                'integer',
            ],
            'router_dest_id' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
