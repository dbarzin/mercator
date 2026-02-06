<table class="table table-bordered table-striped table-report" id="{{ $domaineAd->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.domaineAd.fields.name') }}
            </th>
            <td>
                {{ $domaineAd->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaineAd.fields.description') }}
            </th>
            <td>
                {!! $domaineAd->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaineAd.fields.domain_ctrl_cnt') }}
            </th>
            <td>
                {{ $domaineAd->domain_ctrl_cnt }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaineAd.fields.user_count') }}
            </th>
            <td>
                {{ $domaineAd->user_count }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaineAd.fields.machine_count') }}
            </th>
            <td>
                {{ $domaineAd->machine_count }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaineAd.fields.relation_inter_domaine') }}
            </th>
            <td>
                {{ $domaineAd->relation_inter_domaine }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.forestAd.title') }}
            </th>
            <td>
                @foreach($domaineAd->forestAds as $forestAd)
                    <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                    {{ $forestAd->name }}
                    </a>
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.logicalServer.title') }}
            </th>
            <td>
                @foreach($domaineAd->logicalServers as $logicalServer)
                    <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                    {{ $logicalServer->name }}
                    </a>
                    @if ($loop->last!=$logicalServer)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
