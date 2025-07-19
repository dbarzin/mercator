@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.activities.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=ACTIVITY_{{$activity->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('activity_edit')
        <a class="btn btn-info" href="{{ route('admin.activities.edit', $activity->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('activity_delete')
        <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.activity.title_singular') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.activity.fields.name') }}
                    </th>
                    <td>
                        {{ $activity->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.activity.fields.description') }}
                    </th>
                    <td>
                        {!! $activity->description !!}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.activity.fields.processes') }}
                    </th>
                    <td>
                        @foreach($activity->processes as $process)
                            <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->name }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.activity.fields.operations') }}
                    </th>
                    <td>
                        @foreach($activity->operations as $operation)
                            <a href="{{ route('admin.operations.show', $operation->id) }}">{{ $operation->name }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.activity.fields.applications') }}
                    </th>
                    <td colspan="5">
                        @foreach($activity->applications as $application)
                            <a href="{{ route('admin.applications.show', $application->id) }}">
                            {{ $application->name }}
                            </a>
                            @if (!$loop->last)
                            ,
                            @endif
                        @endforeach
                    </td>
                </tr>



            </tbody>
        </table>
    </div>
    <div class="card-header">
        {{ trans('cruds.activity.bia') }}
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.activity.fields.recovery_time_objective') }}
                    </th>
                    <td width="20%">
                        {{ substr($activity->recovery_time_objective,0,5) }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.activity.fields.recovery_point_objective') }}
                    </th>
                    <td>
                        {{ substr($activity->recovery_point_objective,0,5) }}
                    </td>
                </tr>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.activity.fields.maximum_tolerable_downtime') }}
                    </th>
                    <td width="20%">
                        {{ substr($activity->maximum_tolerable_downtime,0,5) }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.activity.fields.maximum_tolerable_data_loss') }}
                    </th>
                    <td>
                        {{ substr($activity->maximum_tolerable_data_loss,0,5) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <b>{{  trans('cruds.activity.impacts') }}</b>
        <table class="table table-bordered table-striped">
        @foreach($activity->impacts as $impact)
        <tr>
            <td width="10%">
                {{ $impact->impact_type }}
            </td>
            <td width="10%">
                @if ($impact->severity==0){{ trans('global.none') }}@endif
                @if ($impact->severity==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                @if ($impact->severity==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                @if ($impact->severity==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                @if ($impact->severity==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
            </td>
            <td>
            </td>
        </tr>
        @endforeach
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $activity->created_at ? $activity->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $activity->updated_at ? $activity->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.activities.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection
