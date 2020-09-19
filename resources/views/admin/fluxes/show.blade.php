@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.flux.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.fluxes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.id') }}
                        </th>
                        <td>
                            {{ $flux->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.name') }}
                        </th>
                        <td>
                            {{ $flux->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.description') }}
                        </th>
                        <td>
                            {{ $flux->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.application_source') }}
                        </th>
                        <td>
                            {{ $flux->application_source->name ?? '' }}
                        </td>
                    </tr>
                    @if (auth()->user()->granularity>=2)
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.service_source') }}
                        </th>
                        <td>
                            {{ $flux->service_source->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.module_source') }}
                        </th>
                        <td>
                            {{ $flux->module_source->name ?? '' }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.database_source') }}
                        </th>
                        <td>
                            {{ $flux->database_source->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.application_dest') }}
                        </th>
                        <td>
                            {{ $flux->application_dest->name ?? '' }}
                        </td>
                    </tr>
                    @if (auth()->user()->granularity>=2)
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.service_dest') }}
                        </th>
                        <td>
                            {{ $flux->service_dest->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.module_dest') }}
                        </th>
                        <td>
                            {{ $flux->module_dest->name ?? '' }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.database_dest') }}
                        </th>
                        <td>
                            {{ $flux->database_dest->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.crypted') }}
                        </th>
                        <td>
                            @if ($flux->crypted==0)
                                Non
                            @elseif ($flux->crypted==1)
                                Oui
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.fluxes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection