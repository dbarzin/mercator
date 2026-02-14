<table class="table table-bordered table-striped table-report" id="{{ $wan->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.wan.fields.name') }}
            </th>
            <td>
                {{ $wan->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.wan.fields.mans') }}
            </th>
            <td>
                @foreach($wan->mans as $key => $mans)
                    <span class="label label-info">{{ $mans->name }}</span>
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.wan.fields.lans') }}
            </th>
            <td>
                @foreach($wan->lans as $key => $lans)
                    <span class="label label-info">{{ $lans->name }}</span>
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
