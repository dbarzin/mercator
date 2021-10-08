@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.gateway.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.gateways.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                @can('entity_edit')
                    <a class="btn btn-info" href="{{ route('admin.gateways.edit', $gateway->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('entity_delete')
                    <form action="{{ route('admin.gateways.destroy', $gateway->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.gateway.fields.name') }}
                        </th>
                        <td>
                            {{ $gateway->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.description') }}
                        </th>
                        <td>
                            {!! $gateway->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.authentification') }}
                        </th>
                        <td>
                            {{ $gateway->authentification }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.ip') }}
                        </th>
                        <td>
                            {{ $gateway->ip }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.gateways.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
