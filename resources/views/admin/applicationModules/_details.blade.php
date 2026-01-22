<table class="table table-bordered table-striped">
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
