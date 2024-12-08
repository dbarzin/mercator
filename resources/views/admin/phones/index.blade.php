@extends('layouts.admin')
@section('content')
@can('phone_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.phones.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.phone.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.phone.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.address_ip') }}
                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.building') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($phones as $key => $phone)
                        <tr data-entry-id="{{ $phone->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.phones.show', $phone->id) }}">
                                {{ $phone->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $phone->type ?? '' }}
                            </td>
                            <td>
                                {{ $phone->address_ip ?? '' }}
                            </td>
                            <td>
                                @if ($phone->site!==null)
                                    <a href="{{ route('admin.sites.show', $phone->site_id) }}">{{ $phone->site->name }}</a>
                                @endif
                            </td>
                            <td>
                                @if ($phone->building!==null)
                                    <a href="{{ route('admin.buildings.show', $phone->building_id) }}">{{ $phone->building->name }}</a>
                                @endif
                            </td>
                            <td nowrap>
                                @can('phone_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.phones.show', $phone->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('phone_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.phones.edit', $phone->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('phone_delete')
                                    <form action="{{ route('admin.phones.destroy', $phone->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.phone.title_singular"),
    'URL' => route('admin.phones.massDestroy'),
    'canDelete' => auth()->user()->can('phone_delete') ? true : false
));
</script>
@endsection
