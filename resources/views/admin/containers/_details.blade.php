<table class="table table-bordered table-striped table-report" id="{{ $container->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.container.fields.name') }}
            </th>
            <td>
                {{ $container->name }}
            </td>
            <th width="10%">
                <dt>{{ trans('cruds.container.fields.type') }}</dt>
            </th>
            <td width="10%">
                {{ $container->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.container.fields.description') }}
            </th>
            <td colspan="2">
                {!! $container->description !!}
            </td>
            <td width="10%">
                @if ($container->icon_id === null)
                <img src='/images/container.png' width='60' height='60'>
                @else
                <img src='{{ route('admin.documents.show', $container->icon_id) }}' width='60' height='60'>
                @endif
            </td>
        </tr>
    </tbody>
</table>
