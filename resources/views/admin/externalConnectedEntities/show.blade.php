@extends('layouts.admin')
@section('content')
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
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.externalConnectedEntity.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.externalConnectedEntity.fields.name') }}
                    </th>
                    <td>
                        {{ $externalConnectedEntity->name }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.externalConnectedEntity.fields.type') }}
                    </th>
                    <td>
                        {{ $externalConnectedEntity->type }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.externalConnectedEntity.fields.entity') }}
                    </th>
                    <td>
                        @if ($externalConnectedEntity->entity!=null)
                            <a href="{{ route('admin.entities.show', $externalConnectedEntity->entity->id) }}">
                                {{ $externalConnectedEntity->entity->name }}
                            </a>
                        @endif
                    </td>
                    <th width="10%">
                        {{ trans('cruds.externalConnectedEntity.fields.contacts') }}
                    </th>
                    <td>
                        {{ $externalConnectedEntity->contacts }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.externalConnectedEntity.fields.description') }}
                    </th>
                    <td colspan='3'>
                        {!! $externalConnectedEntity->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.externalConnectedEntity.fields.network') }}
                    </th>
                    <td colspan='3'>
                        @if ($externalConnectedEntity->network!=null)
                            <a href="{{ route('admin.networks.show', $externalConnectedEntity->network->id) }}">
                                {{ $externalConnectedEntity->network->name }}
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.externalConnectedEntity.fields.src') }}
                    </th>
                    <td>
                        {{ $externalConnectedEntity->src }}
                        <br>
                        {{ $externalConnectedEntity->src_desc }}
                    </td>
                    <th>
                        {{ trans('cruds.externalConnectedEntity.fields.dest') }}
                    </th>
                    <td>
                        {{ $externalConnectedEntity->dest }}
                        <br>
                        {{ $externalConnectedEntity->dest_desc }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $externalConnectedEntity->created_at ? $externalConnectedEntity->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $externalConnectedEntity->updated_at ? $externalConnectedEntity->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.external-connected-entities.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
