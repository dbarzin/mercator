@props([
    'macroProcessus',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $macroProcessus->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.macroProcessus.fields.name') }}
            </th>
            <td>
            @if($withLink ?? false)
                <a href="{{ route('admin.macro-processuses.show', $macroProcessus->id) }}">
                    {{ $macroProcessus->name }}
                </a>
            @else
                {{ $macroProcessus->name }}
            @endif
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
            @if (config('mercator-config.parameters.security_need_auth'))
            &nbsp;
            {{ trans('global.authenticity') }} :
                @if ($macroProcessus->security_need_auth==0){{ trans('global.none') }}@endif
                @if ($macroProcessus->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                @if ($macroProcessus->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                @if ($macroProcessus->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                @if ($macroProcessus->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
            @endif
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
                @foreach($macroProcessus->processes as $process)
                    <a href="{{ route('admin.processes.show', $process->id) }}">
                        {{ $process->name }}
                    </a>
                    @if(!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>



