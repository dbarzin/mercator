@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.macro-processuses.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=MACROPROCESS_{{$macroProcessus->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('macro_processus_edit')
        <a class="btn btn-info" href="{{ route('admin.macro-processuses.edit', $macroProcessus->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('macro_processus_delete')
        <form action="{{ route('admin.macro-processuses.destroy', $macroProcessus->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.macroProcessus.title') }}
    </div>

    <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.macroProcessus.fields.name') }}
                        </th>
                        <td>
                            {{ $macroProcessus->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.description') }}
                        </th>
                        <td>
                            {!! $macroProcessus->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.io_elements') }}
                        </th>
                        <td>
                            {!! $macroProcessus->io_elements !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.security_need') }}
                        </th>
                        <td>
                        {{ trans('global.confidentiality') }} :
                            @if ($macroProcessus->security_need_c==0){{ trans('global.none') }}@endif
                            @if ($macroProcessus->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($macroProcessus->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($macroProcessus->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($macroProcessus->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        &nbsp;
                        {{ trans('global.integrity') }} :
                            @if ($macroProcessus->security_need_i==0){{ trans('global.none') }}@endif
                            @if ($macroProcessus->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($macroProcessus->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($macroProcessus->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($macroProcessus->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        &nbsp;
                        {{ trans('global.availability') }} :
                            @if ($macroProcessus->security_need_a==0){{ trans('global.none') }}@endif
                            @if ($macroProcessus->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($macroProcessus->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($macroProcessus->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($macroProcessus->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        &nbsp;
                        {{ trans('global.tracability') }} :
                            @if ($macroProcessus->security_need_t==0){{ trans('global.none') }}@endif
                            @if ($macroProcessus->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($macroProcessus->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($macroProcessus->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($macroProcessus->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.owner') }}
                        </th>
                        <td>
                            {{ $macroProcessus->owner }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.processes') }}
                        </th>
                        <td>
                            @foreach($macroProcessus->processes as $macroProcessus)
                                <a href="{{ route('admin.processes.show', $macroProcessus->id) }}">
                                    {{ $macroProcessus->name }}
                                    @if(!$loop->last)
                                    ,
                                    @endif
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $macroProcessus->created_at ? $macroProcessus->created_at->format(trans('global.timestamp')) : '' }} |
            {{ trans('global.updated_at') }} {{ $macroProcessus->updated_at ? $macroProcessus->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>

    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.macro-processuses.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

@endsection
