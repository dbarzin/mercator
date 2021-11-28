@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.dhcpServer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dhcp-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @can('entity_edit')
                    <a class="btn btn-info" href="{{ route('admin.dhcp-servers.edit', $dhcpServer->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('entity_delete')
                    <form action="{{ route('admin.dhcp-servers.destroy', $dhcpServer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.dhcpServer.fields.name') }}
                        </th>
                        <td>
                            {{ $dhcpServer->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dhcpServer.fields.description') }}
                        </th>
                        <td>
                            {!! $dhcpServer->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.dhcpServer.fields.address_ip') }}
                        </th>
                        <td>
                            {{ $dhcpServer->address_ip }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dhcp-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection