@props([
    'wan',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $wan->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.wan.fields.name') }}
            </th>
            <td>
            @if($withLink)
            <a href="{{ route('admin.wans.show', $wan) }}">{{ $wan->name }}</a>
            @else
            {{ $wan->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.wan.fields.mans') }}
            </th>
            <td>
                @foreach($wan->mans as $mans)
                <a href="{{ route('admin.mans.show', $mans) }}">{{ $mans->name }}</a>
                @if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.wan.fields.lans') }}
            </th>
            <td>
                @foreach($wan->lans as $lan)
                <a href="{{ route('admin.lans.show', $lan) }}">{{ $lan->name }}</a>
                @if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
