@props([
    'applicationBlock',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $applicationBlock->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.applicationBlock.fields.name') }}
            </th>
            <td>
            @if($withLink)
            @canShow($applicationBlock)
            <a href="{{ route('admin.application-blocks.show',$applicationBlock->id) }}">{{ $applicationBlock->name }}</a>
            @elsecanShow
            {{ $applicationBlock->name }}
            @endcanShow
            @else
            {{ $applicationBlock->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.applicationBlock.fields.type') }}
            </th>
            <td width="20%">
                {{ $applicationBlock->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.applicationBlock.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $applicationBlock->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationBlock.fields.description') }}
            </th>
            <td colspan="5">
                {!! $applicationBlock->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationBlock.fields.responsible') }}
            </th>
            <td>
                {{ $applicationBlock->responsible }}
            </td>
        </tr>
        @canAccess(App\Models\Application::class)
        <tr>
            <th>
                {{ trans('cruds.applicationBlock.fields.applications') }}
            </th>
            <td>
                @foreach($applicationBlock->applications as $key => $application)
                    @canShow($application)
                        <a href="{{ route('admin.applications.show',$application->id) }}">{{ $application->name }}</a>
                    @elsecanShow
                        {{ $application->name }}
                    @endcanShow
                    @if(!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
    </tbody>
</table>
