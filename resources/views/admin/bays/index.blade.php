@extends('layouts.admin')
@section('content')
    @can('bay_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route('admin.bays.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.bay.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.bay.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.bay.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.bay.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.bay.fields.room') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bays as $bay)
                        <tr data-entry-id="{{ $bay->id }}"
                            @if(
                                ($bay->description===null)
                                )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.bays.show', $bay->id) }}">
                                    {{ $bay->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $bay->description ?? '' !!}
                            </td>
                            <td>
                                @if ($bay->room!=null)
                                    <a href="{{ route('admin.buildings.show', $bay->room->id) }}">
                                        {{ $bay->room->name }}
                                    </a>
                                @endif
                            </td>
                            <td nowrap>
                                @can('bay_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.bays.show', $bay->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('bay_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.bays.edit', $bay->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('bay_delete')
                                    <form action="{{ route('admin.bays.destroy', $bay->id) }}" method="POST"
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
    @parent
    <script>
        @include('partials.datatable', array(
            'id' => '#dataTable',
            'title' => trans("cruds.bay.title_singular"),
            'URL' => route('admin.bays.massDestroy'),
            'canDelete' => auth()->user()->can('bay_delete') ? true : false
        ));
    </script>
@endsection
