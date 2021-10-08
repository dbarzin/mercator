@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.entity.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entities.index') }}">
                    {{ trans('global.back_to_list') }}

                @can('entity_edit')
                    <a class="btn btn-info" href="{{ route('admin.entities.edit', $entity->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('entity_delete')
                    <form action="{{ route('admin.entities.destroy', $entity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan

            </div>
            <table class="table table-bordered table-striped ">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.entity.fields.name') }}
                        </th>
                        <td>
                            {{ $entity->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.description') }}
                        </th>
                        <td>
                            {!! $entity->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.security_level') }}
                        </th>
                        <td>
                            {!! $entity->security_level !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.contact_point') }}
                        </th>
                        <td>
                            {!! $entity->contact_point !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $entity->created_at->format("d M Y") }} |
        {{ trans('global.updated_at') }} {{ $entity->updated_at->format("d M Y") }} 
    </div>

</div>

@endsection