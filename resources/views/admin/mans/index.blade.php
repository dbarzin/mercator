@extends('layouts.admin')
@section('content')
@can('man_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.mans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.man.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.man.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.man.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.man.fields.lans') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mans as $key => $man)
                        <tr data-entry-id="{{ $man->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.mans.show', $man->id) }}">
                                {{ $man->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                @foreach($man->lans as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('man_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.mans.show', $man->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('man_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.mans.edit', $man->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('man_delete')
                                    <form action="{{ route('admin.mans.destroy', $man->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.man.title_singular"),
    'URL' => route('admin.mans.massDestroy'),
    'canDelete' => auth()->user()->can('man_delete') ? true : false
));
</script>
@endsection
