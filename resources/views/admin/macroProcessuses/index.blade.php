@extends('layouts.admin')
@section('content')
@can('macro_processus_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.macro-processuses.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.macroProcessus.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.macroProcessus.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.owner') }}
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.processes') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($macroProcessuses as $key => $macroProcessus)
                        <tr data-entry-id="{{ $macroProcessus->id }}"
                            @if(($macroProcessus->description==null)||
                                ($macroProcessus->io_elements==null)||
                                ((auth()->user()->granularity>=2)&&
                                    (
                                    ($macroProcessus->security_need_c==null)||
                                    ($macroProcessus->security_need_i==null)||
                                    ($macroProcessus->security_need_a==null)||
                                    ($macroProcessus->security_need_t==null)
                                    )
                                )||
                                    (
                                    (auth()->user()->granularity>=2) &&
                                    ($macroProcessus->owner==null)
                                    )
                                )
                                    class="table-warning"
                            @endif
                            >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.macro-processuses.show', $macroProcessus->id) }}">
                                {{ $macroProcessus->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $macroProcessus->description ?? '' !!}
                            </td>

                            <td>
                                {{ $macroProcessus->owner }}
                            </td>

                            <td>
                                @foreach($macroProcessus->processes as $process)
                                    <a href="{{ route('admin.processes.show', $process->id) }}">
                                        {{ $process->name }}
                                    </a>
                                    @if(!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>

                            <td nowrap>
                                @can('macro_processus_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.macro-processuses.show', $macroProcessus->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('macro_processus_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.macro-processuses.edit', $macroProcessus->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('macro_processus_delete')
                                    <form action="{{ route('admin.macro-processuses.destroy', $macroProcessus->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.macroProcessus.title_singular"),
    'URL' => route('admin.macro-processuses.massDestroy'),
    'canDelete' => auth()->user()->can('site_delete') ? true : false
));
</script>
@endsection
