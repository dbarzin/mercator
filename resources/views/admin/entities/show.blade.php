@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.entities.index') }}">
	    {{ trans('global.back_to_list') }}
   	</a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=ENTITY_{{$entity->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('entity_edit')
        <a class="btn btn-info" href="{{ route('admin.entities.edit', $entity->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('entity_delete')
        <form action="{{ route('admin.entities.destroy', $entity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.entity.title_singular') }}
    </div>

    <div class="card-body">
            <table class="table table-bordered table-striped ">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.entity.fields.name') }}
                        </th>
                        <td>
                            {{ $entity->name }}
                        </td>
                        <th width="10%">
                            {{ trans('cruds.entity.fields.entity_type') }}
                        </th>
                        <td>
                            {!! $entity->entity_type !!}
                        </td>
                        <th width="10%">
                            {{ trans('cruds.entity.fields.parent_entity') }}
                        </th>
                        <td>
                            @if ($entity->parentEntity!=null)
                                <a href="{{ route('admin.entities.show', $entity->parentEntity->id) }}">{{ $entity->parentEntity->name }}</a>
                            @endif
                        </td>
                        <th width="10%">
                            {{ trans('cruds.entity.fields.is_external') }}
                        </th>
                        <td>
                            {!! $entity->is_external ? trans('global.yes') : trans('global.yes') !!}
                        </td>
                    </tr>
                    @if ($entity->entities()->count()>0)
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.subsidiaries') }}
                        </th>
                        <td colspan="7">
                            @foreach($entity->entities as $e)
                                <a href="{{ route('admin.entities.show', $e->id) }}">{{ $e->name }}</a>
                                @if(!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.description') }}
                        </th>
                        <td colspan="7">
                            {!! $entity->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.security_level') }}
                        </th>
                        <td colspan="7">
                            {!! $entity->security_level !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.contact_point') }}
                        </th>
                        <td colspan="7">
                            {!! $entity->contact_point !!}
                        </td>
                    </tr>


                    <tr>
                        <th>{{ trans('cruds.entity.fields.relations') }}</th>
                        <td colspan="7">
                            @foreach ($entity->sourceRelations as $relation)
                                <a href="/admin/relations/{{ $relation->id }}">{{ $relation->name }}</a>
                                ->
                                <a href="/admin/entities/{{ $relation->destination_id }}">{{ $relation->destination->name ?? '' }}</a>
                                @if (!$loop->last)
                                <br>
                                @endif
                            @endforeach
                            @if (($entity->sourceRelations->count()>0)&&($entity->destinationRelations->count()>0))
                            <br>
                            @endif
                            @foreach ($entity->destinationRelations as $relation)
                                <a href="/admin/entities/{{ $relation->source_id }}">{{ $relation->source->name ?? '' }}</a>
                                <-
                                <a href="/admin/relations/{{ $relation->id }}">{{ $relation->name }}</a>
                                @if (!$loop->last)
                                <br>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.entity.fields.processes') }}</th>
                        <td colspan="7">
                            @foreach ($entity->entitiesProcesses as $process)
                                <a href="/admin/processes/{{ $process->id }}">{{ $process->identifiant }}</a>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.entity.fields.exploits') }}</th>
                        <td colspan="7">
                            @foreach($entity->applications as $application)
                                <a href="/admin/applications/{{$application->id}}">{{$application->name}}</a>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                            @if (
                                ($entity->applications->count()>0)&&
                                ($entity->databases->count()>0)
                                )
                                ,<br>
                            @endif
                            @foreach($entity->databases as $database)
                                <a href="/admin/databases/{{$database->id}}">{{$database->name}}</a>
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
        {{ trans('global.created_at') }} {{ $entity->created_at ? $entity->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $entity->updated_at ? $entity->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.entities.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
