@extends('layouts.admin')
@section('content')
<div class="form-group">
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.tasks.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        @can('task_edit')
            <a class="btn btn-info" href="{{ route('admin.tasks.edit', $task->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('task_delete')
            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan

    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.task.title') }}
    </div>

    <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.task.fields.name') }}
                        </th>
                        <td>
                            {{ $task->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.task.fields.description') }}
                        </th>
                        <td>
                            {!! $task->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $task->created_at ? $task->created_at->format(trans('global.timestamp')) : '' }} |
            {{ trans('global.updated_at') }} {{ $task->updated_at ? $task->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>

    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.tasks.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

@endsection
