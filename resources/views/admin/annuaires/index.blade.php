@extends('layouts.admin')
@section('content')
@can('annuaire_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.annuaires.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.annuaire.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.annuaire.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.annuaire.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.annuaire.fields.solution') }}
                        </th>
                        <th>
                            {{ trans('cruds.annuaire.fields.zone_admin') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($annuaires as $key => $annuaire)
                        <tr data-entry-id="{{ $annuaire->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.annuaires.show', $annuaire->id) }}">
                                {{ $annuaire->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $annuaire->solution ?? '' }}
                            </td>
                            <td>
                                @if ($annuaire->zone_admin!=null)
                                <a href="{{ route('admin.zone-admins.show', $annuaire->zone_admin->id) }}">
                                    {{ $annuaire->zone_admin->name ?? '' }}
                                </a>
                                @endif
                            </td>

                            <td nowrap>
                                @can('annuaire_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.annuaires.show', $annuaire->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('annuaire_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.annuaires.edit', $annuaire->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('annuaire_delete')
                                    <form action="{{ route('admin.annuaires.destroy', $annuaire->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.annuaire.title_singular"),
    'URL' => route('admin.annuaires.massDestroy'),
    'canDelete' => auth()->user()->can('annuaire_delete') ? true : false
));
</script>
@endsection
