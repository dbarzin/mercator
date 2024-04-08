@extends('layouts.admin')
@section('content')
<div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.operations.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=OPERATION_{{$operation->id}}">
            {{ trans('global.explore') }}
        </a>

        @can('entity_edit')
        <a class="btn btn-info" href="{{ route('admin.operations.edit', $operation->id) }}">
            {{ trans('global.edit') }}
        </a>
        @endcan

        @can('entity_delete')
        <form action="{{ route('admin.operations.destroy', $operation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
        @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.operation.title') }}
    </div>

    <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.operation.fields.name') }}
                        </th>
                        <td>
                            {{ $operation->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.operation.fields.description') }}
                        </th>
                        <td>
                            {!! $operation->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.operation.fields.process') }}
                        </th>
                        <td>
                            @if ($operation->process!=null)
                                <a href="{{ route('admin.processes.show',$operation->process->id) }}">
                                    {{ $operation->process->identifiant ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.operation.fields.activities') }}
                        </th>
                        <td>
                            @foreach($operation->activities as $activity)
                                <a href="{{ route('admin.activities.show', $activity->id) }}">
                                    {{ $activity->name }}
                                </a>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.operation.fields.actors') }}
                        </th>
                        <td>
                            @foreach($operation->actors as $actor)
                                <a href="{{ route('admin.actors.show', $actor->id) }}">
                                    {{ $actor->name }}
                                </a>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.operation.fields.tasks') }}
                        </th>
                        <td>
                            @foreach($operation->tasks as $task)
                                <a href="{{ route('admin.tasks.show', $task->id) }}">
                                    {{ $task->name }}
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
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $operation->created_at ? $operation->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $operation->updated_at ? $operation->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.operations.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
</div>
@endsection
