@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.processes.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=PROCESS_{{$process->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('process_edit')
        <a class="btn btn-info" href="{{ route('admin.processes.edit', $process->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('process_delete')
        <form action="{{ route('admin.processes.destroy', $process->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan

</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.process.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.process.fields.name') }}
                        </th>
                        <td colspan="2">
                            {{ $process->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.description') }}
                        </th>
                        <td>
                            {!! $process->description !!}
                        </td>
                        <td width="10%">
                            @if ($process->icon_id === null)
                            <img src='/images/process.png' width='120' height='120'>
                            @else
                            <img src='{{ route('admin.documents.show', $process->icon_id) }}' width='120' height='120'>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.in_out') }}
                        </th>
                        <td colspan="2">
                            {!! $process->in_out !!}
                        </td>
                    </tr>
                    @if (auth()->user()->granularity>=3)
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.activities') }}
                        </th>
                        <td colspan="2">
                            @foreach($process->activities as $activity)
                                <a href="{{ route('admin.activities.show', $activity->id) }}">
                                {{ $activity->name }}
                                </a>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.entities') }}
                        </th>
                        <td colspan="2">
                            @foreach($process->entities as $entity)
                                <a href="{{ route('admin.entities.show', $entity->id) }}">
                                {{ $entity->name }}
                                </a>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.informations') }}
                        </th>
                        <td colspan="2">
                            @foreach($process->information as $info)
                                <a href="{{ route('admin.information.show', $info->id) }}">
                                {{ $info->name }}
                                </a>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.security_need') }}
                        </th>
                        <td colspan="2">
                        {{ trans('global.confidentiality') }} :
                            @if ($process->security_need_c==0){{ trans('global.none') }}@endif
                            @if ($process->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($process->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($process->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($process->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        &nbsp;
                        {{ trans('global.integrity') }} :
                            @if ($process->security_need_i==0){{ trans('global.none') }}@endif
                            @if ($process->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($process->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($process->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($process->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        &nbsp;
                        {{ trans('global.availability') }} :
                            @if ($process->security_need_a==0){{ trans('global.none') }}@endif
                            @if ($process->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($process->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($process->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($process->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        &nbsp;
                        {{ trans('global.tracability') }} :
                            @if ($process->security_need_t==0){{ trans('global.none') }}@endif
                            @if ($process->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($process->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($process->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($process->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        @if (config('mercator-config.parameters.security_need_auth'))
                        &nbsp;
                        {{ trans('global.authenticity') }} :
                            @if ($process->security_need_auth==0){{ trans('global.none') }}@endif
                            @if ($process->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                            @if ($process->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                            @if ($process->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                            @if ($process->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.owner') }}
                        </th>
                        <td colspan="2">
                            {{ $process->owner }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.macroprocessus') }}
                        </th>
                        <td colspan="2">
                            @if($process->macroProcess!=null)
                                <a href="{{ route('admin.macro-processuses.show', $process->macroProcess->id) }}">
                                    {{ $process->macroProcess->name }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $process->created_at ? $process->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $process->updated_at ? $process->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.processes.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
