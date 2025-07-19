@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    @can('activity_create')
    <div class="col-lg-6">
        <a id="btn-new" class="btn btn-success" href="{{ route('admin.activities.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.activity.title_singular') }}
        </a>
    </div>
    @endcan
    @can('activity_show')
    <div class="col-lg-6 text-end">
        <a id="btn-export" class="btn btn-primary" href="{{ route('admin.report.view.rto') }}">
            <i class="bi bi-download"></i>
            {{ trans('cruds.activity.bia') }}
        </a>

        <a id="btn-export" class="btn btn-primary" href="{{ route('admin.report.view.impacts') }}">
            <i class="bi bi-download"></i>
            {{ trans('cruds.activity.impacts') }}
        </a>
    </div>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.activity.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.activity.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.activity.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.activity.fields.operations') }}
                        </th>
                        <th>
                            {{ trans('cruds.activity.fields.processes') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $key => $activity)
                        <tr data-entry-id="{{ $activity->id }}"
                            @if (
                                ($activity->description===null)
                                )
                                class="table-warning"
                            @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.activities.show', $activity->id) }}">
                                {{ $activity->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $activity->description !!}
                            </td>
                            <td>
                                @foreach($activity->operations as $operation)
                                    <a href="{{ route('admin.operations.show', $operation->id) }}">{{ $operation->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($activity->processes as $process)
                                    <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('activity_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.activities.show', $activity->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('activity_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.activities.edit', $activity->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('activity_delete')
                                    <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.activity.title_singular"),
    'URL' => route('admin.activities.massDestroy'),
    'canDelete' => auth()->user()->can('activity_delete') ? true : false
));
</script>
@endsection
