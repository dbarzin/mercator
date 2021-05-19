@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.lan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.lan.fields.name') }}
                        </th>
                        <td>
                            {{ $lan->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lan.fields.description') }}
                        </th>
                        <td>
                            {{ $lan->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#lans_men" role="tab" data-toggle="tab">
                {{ trans('cruds.man.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#lans_wans" role="tab" data-toggle="tab">
                {{ trans('cruds.wan.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="lans_men">
            @includeIf('admin.lans.relationships.lansMen', ['mans' => $lan->lansMen])
        </div>
        <div class="tab-pane" role="tabpanel" id="lans_wans">
            @includeIf('admin.lans.relationships.lansWans', ['wans' => $lan->lansWans])
        </div>
    </div>
</div>

@endsection