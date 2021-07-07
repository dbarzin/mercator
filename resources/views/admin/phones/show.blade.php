@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.phone.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.phones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.phone.fields.name') }}
                        </th>
                        <td>
                            {{ $phone->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.phone.fields.description') }}
                        </th>
                        <td>
                            {!! $phone->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.phone.fields.type') }}
                        </th>
                        <td>
                            {{ $phone->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.phone.fields.site') }}
                        </th>
                        <td>
                            {{ $phone->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.phone.fields.building') }}
                        </th>
                        <td>
                            {{ $phone->building->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.phones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection