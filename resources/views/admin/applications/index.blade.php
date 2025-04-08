@extends('layouts.admin')
@section('content')
@can('m_application_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.applications.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.application.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.application.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.application.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.responsible') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.entity_resp') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.application_block') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.attributes') }}
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $key => $application)
                        <tr data-entry-id="{{ $application->id }}"

                        @if (
                                ($application->description==null)||
                                ($application->responsible==null)||
                                ($application->technology==null)||
                                ($application->type==null)||
                                ($application->processes->count()==0)||
                                ((auth()->user()->granularity>=2)&&
                                    (
                                    ($application->entities->count()==0)||
                                    ($application->entity_resp_id==null)||
                                    ($application->users==null)||
                                    ($application->security_need_c==null)||
                                    ($application->security_need_i==null)||
                                    ($application->security_need_a==null)||
                                    ($application->security_need_t==null)||
                                    ($application->applicationBlock==null)
                                    )
                                )
                                /*
                                ||
                                ((auth()->user()->granularity==3)&&
                                    (
                                    ($application->vendor==null)||
                                    ($application->product==null)||
                                    ($application->version==null)
                                    )
                                )
                                */
                            )
                            class="table-warning"
                        @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                    {{ $application->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $application->description ?? '' !!}
                            </td>
                            <td>
                                {{ $application->responsible ?? '' }}
                            </td>
                            <td>
                                @if ($application->entityResp!=null)
                                <a href="{{ route('admin.entities.show', $application->entityResp->id) }}">
                                {{ $application->entityResp->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td>
                                @if ($application->applicationBlock!=null)
                                <a href="{{ route('admin.application-blocks.show', $application->applicationBlock->id) }}">
                                {{ $application->applicationBlock->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td>
                                @php
                                foreach(explode(" ",$application->attributes) as $a)
                                    echo "<div class='badge badge-info'>$a</div> ";
                                @endphp
                            </td>
                            <td nowrap>
                                @can('m_application_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.applications.show', $application->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @if(auth()->user()->can('m_application_edit') && auth()->user()->can('is-cartographer-m-application', $application))
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.applications.edit', $application->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif

                                @if(auth()->user()->can('m_application_delete') && auth()->user()->can('is-cartographer-m-application', $application))
                                    <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endif
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
    'title' => trans("cruds.application.title_singular"),
    'URL' => route('admin.applications.massDestroy'),
    'canDelete' => auth()->user()->can('m_application_delete') ? true : false
));
</script>
@endsection
