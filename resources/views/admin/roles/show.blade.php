@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.role.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.roles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @if(auth()->user()->can('role_edit'))
                    <a class="btn btn-info" href="{{ route('admin.roles.edit', $role->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endif
                @can('role_delete')
                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.role.fields.title') }}
                        </th>
                        <td>
                            {{ $role->title }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            @foreach($role->sortedPerms as $perm)
                                <div class="d-inline-block col-sm-12 col-lg-5">
                                    <span class="font-weight-bold">{{ ucwords($perm['name']) }} - </span>
                                    @foreach($perm['actions'] as $action)
                                        {{ ucfirst($action[1]) }}@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.roles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
