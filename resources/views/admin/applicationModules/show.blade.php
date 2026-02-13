@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.application-modules.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=MOD_{{$applicationModule->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('application_module_edit')
        <a class="btn btn-info" href="{{ route('admin.application-modules.edit', $applicationModule->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('application_module_delete')
        <form action="{{ route('admin.application-modules.destroy', $applicationModule->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationModule.title') }}
    </div>

    <div class="card-body">
    @include('admin.applicationModules._details', [
        'applicationModule' => $applicationModule,
        'withLink' => false,
    ])
    </div>

    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        Common Platform Enumeration (CPE)
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped table-report">
            <tbody>
            <tr>
                <th width="10%">
                    {{ trans('cruds.application.fields.vendor') }}
                </th>
                <td width="22%">
                    {{ $applicationModule->vendor }}
                </td>
                <th width="10%">
                    {{ trans('cruds.application.fields.product') }}
                </th>
                <td width="22%">
                    {{ $applicationModule->product }}
                </td>
                <th width="10%">
                    {{ trans('cruds.application.fields.version') }}
                </th>
                <td width="22%">
                    {{ $applicationModule->version }}
                </td>
                <td>
                    <form action="{{ route('admin.cve.search','cpe:2.3:a:'. $applicationModule->vendor.':'. $applicationModule->product . ':' . $applicationModule->version) }}"
                          method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="submit" class="btn btn-info"
                               value="{{ trans('global.search') }}" {{ (($applicationModule->vendor==null)||($applicationModule->product==null)) ? 'disabled' : '' }} />
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $applicationModule->created_at ? $applicationModule->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $applicationModule->updated_at ? $applicationModule->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.application-modules.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
