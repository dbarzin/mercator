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
            <td colspan="3">
            @if($withLink)
            <a href="{{ route('admin.mans.show', $man) }}">{{ $man->name }}</a>
            @else
            {{ $man->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
            {{ trans('cruds.man.fields.description') }}
            </th>
            <td colspan="3">
            {!! $man->description !!}
            </td>
        </tr>
        <tr>
            <th>
            {{ trans('cruds.man.fields.wans') }}
            </th>
            <td width="40%">
                @foreach($man->wans as $wan)
                <a href="{{ route('admin.wans.show', $wan) }}">{{ $wan->name }}</a>
                @if(!$loop->last), @endif
                @endforeach

            </td>
            <th width="10%">
            {{ trans('cruds.man.fields.parent_man') }}
            </th>
            <td width="40%">
            @if($man->parentMan!==null)
            <a href="{{ route('admin.mans.show', $man->parentMan) }}">{{ $man->parentMan->name }}</a>
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
