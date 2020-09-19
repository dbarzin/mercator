@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.dnsserver.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dnsservers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.dnsserver.fields.id') }}
                        </th>
                        <td>
                            {{ $dnsserver->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dnsserver.fields.name') }}
                        </th>
                        <td>
                            {{ $dnsserver->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dnsserver.fields.description') }}
                        </th>
                        <td>
                            {!! $dnsserver->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dnsservers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection