@props([
    'network',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $subnetwork->getUID() }}">
    <tbody>
    <tr>
        <th width='10%'>
            {{ trans('cruds.subnetwork.fields.name') }}
        </th>
        <td>
        @if ($withLink)
        <a href="{{ route('admin.subnetworks.show', $subnetwork) }}">{{ $subnetwork->name }}</a>
        @else
        {{ $subnetwork->name }}
        @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.subnetwork.fields.description') }}
        </th>
        <td>
            {!! $subnetwork->description !!}
        </td>
    </tr>
    </tbody>
</table>
