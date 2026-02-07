<table class="table table-bordered table-striped table-report" id="{{ $annuaire->getUID() }}">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.annuaire.fields.name') }}
            </th>
            <td>
                {{ $annuaire->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.annuaire.fields.description') }}
            </th>
            <td>
                {!! $annuaire->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.annuaire.fields.solution') }}
            </th>
            <td>
                {{ $annuaire->solution }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.annuaire.fields.zone_admin') }}
            </th>
            <td>
                @if ($annuaire->zone_admin!=null)
                <a href="{{ route('admin.zone-admins.show', $annuaire->zone_admin->id) }}">
                {{ $annuaire->zone_admin->name ?? '' }}
                @endif
                </a>
            </td>
        </tr>
    </tbody>
</table>
