@props([
    'man',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.man.fields.name') }}
            </th>
            <td>
            @if($withLink)
            <a href="{{ route('admin.mans.show', $man) }}">{{ $man->name }}</a>
            @else
            {{ $man->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.man.fields.lans') }}
            </th>
            <td>
                @foreach($man->lans as $lan)
                <a href="{{ route('admin.lans.show', $lan) }}">{{ $lan->name }}</a>
                @if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
