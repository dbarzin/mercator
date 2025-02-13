@extends('layouts.admin')
@section('content')
@can('security_controls_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.security-controls.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.securityControl.title_singular') }}
            </a>
        &nbsp;
            <a class="btn btn-success" href="{{ route('admin.security-controls.assign') }}">
                Assigner des Mesures de sécurité
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.securityControl.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.securityControl.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.securityControl.fields.description') }}
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($controls as $control)
                        <tr data-entry-id="{{ $control->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.security-controls.show', $control->id) }}">
                                {{ $control->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $control->description ?? '' !!}
                            </td>
                            <td nowrap>
                                @can('security_controls_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.security-controls.show', $control->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('security_controls_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.security-controls.edit', $control->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('security_controls_delete')
                                    <form action="{{ route('admin.security-controls.destroy', $control->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.securityControl.title_singular"),
    'URL' => route('admin.security-controls.massDestroy'),
    'canDelete' => auth()->user()->can('data_processing_register_delete') ? true : false
));
</script>
@endsection
