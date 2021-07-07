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
                        <th width="10%">
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
                            {{ trans('cruds.flux.fields.source') }}
                        </th>
                        <td>
                            {{ $flux->application_source->name ?? '' }}
                            @if (auth()->user()->granularity>=2)
                                {{ $flux->service_source->name ?? '' }}
                                {{ $flux->module_source->name ?? '' }}
                            @endif
                            {{ $flux->database_source->name ?? '' }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.flux.fields.destination') }}
                        </th>
                        <td>
                            {{ $flux->application_dest->name ?? '' }}
                            @if (auth()->user()->granularity>=2)
                                {{ $flux->service_dest->name ?? '' }}
                                {{ $flux->module_dest->name ?? '' }}
                            @endif
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