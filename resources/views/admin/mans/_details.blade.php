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
            @canShow($man)
            <a href="{{ route('admin.mans.show', $man) }}">{{ $man->name }}</a>
            @elsecanShow
            {{ $man->name }}
            @endcanShow
            @else
            {{ $man->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.man.fields.type') }}
            </th>
            <td width="20%">
                {{ $man->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.man.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $man->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
            {{ trans('cruds.man.fields.description') }}
            </th>
            <td colspan="7">
            {!! $man->description !!}
            </td>
        </tr>
        @canAccessAny(App\Models\Wan::class, App\Models\Man::class)
        <tr>
            <th>
            {{ trans('cruds.man.fields.wans') }}
            </th>
            <td width="40%">
                @foreach($man->wans as $wan)
                @canShow($wan)
                <a href="{{ route('admin.wans.show', $wan) }}">{{ $wan->name }}</a>
                @elsecanShow
                {{ $wan->name }}
                @endcanShow
                @if(!$loop->last), @endif
                @endforeach

            </td>
            <th width="10%">
            {{ trans('cruds.man.fields.parent_man') }}
            </th>
            <td width="40%">
            @if($man->parentMan!==null)
            @canShow($man->parentMan)
            <a href="{{ route('admin.mans.show', $man->parentMan) }}">{{ $man->parentMan->name }}</a>
            @elsecanShow
            {{ $man->parentMan->name }}
            @endcanShow
            @endif
            </td>
        </tr>
        @endcanAccessAny
        @canAccess(App\Models\Lan::class)
        <tr>
            <th>
                {{ trans('cruds.man.fields.lans') }}
            </th>
            <td>
                @foreach($man->lans as $lan)
                @canShow($lan)
                <a href="{{ route('admin.lans.show', $lan) }}">{{ $lan->name }}</a>
                @elsecanShow
                {{ $lan->name }}
                @endcanShow
                @if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
    </tbody>
</table>
