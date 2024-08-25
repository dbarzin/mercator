@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.phones.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    @can('phone_edit')
        <a class="btn btn-info" href="{{ route('admin.phones.edit', $phone->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('phone_delete')
        <form action="{{ route('admin.phones.destroy', $phone->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.phone.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
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
                            {{ trans('cruds.phone.fields.type') }}
                        </th>
                        <td>
                            {{ $phone->type }}
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
                            {{ trans('cruds.phone.fields.address_ip') }}
                        </th>
                        <td>
                            {{ $phone->address_ip }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.phone.fields.site') }}
                        </th>
                        <td>
                            @if ($phone->site!==null)
                                <a href="{{ route('admin.sites.show', $phone->site_id) }}">{{ $phone->site->name }}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.phone.fields.building') }}
                        </th>
                        <td>
                            @if ($phone->building!==null)
                                <a href="{{ route('admin.buildings.show', $phone->building_id) }}">{{ $phone->building->name }}</a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $phone->created_at ? $phone->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $phone->updated_at ? $phone->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.phones.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
