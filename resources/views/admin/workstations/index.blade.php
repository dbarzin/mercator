@extends('layouts.admin')
@section('content')
@can('workstation_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route("admin.workstations.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.workstation.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.workstation.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.workstation.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.workstation.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.workstation.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.workstation.fields.building') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workstations as $key => $workstation)
                        <tr data-entry-id="{{ $workstation->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.workstations.show', $workstation->id) }}">
                                {{ $workstation->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $workstation->type ?? '' !!}
                            </td>
                            <td>
                                @if ($workstation->site!=null)
                                    <a href="{{ route('admin.sites.show', $workstation->site->id) }}">
                                        {{ $workstation->site->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($workstation->building!=null)
                                <a href="{{ route('admin.buildings.show', $workstation->building->id) }}">
                                    {{ $workstation->building->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td nowrap>
                                @can('workstation_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.workstations.show', $workstation->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('workstation_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.workstations.edit', $workstation->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('workstation_delete')
                                    <form action="{{ route('admin.workstations.destroy', $workstation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.workstation.title_singular"),
    'URL' => route('admin.workstations.massDestroy'),
    'canDelete' => auth()->user()->can('workstation_delete') ? true : false
));
</script>
@endsection
