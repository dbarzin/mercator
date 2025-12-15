@extends('layouts.admin')
@section('content')
    @can('site_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route('admin.sites.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.site.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.site.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('cruds.site.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.site.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.site.fields.buildings') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sites as $site)
                        <tr data-entry-id="{{ $site->id }}"
                            @if (
                                ($site->description===null)
                                )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.sites.show', $site->id) }}">
                                    {{ $site->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $site->description ?? '' !!}
                            </td>
                            <td>
                                @foreach($site->buildings as $building)
                                    <a href="{{ route('admin.buildings.show', $building->id) }}">
                                        {{ $building->name ?? '' }}
                                    </a>
                                    @if ($site->buildings->last()!=$building)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('site_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.sites.show', $site->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('site_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.sites.edit', $site->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('site_delete')
                                    <form action="{{ route('admin.sites.destroy', $site->id) }}" method="POST"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
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
    <script>
        @include('partials.datatable', array(
            'id' => '#dataTable',
            'title' => trans("cruds.site.title_singular"),
            'URL' => route('admin.sites.massDestroy'),
            'canDelete' => auth()->user()->can('site_delete') ? true : false
        ));
    </script>
@endsection
