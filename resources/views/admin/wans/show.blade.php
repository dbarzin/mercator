@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.wan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.wans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.wan.fields.id') }}
                        </th>
                        <td>
                            {{ $wan->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wan.fields.name') }}
                        </th>
                        <td>
                            {{ $wan->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wan.fields.mans') }}
                        </th>
                        <td>
                            @foreach($wan->mans as $key => $mans)
                                <span class="label label-info">{{ $mans->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wan.fields.lans') }}
                        </th>
                        <td>
                            @foreach($wan->lans as $key => $lans)
                                <span class="label label-info">{{ $lans->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.wans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection