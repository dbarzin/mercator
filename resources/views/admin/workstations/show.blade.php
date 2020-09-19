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
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.workstation.fields.id') }}
                        </th>
                        <td>
                            {{ $workstation->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workstation.fields.name') }}
                        </th>
                        <td>
                            {{ $workstation->name }}
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
</div>



@endsection