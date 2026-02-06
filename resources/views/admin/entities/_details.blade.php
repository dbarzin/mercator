<table class="table table-bordered table-striped table-report" id="{{ $entity->getUID() }}">
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
                {{ $entity->is_external ? trans('global.yes') : trans('global.no') }}
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
            <td colspan="6">
                {!! $entity->description !!}
            </td>
            <td width="10%">
                @if ($entity->icon_id === null)
                <img src='/images/application.png' width='120' height='120'>
                @else
                <img src='{{ route('admin.documents.show', $entity->icon_id) }}' width='120' height='120'>
                @endif
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
                @foreach ($entity->processes as $process)
                    <a href="/admin/processes/{{ $process->id }}">{{ $process->name }}</a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>{{ trans('cruds.entity.fields.exploits') }}</th>
            <td colspan="7">
                @foreach($entity->respApplications as $application)
                    <a href="/admin/applications/{{$application->id}}">{{$application->name}}</a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
                @if (
                    ($entity->respApplications->count()>0)&&
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
