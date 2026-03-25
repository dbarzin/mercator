@props([
    'workstation',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $workstation->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.workstation.fields.name') }}
            </th>
            <td>
            @if($withLink)
            <a href="{{ route('admin.workstations.show', $workstation->id) }}">
                {{ $workstation->name }}
             </a>
            @else
                {{ $workstation->name }}
            @endif
            </td>
            <th width="10%">{{ trans('cruds.workstation.fields.type') }}</th>
            <td >{{ $workstation->type }}</td>
            <th width="10%">{{ trans('cruds.workstation.fields.status') }}</th>
            <td colspan="2">{{ $workstation->status }}</td>
        </tr>
        <tr>
            <td width="10%">{{ trans('cruds.workstation.fields.description') }}</td>
            <td width="80%" colspan="5">{!! $workstation->description !!}</td>
            <td width="10%" align="center">
                <img src="{{ $workstation->icon_id === null ? '/images/workstation.png' : route('admin.documents.show', $workstation->icon_id) }}" width='60' height='60'/>
            </td>
       </tr>
       <tr>
            <th width="10%">{{ trans('cruds.workstation.fields.manufacturer') }}</th>
            <td width="20%">{{ $workstation->manufacturer }}</td>
            <th width="10%">{{ trans('cruds.workstation.fields.model') }}</th>
            <td width="20%">{{ $workstation->model }}</td>
            <th width="10%">{{ trans('cruds.workstation.fields.serial_number') }}</th>
            <td width="20%" colspan="2">{{ $workstation->serial_number }}</td>
       </tr>
       <tr>
            <th>{{ trans('cruds.workstation.fields.cpu') }}</th>
            <td>{{ $workstation->cpu }}</td>
            <th>{{ trans('cruds.workstation.fields.memory') }}</th>
            <td>{{ $workstation->memory }}</td>
            <th>{{ trans('cruds.workstation.fields.disk') }}</th>
            <td colspan="2">{{ $workstation->disk }}</td>
       </tr>
       <tr>
            <th width="10%">{{ trans('cruds.workstation.fields.operating_system') }}</th>
            <td colspan="6">{{ $workstation->operating_system ?? '' }}</td>
       </tr>

    </tbody>
</table>