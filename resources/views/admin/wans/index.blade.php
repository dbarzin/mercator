@extends('layouts.admin')
@section('content')
@can('wan_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route("admin.wans.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.wan.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.wan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.wan.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.wan.fields.mans') }}
                        </th>
                        <th>
                            {{ trans('cruds.wan.fields.lans') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wans as $key => $wan)
                        <tr data-entry-id="{{ $wan->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.wans.show', $wan->id) }}">
                                {{ $wan->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                @foreach($wan->mans as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach($wan->lans as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('wan_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.wans.show', $wan->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('wan_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.wans.edit', $wan->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('wan_delete')
                                    <form action="{{ route('admin.wans.destroy', $wan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.wan.title_singular"),
    'URL' => route('admin.wans.massDestroy'),
    'canDelete' => auth()->user()->can('wan_delete') ? true : false
));
</script>
@endsection
