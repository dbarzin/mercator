@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.workstation.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.workstations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @can('workstation_edit')
                    <a class="btn btn-info" href="{{ route('admin.workstations.edit', $workstation->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('workstation_delete')
                    <form action="{{ route('admin.workstations.destroy', $workstation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.workstation.fields.name') }}
                        </th>
                        <td>
                            {{ $workstation->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workstation.fields.type') }}
                        </th>
                        <td>
                            {{ $workstation->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workstation.fields.description') }}
                        </th>
                        <td>
                            {!! $workstation->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workstation.fields.site') }}
                        </th>
                        <td>
                            {{ $workstation->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workstation.fields.building') }}
                        </th>
                        <td>
                            {{ $workstation->building->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.workstations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $workstation->created_at->format(trans('global.timestamp')) }} |
        {{ trans('global.updated_at') }} {{ $workstation->updated_at->format(trans('global.timestamp')) }} 
    </div>
</div>
@endsection