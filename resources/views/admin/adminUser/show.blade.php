@extends('layouts.admin')
@section('content')
<div class="form-group">
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.admin-users.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=ADMIN_{{$adminUser->id}}">
            {{ trans('global.explore') }}
        </a>

        @can('admin_user_edit')
            <a class="btn btn-info" href="{{ route('admin.admin-users.edit', $adminUser->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('admin_user_delete')
            <form action="{{ route('admin.admin-users.destroy', $adminUser->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan

    </div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.adminUser.title') }}
    </div>

    <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.adminUser.fields.user_id') }}
                        </th>
                        <td colspan="3">
                            {{ $adminUser->user_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.adminUser.fields.firstname') }}
                        </th>
                        <td width="30%">
                            {{ $adminUser->firstname }}
                        </td>
                        <th width="10%">
                            {{ trans('cruds.adminUser.fields.lastname') }}
                        </th>
                        <td>
                            {{ $adminUser->lastname }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <dt>{{ trans('cruds.adminUser.fields.description') }}</dt>
                        </th>
                        <td colspan="3">
                            {!! $adminUser->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.adminUser.fields.domain') }}
                        </th>
                        <td>
                            @if ($adminUser->domain_id !== null)
                                <a href="{{ route('admin.domaine-ads.show', $adminUser->domain_id) }}">
                                    {{ $adminUser->domain->name }}
                                </a>
                            @endif
                        </td>
                        <th>
                            {{ trans('cruds.adminUser.fields.type') }}
                        </th>
                        <td>
                            {{ $adminUser->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.adminUser.fields.local') }}
                        </th>
                        <td>
                            {{ $adminUser->local }}
                        </td>
                        <th>
                            {{ trans('cruds.adminUser.fields.privileged') }}
                        </th>
                        <td>
                            {{ $adminUser->privileged }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $adminUser->created_at ? $adminUser->created_at->format(trans('global.timestamp')) : '' }} |
            {{ trans('global.updated_at') }} {{ $adminUser->updated_at ? $adminUser->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.admin-users.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>
@endsection
