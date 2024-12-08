@extends('layouts.admin')
@section('content')
@can('database_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.databases.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.database.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.database.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.database.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.database.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.database.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.database.fields.informations') }}
                        </th>
                        <th>
                            {{ trans('cruds.database.fields.applications') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($databases as $key => $database)
                        <tr data-entry-id="{{ $database->id }}"
                          @if(($database->description==null)||
                              ($database->entity_resp_id==null)||
                              ($database->responsible==null)||
                              ($database->type==null)||
                              ((auth()->user()->granularity>=2)&&
                                    (
                                    ($database->security_need_c==null)||
                                    ($database->security_need_i==null)||
                                    ($database->security_need_a==null)||
                                    ($database->security_need_t==null)
                                    )
                                )
                              )
                          class="table-warning"
                          @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.databases.show', $database->id) }}">
                                {{ $database->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $database->description ?? '' !!}
                            </td>
                            <td>
                                {{ $database->type ?? '' }}
                            </td>
                            <td>
                                @foreach($database->informations as $information)
                                    <a href="{{ route('admin.information.show', $information->id) }}">
                                    {{ $information->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @foreach($database->applications as $application)
                                    <a href="{{ route('admin.applications.show', $application->id) }}">
                                    {{ $application->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>

                            <td nowrap>
                                @can('database_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.databases.show', $database->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('database_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.databases.edit', $database->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('database_delete')
                                    <form action="{{ route('admin.databases.destroy', $database->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.database.title_singular"),
    'URL' => route('admin.databases.massDestroy'),
    'canDelete' => auth()->user()->can('database_delete') ? true : false
));
</script>
@endsection
