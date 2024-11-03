@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.wans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    @can('wan_edit')
        <a class="btn btn-info" href="{{ route('admin.wans.edit', $wan->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('wan_delete')
        <form action="{{ route('admin.wans.destroy', $wan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.wan.title') }}
    </div>

    <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
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
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $wan->created_at ? $wan->created_at->format(trans('global.timestamp')) : '' }} |
            {{ trans('global.updated_at') }} {{ $wan->updated_at ? $wan->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.wans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
