@extends('layouts.admin')
@section('content')
@can('lan_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.lans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.lan.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.lan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.lan.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.lan.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lans as $key => $lan)
                        <tr data-entry-id="{{ $lan->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.lans.show', $lan->id) }}">
                                {{ $lan->name }}
                                </a>
                            </td>
                            <td>
                                {!! $lan->description !!}
                            </td>
                            <td nowrap>
                                @can('lan_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.lans.show', $lan->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('lan_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.lans.edit', $lan->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('lan_delete')
                                    <form action="{{ route('admin.lans.destroy', $lan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.lan.title_singular"),
    'URL' => route('admin.lans.massDestroy'),
    'canDelete' => auth()->user()->can('lan_delete') ? true : false
));
</script>
@endsection
