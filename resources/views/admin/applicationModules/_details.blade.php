<table class="table table-bordered table-striped table-report" id="{{ $applicationModule->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.applicationModule.fields.name') }}
            </th>
            <td>
                {{ $applicationModule->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationModule.fields.description') }}
            </th>
            <td>
                {!! $applicationModule->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationModule.fields.entities') }}
            </th>
            <td>
                @foreach($applicationModule->entities as $entity)
                    <a href="{{ route('admin.entities.show', $entity->id) }}">
                    {{ $entity->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationModule.fields.services') }}
            </th>
            <td>
                @foreach($applicationModule->applicationServices as $service)
                    <a href="{{ route('admin.application-services.show', $service->id) }}">
                    {{ $service->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
