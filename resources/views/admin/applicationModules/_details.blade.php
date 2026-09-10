@props([
    'applicationModule',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $applicationModule->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.applicationModule.fields.name') }}
            </th>
            <td>
            @if ($withLink)
                @canShow($applicationModule)
                <a href='{{ route("admin.application-modules.show", $applicationModule->id) }}'>{{ $applicationModule->name }}</a>
                @elsecanShow
                    {{ $applicationModule->name }}
                @endcanShow
            @else
                {{ $applicationModule->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.applicationModule.fields.type') }}
            </th>
            <td width="20%">
                {{ $applicationModule->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.applicationModule.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $applicationModule->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationModule.fields.description') }}
            </th>
            <td colspan="5">
                {!! $applicationModule->description !!}
            </td>
        </tr>
        @canAccess(App\Models\Entity::class)
        <tr>
            <th>
                {{ trans('cruds.applicationModule.fields.entities') }}
            </th>
            <td>
                @foreach($applicationModule->entities as $entity)
                    @canShow($entity)
                        <a href="{{ route('admin.entities.show', $entity->id) }}">{{ $entity->name }}</a>
                    @elsecanShow
                        {{ $entity->name }}
                    @endcanShow
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
        @canAccess(App\Models\ApplicationService::class)
        <tr>
            <th>
                {{ trans('cruds.applicationModule.fields.services') }}
            </th>
            <td>
                @foreach($applicationModule->applicationServices as $service)
                    @canShow($service)
                        <a href="{{ route('admin.application-services.show', $service->id) }}">{{ $service->name }}</a>
                    @elsecanShow
                        {{ $service->name }}
                    @endcanShow
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
    </tbody>
</table>
