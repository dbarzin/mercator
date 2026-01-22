<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.man.fields.name') }}
            </th>
            <td>
                {{ $man->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.man.fields.lans') }}
            </th>
            <td>
                @foreach($man->lans as $key => $lans)
                    <span class="label label-info">{{ $lans->name }}</span>
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
