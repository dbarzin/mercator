@props([
    'domain',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $domain->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.domain.fields.name') }}
            </th>
            <td>
            @if ($withLink)
                @canShow($domain)
                <a href="{{ route('admin.domains.show', $domain->id) }}">{{ $domain->name }}</a>
                @elsecanShow
                {{ $domain->name }}
                @endcanShow
            @else
                {{ $domain->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.domain.fields.type') }}
            </th>
            <td width="20%">
                {{ $domain->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.domain.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $domain->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domain.fields.description') }}
            </th>
            <td colspan="5">
                {!! $domain->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domain.fields.domain_ctrl_cnt') }}
            </th>
            <td>
                {{ $domain->domain_ctrl_cnt }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domain.fields.user_count') }}
            </th>
            <td>
                {{ $domain->user_count }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domain.fields.machine_count') }}
            </th>
            <td>
                {{ $domain->machine_count }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domain.fields.relation_inter_domaine') }}
            </th>
            <td>
                {{ $domain->relation_inter_domaine }}
            </td>
        </tr>
        @canAccess(App\Models\ForestAd::class)
        <tr>
            <th>
                {{ trans('cruds.domain.fields.forestAds') }}
            </th>
            <td>
                @foreach($domain->forestAds as $forestAd)
                    @canShow($forestAd)
                        <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                        {{ $forestAd->name }}
                        </a>
                    @elsecanShow
                        {{ $forestAd->name }}
                    @endcanShow
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
        @canAccess(App\Models\LogicalServer::class)
        <tr>
            <th>
                {{ trans('cruds.domain.fields.logical_servers') }}
            </th>
            <td>
                @foreach($domain->logicalServers as $logicalServer)
                    @canShow($logicalServer)
                        <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                        {{ $logicalServer->name }}
                        </a>
                    @elsecanShow
                        {{ $logicalServer->name }}
                    @endcanShow
                    @if ($loop->last!=$logicalServer)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
    </tbody>
</table>
