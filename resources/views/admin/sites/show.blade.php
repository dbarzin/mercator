@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.site.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sites.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @can('site_edit')
                    <a class="btn btn-info" href="{{ route('admin.sites.edit', $site->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('site_delete')
                    <form action="{{ route('admin.sites.destroy', $site->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.site.fields.name') }}
                        </th>
                        <td>
                            {{ $site->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.site.fields.description') }}
                        </th>
                        <td>
                            {!! $site->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sites.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection