@extends('layouts.admin')
@section('content')
@can('data_processing_register_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.data-processings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.dataProcessing.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.dataProcessing.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.processes') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.applications') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.information') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processingRegister as $processing)
                        <tr data-entry-id="{{ $processing->id }}"
                            @if (
                                ($processing->description===null)||
                                ($processing->responsible===null)||
                                ($processing->purpose===null)||
                                ($processing->categories===null)||
                                ($processing->recipients===null)||
                                ($processing->transfert===null)||
                                ($processing->retention===null)
                                )
                                class="table-warning"
                            @endif
                        >
                            <td>

                            </td>
                            <td nowrap>
                                <a href="{{ route('admin.data-processings.show', $processing->id) }}">
                                {{ $processing->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $processing->description !!}
                            </td>
                            <td>
                                @foreach($processing->processes as $p)
                                    <a href="{{ route('admin.processes.show', $p->id) }}">{{ $p->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($processing->applications as $app)
                                    <a href="{{ route('admin.applications.show', $app->id) }}">{{ $app->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($processing->informations as $info)
                                    <a href="{{ route('admin.information.show', $info->id) }}">{{ $info->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('data_processing_register_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.data-processings.show', $processing->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('data_processing_register_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.data-processings.edit', $processing->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('data_processing_register_delete')
                                    <form action="{{ route('admin.data-processings.destroy', $processing->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.dataProcessing.title_singular"),
    'URL' => route('admin.data-processings.massDestroy'),
    'canDelete' => auth()->user()->can('data_processing_register_delete') ? true : false
));
</script>
@endsection
