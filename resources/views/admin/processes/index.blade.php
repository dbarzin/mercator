@extends('layouts.admin')
@section('content')
@can('process_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.processes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.process.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.process.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.process.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.process.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.process.fields.operations') }}
                        </th>
                        <th>
                            {{ trans('cruds.process.fields.activities') }}
                        </th>
                        <th>
                            {{ trans('cruds.process.fields.informations') }}
                        </th>
                        <th>
                            {{ trans('cruds.process.fields.macroprocessus') }}
                        </th>
                        <th>
                            {{ trans('cruds.process.fields.owner') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processes as $key => $process)
                        <tr data-entry-id="{{ $process->id }}"
                            @if(($process->name==null)||
                                ($process->description==null)||
                                ($process->in_out==null)||
                                ((auth()->user()->granularity>=2)&&
                                    (($process->security_need_c==null)||
                                    ($process->security_need_i==null)||
                                    ($process->security_need_a==null)||
                                    ($process->security_need_t==null)))||
                                ($process->owner==null)||
                                ($process->macroprocess_id==null)
                                )
                                                      class="table-warning"
                            @endif

                            >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.processes.show', $process->id) }}">
                                {{ $process->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $process->description ?? '' !!}
                            </td>
                            <td>
                                {!!
                                    $process->operations->map(function ($operation) {
                                        return '<a href="' . route('admin.operations.show', $operation->id) . '">' . $operation->name . '</a>';
                                    })->implode(', ');
                                !!}
                            </td>
                            <td>
                                {!!
                                    $process->activities->map(function ($activity) {
                                        return '<a href="' . route('admin.activities.show', $activity->id) . '">' . $activity->name . '</a>';
                                    })->implode(', ');
                                !!}
                            </td>
                            <td>
                                {!!
                                    $process->processInformation->map(function ($information) {
                                        return '<a href="' . route('admin.information.show', $information->id) . '">' . $information->name . '</a>';
                                    })->implode(', ');
                                !!}
                            </td>
                            <td>
                                @if ($process->macroprocess_id!=null)
                                <a href="{{ route('admin.macro-processuses.show', $process->macroprocess_id) }}">
                                {{ $process->macroProcess->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td>
                                {{ $process->owner ?? '' }}
                            </td>
                            <td nowrap>
                                @can('process_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.processes.show', $process->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('process_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.processes.edit', $process->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('process_delete')
                                    <form action="{{ route('admin.processes.destroy', $process->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.process.title_singular"),
    'URL' => route('admin.processes.massDestroy'),
    'canDelete' => auth()->user()->can('process_delete') ? true : false
));
</script>
@endsection
