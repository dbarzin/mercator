@extends('layouts.admin')
@section('content')
    @can('application_module_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route('admin.application-modules.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.applicationModule.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.applicationModule.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.applicationModule.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.applicationModule.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($applicationModules as $key => $applicationModule)
                        <tr data-entry-id="{{ $applicationModule->id }}"
                            @if ($applicationModule->description==null)
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.application-modules.show', $applicationModule->id) }}">
                                    {{ $applicationModule->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $applicationModule->description !!}
                            </td>
                            <td nowrap>
                                @can('application_module_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.application-modules.show', $applicationModule->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('application_module_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.application-modules.edit', $applicationModule->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('application_module_delete')
                                    <form action="{{ route('admin.application-modules.destroy', $applicationModule->id) }}"
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
            'title' => trans("cruds.applicationModule.title_singular"),
            'URL' => route('admin.application-modules.massDestroy'),
            'canDelete' => auth()->user()->can('application_module_delete') ? true : false
        ));
    </script>
@endsection
