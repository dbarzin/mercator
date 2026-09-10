@props([
    'entity',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $entity->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.entity.fields.name') }}
            </th>
            <td width="15%">
            @if ($withLink)
            @canShow($entity)
            <a href="{{ route('admin.entities.show', $entity->id) }}">{{ $entity->name }}</a>
            @elsecanShow
            {{ $entity->name }}
            @endcanShow
            @else
                {{ $entity->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.entity.fields.type') }}
            </th>
            <td width="15%">
                {{ $entity->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.entity.fields.attributes') }}
            </th>
            <td colspan="3" width="20%">
                @foreach(explode(" ", (string) $entity->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.entity.fields.description') }}
            </th>
            <td colspan="6">
                {!! $entity->description !!}
            </td>
            <td width="10%">
                @if ($entity->icon_id === null)
                <img src='/images/application.png' width='60' height='60'>
                @else
                <img src='{{ route('admin.documents.show', $entity->icon_id) }}' width='60' height='60'>
                @endif
            </td>
        </tr>

        <tr>
            <th width="10%">
                {{ trans('cruds.entity.fields.parent_entity') }}
            </th>
            <td colspan="1">
                @if ($entity->parentEntity!=null)
                    @canShow($entity->parentEntity)
                        <a href="{{ route('admin.entities.show', $entity->parentEntity->id) }}">{{ $entity->parentEntity->name }}</a>
                    @elsecanShow
                        {{ $entity->parentEntity->name }}
                    @endcanShow
                @endif
            </td>
            <th width="10%">
                {{ trans('cruds.entity.fields.subsidiaries') }}
            </th>
            <td colspan="7">
                @foreach($entity->entities as $e)
                    @canShow($e)
                        <a href="{{ route('admin.entities.show', $e->id) }}">{{ $e->name }}</a>
                    @elsecanShow
                        {{ $e->name }}
                    @endcanShow
                    @if(!$loop->last)
                    ,
                    @endif
                @endforeach
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


        @canAccess(App\Models\Relation::class)
        <tr>
            <th>{{ trans('cruds.entity.fields.relations') }}</th>
            <td colspan="7">
                @foreach ($entity->sourceRelations as $relation)
                    @canShow($relation)
                        <a href="/admin/relations/{{ $relation->id }}">{{ $relation->name }}</a>
                    @elsecanShow
                        {{ $relation->name }}
                    @endcanShow
                    ->
                    @if ($relation->destination)
                        @canShow($relation->destination)
                            <a href="/admin/entities/{{ $relation->destination_id }}">{{ $relation->destination->name ?? '' }}</a>
                        @elsecanShow
                            {{ $relation->destination->name ?? '' }}
                        @endcanShow
                    @endif
                    @if (!$loop->last)
                    <br>
                    @endif
                @endforeach
                @if (($entity->sourceRelations->count()>0)&&($entity->destinationRelations->count()>0))
                <br>
                @endif
                @foreach ($entity->destinationRelations as $relation)
                    @if ($relation->source)
                        @canShow($relation->source)
                            <a href="/admin/entities/{{ $relation->source_id }}">{{ $relation->source->name ?? '' }}</a>
                        @elsecanShow
                            {{ $relation->source->name ?? '' }}
                        @endcanShow
                    @endif
                    <-
                    @canShow($relation)
                        <a href="/admin/relations/{{ $relation->id }}">{{ $relation->name }}</a>
                    @elsecanShow
                        {{ $relation->name }}
                    @endcanShow
                    @if (!$loop->last)
                    <br>
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
        @canAccess(App\Models\Process::class)
        <tr>
            <th>{{ trans('cruds.entity.fields.processes') }}</th>
            <td colspan="7">
                @foreach ($entity->processes as $process)
                    @canShow($process)
                        <a href="/admin/processes/{{ $process->id }}">{{ $process->name }}</a>
                    @elsecanShow
                        {{ $process->name }}
                    @endcanShow
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
        @canAccessAny(App\Models\Application::class, App\Models\Database::class)
        <tr>
            <th>{{ trans('cruds.entity.fields.exploits') }}</th>
            <td colspan="7">
                @foreach($entity->respApplications as $application)
                    @canShow($application)
                        <a href="/admin/applications/{{$application->id}}">{{$application->name}}</a>
                    @elsecanShow
                        {{$application->name}}
                    @endcanShow
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
                    @canShow($database)
                        <a href="/admin/databases/{{$database->id}}">{{$database->name}}</a>
                    @elsecanShow
                        {{$database->name}}
                    @endcanShow
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccessAny
    </tbody>
</table>
