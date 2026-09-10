@props([
    'applicationFlow',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $flow->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.applicationFlow.fields.name') }}
        </th>
        <td width="30%">
        @if ($withLink)
        @canShow($flow)
        <a href="{{ route('admin.application-flows.show', $flow->id) }}">{{ $flow->name }}</a>
        @elsecanShow
        {{ $flow->name }}
        @endcanShow
        @else
            {{ $flow->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.applicationFlow.fields.type') }}
        </th>
        <td width="20%">
            {{ $flow->type }}
        </td>
        <th width="10%">
            {{ trans('cruds.applicationFlow.fields.attributes') }}
        </th>
        <td width="20%">
            @foreach(explode(" ",$flow->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.applicationFlow.fields.description') }}
        </th>
        <td colspan="5">
            {!! $flow->description !!}
        </td>
    </tr>

    @canAccessAny(App\Models\Application::class, App\Models\ApplicationService::class, App\Models\ApplicationModule::class, App\Models\Database::class)
    <tr>
        <th>
            {{ trans('cruds.applicationFlow.fields.source') }}
        </th>
        <td colspan="1">
            @if ($flow->applicationSource!=null)
                @canShow($flow->applicationSource)
                    <a href="{{ route('admin.applications.show',$flow->applicationSource->id) }}">{{ $flow->applicationSource->name }}</a>
                @elsecanShow
                    {{ $flow->applicationSource->name }}
                @endcanShow
                [Application]
            @endif
            @if($flow->serviceSource!=null)
                @canShow($flow->serviceSource)
                    <a href="{{ route('admin.application-services.show', $flow->serviceSource->id) }}">{{ $flow->serviceSource->name }}</a>
                @elsecanShow
                    {{ $flow->serviceSource->name }}
                @endcanShow
                [Service]
            @endif
            @if ($flow->moduleSource!=null)
                @canShow($flow->moduleSource)
                    <a href="{{ route('admin.application-modules.show', $flow->moduleSource->id) }}">{{ $flow->moduleSource->name }}</a>
                @elsecanShow
                    {{ $flow->moduleSource->name }}
                @endcanShow
                [Module]
            @endif
            @if ($flow->databaseSource!=null)
                @canShow($flow->databaseSource)
                    <a href="{{ route('admin.databases.show',$flow->databaseSource->id) }}">{{ $flow->databaseSource->name }}</a>
                @elsecanShow
                    {{ $flow->databaseSource->name }}
                @endcanShow
                [Database]
            @endif
        </td>

        <th>
            {{ trans('cruds.applicationFlow.fields.destination') }}
        </th>
        <td colspan="3">
            @if ($flow->applicationDest!=null)
                @canShow($flow->applicationDest)
                    <a href="{{ route('admin.applications.show',$flow->applicationDest->id) }}">{{ $flow->applicationDest->name }}</a>
                @elsecanShow
                    {{ $flow->applicationDest->name }}
                @endcanShow
                [Application]
            @endif
            @if ($flow->serviceDest!=null)
                @canShow($flow->serviceDest)
                    <a href="{{ route('admin.application-services.show', $flow->serviceDest->id) }}">{{ $flow->serviceDest->name }}</a>
                @elsecanShow
                    {{ $flow->serviceDest->name }}
                @endcanShow
                [Service]
            @endif
            @if ($flow->moduleDest!=null)
                @canShow($flow->moduleDest)
                    <a href="{{ route('admin.application-modules.show', $flow->moduleDest->id) }}">{{ $flow->moduleDest->name }}</a>
                @elsecanShow
                    {{ $flow->moduleDest->name }}
                @endcanShow
                [Module]
            @endif
            @if ($flow->databaseDest!=null)
                @canShow($flow->databaseDest)
                    <a href="{{ route('admin.databases.show',$flow->databaseDest->id) }}">{{ $flow->databaseDest->name }}</a>
                @elsecanShow
                    {{ $flow->databaseDest->name }}
                @endcanShow
                [Database]
            @endif
        </td>
    </tr>
    @endcanAccessAny
    @canAccess(App\Models\Information::class)
    <tr>
        <th>
            {{ trans('cruds.applicationFlow.fields.information') }}
        </th>
        <td colspan="5">
            @foreach($flow->informations as $info)
                @canShow($info)
                    <a href="{{ route('admin.information.show',$info->id) }}">{{ $info->name }}</a>
                @elsecanShow
                    {{ $info->name }}
                @endcanShow
                @if (!$loop->last) , @endif
            @endforeach
        </td>
    </tr>
    @endcanAccess
    <tr>
        <th>
            {{ trans('cruds.applicationFlow.fields.crypted') }}
        </th>
        <td>
            @if ($flow->crypted==0)
                Non
            @elseif ($flow->crypted==1)
                Oui
            @endif
        </td>
        <th>
            {{ trans('cruds.applicationFlow.fields.bidirectional') }}
        </th>
        <td colspan="3">
            @if ($flow->bidirectional==0)
                Non
            @elseif ($flow->bidirectional==1)
                Oui
            @endif
        </td>
    </tr>
    </tbody>
</table>
