<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.applicationService.fields.name') }}
            </th>
            <td>
                {{ $applicationService->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationService.fields.description') }}
            </th>
            <td>
                {!! $applicationService->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationService.fields.exposition') }}
            </th>
            <td>
                {{ $applicationService->exposition }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationService.fields.modules') }}
            </th>
            <td>
                @foreach($applicationService->modules as $module)
                    <a href="{{ route('admin.application-modules.show', $module->id) }}">
                    {{ $module->name }}
                    </a>
                    @if ($applicationService->modules->last()!=$module)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
