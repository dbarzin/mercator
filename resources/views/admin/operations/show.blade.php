@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.operation.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.operations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.operation.fields.id') }}
                        </th>
                        <td>
                            {{ $operation->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
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
                            {{ trans('cruds.operation.fields.actors') }}
                        </th>
                        <td>
                            @foreach($operation->actors as $key => $actors)
                                <span class="label label-info">{{ $actors->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.operation.fields.tasks') }}
                        </th>
                        <td>
                            @foreach($operation->tasks as $key => $tasks)
                                <span class="label label-info">{{ $tasks->nom }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.operations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#operations_activities" role="tab" data-toggle="tab">
                {{ trans('cruds.activity.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="operations_activities">
            @includeIf('admin.operations.relationships.operationsActivities', ['activities' => $operation->operationsActivities])
        </div>
    </div>
</div>

@endsection