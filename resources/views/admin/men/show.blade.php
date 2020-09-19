@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.man.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.men.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.man.fields.id') }}
                        </th>
                        <td>
                            {{ $man->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.man.fields.name') }}
                        </th>
                        <td>
                            {{ $man->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.man.fields.lans') }}
                        </th>
                        <td>
                            @foreach($man->lans as $key => $lans)
                                <span class="label label-info">{{ $lans->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.men.index') }}">
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
            <a class="nav-link" href="#mans_wans" role="tab" data-toggle="tab">
                {{ trans('cruds.wan.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="mans_wans">
            @includeIf('admin.men.relationships.mansWans', ['wans' => $man->mansWans])
        </div>
    </div>
</div>

@endsection