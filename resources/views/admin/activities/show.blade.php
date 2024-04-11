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
        {{ trans('global.show') }} {{ trans('cruds.activity.title') }}
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
                            @foreach($activity->activitiesProcesses as $process)
                                <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->identifiant }}</a>
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
                </tbody>
            </table>
        </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $activity->created_at ? $activity->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $activity->updated_at ? $activity->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.activities.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection
