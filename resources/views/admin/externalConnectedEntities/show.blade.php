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
                
                @can('entity_edit')
                    <a class="btn btn-info" href="{{ route('admin.external-connected-entities.edit', $externalConnectedEntity->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('entity_delete')
                    <form action="{{ route('admin.external-connected-entities.destroy', $externalConnectedEntity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan

            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
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
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $externalConnectedEntity->created_at->format(trans('global.timestamp')) ?? '' }} |
        {{ trans('global.updated_at') }} {{ $externalConnectedEntity->updated_at->format(trans('global.timestamp')) ?? '' }} 
    </div>
</div>
@endsection