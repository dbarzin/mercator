@props([
    'information',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $information->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.information.fields.name') }}
            </th>
            <td>
            @if ($withLink)
            <a href="{{ route('admin.information.show', $information->id) }}">{{ $information->name }}</a>
            @else
            {{ $information->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.description') }}
            </th>
            <td>
                {!! $information->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.owner') }}
            </th>
            <td>
                {{ $information->owner }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.administrator') }}
            </th>
            <td>
                {{ $information->administrator }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.storage') }}
            </th>
            <td>
                {{ $information->storage }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.processes') }}
            </th>
            <td>
                @foreach($information->processes as $process)
                    <a href="{{ route('admin.processes.show', $process->id) }}">
                    {{ $process->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.security_need') }}
                @if (config('mercator-config.parameters.security_need_auth'))
                + {{ trans("global.authenticity_short") }}
                @endif
            </th>
            <td>
            {{ trans('global.confidentiality') }} :
                @if ($information->security_need_c==0){{ trans('global.none') }}@endif
                @if ($information->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                @if ($information->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                @if ($information->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                @if ($information->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
            &nbsp;
            {{ trans('global.integrity') }} :
                @if ($information->security_need_i==0){{ trans('global.none') }}@endif
                @if ($information->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                @if ($information->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                @if ($information->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                @if ($information->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
            &nbsp;
            {{ trans('global.availability') }} :
                @if ($information->security_need_a==0){{ trans('global.none') }}@endif
                @if ($information->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                @if ($information->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                @if ($information->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                @if ($information->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
            &nbsp;
            {{ trans('global.tracability') }} :
                @if ($information->security_need_t==0){{ trans('global.none') }}@endif
                @if ($information->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                @if ($information->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                @if ($information->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                @if ($information->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
            @if (config('mercator-config.parameters.security_need_auth'))
            &nbsp;
            {{ trans('global.authenticity') }} :
                @if ($information->security_need_auth==0){{ trans('global.none') }}@endif
                @if ($information->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                @if ($information->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                @if ($information->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                @if ($information->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.sensitivity') }}
            </th>
            <td>
                {{ $information->sensitivity }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.constraints') }}
            </th>
            <td>
                {!! $information->constraints !!}
            </td>
        </tr>
    </tbody>
</table>
