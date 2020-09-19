@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.externalConnectedEntity.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.external-connected-entities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.id') }}
                        </th>
                        <td>
                            {{ $externalConnectedEntity->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.name') }}
                        </th>
                        <td>
                            {{ $externalConnectedEntity->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.responsible_sec') }}
                        </th>
                        <td>
                            {{ $externalConnectedEntity->responsible_sec }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.contacts') }}
                        </th>
                        <td>
                            {{ $externalConnectedEntity->contacts }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.connected_networks') }}
                        </th>
                        <td>
                            @foreach($externalConnectedEntity->connected_networks as $key => $connected_networks)
                                <span class="label label-info">{{ $connected_networks->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.external-connected-entities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection