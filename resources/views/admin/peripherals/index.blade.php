@extends('layouts.admin')
@section('content')
    @can('peripheral_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route("admin.peripherals.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.peripheral.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.peripheral.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.domain') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.provider') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.responsible') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.address_ip') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.bay') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($peripherals as $peripheral)
                        <tr data-entry-id="{{ $peripheral->id }}"
                            @if (
                                    ($peripheral->description===null)||
                                    ($peripheral->site_id===null)||
                                    ($peripheral->building_id===null)||
                                    ($peripheral->responsible===null)
                                    )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.peripherals.show', $peripheral->id) }}">
                                    {{ $peripheral->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $peripheral->domain ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->type ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->provider->name ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->responsible ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->address_ip ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->site->name ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->building->name ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->bay->name ?? '' }}
                            </td>
                            <td nowrap>
                                @can('peripheral_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.peripherals.show', $peripheral->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('peripheral_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.peripherals.edit', $peripheral->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('peripheral_delete')
                                    <form action="{{ route('admin.peripherals.destroy', $peripheral->id) }}"
                                          method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
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
            'title' => trans("cruds.peripheral.title_singular"),
            'URL' => route('admin.peripherals.massDestroy'),
            'canDelete' => auth()->user()->can('peripheral_delete') ? true : false
        ));
    </script>
@endsection
