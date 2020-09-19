@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.annuaire.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.annuaires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.id') }}
                        </th>
                        <td>
                            {{ $annuaire->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.name') }}
                        </th>
                        <td>
                            {{ $annuaire->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.description') }}
                        </th>
                        <td>
                            {!! $annuaire->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.solution') }}
                        </th>
                        <td>
                            {{ $annuaire->solution }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.zone_admin') }}
                        </th>
                        <td>
                            {{ $annuaire->zone_admin->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.annuaires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection