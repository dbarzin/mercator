<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.adminUser.fields.user_id') }}
            </th>
            <td colspan="3">
                {{ $adminUser->user_id }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.adminUser.fields.firstname') }}
            </th>
            <td width="30%">
                {{ $adminUser->firstname }}
            </td>
            <th width="10%">
                {{ trans('cruds.adminUser.fields.lastname') }}
            </th>
            <td>
                {{ $adminUser->lastname }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.adminUser.fields.type') }}
            </th>
            <td>
                {{ $adminUser->type }}
            </td>
            <th>
                {{ trans('cruds.adminUser.fields.attributes') }}
            </th>
            <td>
                @php
                foreach(explode(" ",$adminUser->attributes) as $a)
                    echo "<div class='badge badge-info'>$a</div> ";
                @endphp
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.adminUser.fields.domain') }}
            </th>
            <td>
                @if ($adminUser->domain_id !== null)
                    <a href="{{ route('admin.domaine-ads.show', $adminUser->domain_id) }}">
                        {{ $adminUser->domain->name }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                <dt>{{ trans('cruds.adminUser.fields.description') }}</dt>
            </th>
            <td colspan="3">
                {!! $adminUser->description !!}
            </td>
        </tr>
    </tbody>
</table>
