@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=APP_{{$application->id}}">
                {{ trans('global.explore') }}
            </a>
            @if(auth()->user()->can('m_application_edit'))
                <a class="btn btn-info" href="{{ route('admin.applications.edit', $application->id) }}">
                    {{ trans('global.edit') }}
                </a>
            @endif
            @if(auth()->user()->can('m_application_delete'))
                <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST"
                      onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans('cruds.application.title_singular') }}
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        <dt>{{ trans('cruds.application.fields.name') }}</dt>
                    </th>
                    <td>
                        {{ $application->name }}
                    </td>
                    <th width="10%">
                        <dt>{{ trans('cruds.application.fields.application_block') }}</dt>
                    </th>
                    <td width="10%">
                        @if ($application->applicationBlock!=null)
                            <a href='{{ route("admin.application-blocks.show", $application->applicationBlock->id) }}'>{{ $application->applicationBlock->name }}</a>
                        @endif
                    </td>
                    <th width="10%">
                        <dt>{{ trans('cruds.application.fields.attributes') }}</dt>
                    </th>
                    <td width="10%">
                        {{ $application->attributes }}
                    </td>
                </tr>
                <tr>
                    <th>
                        <dt>{{ trans('cruds.application.fields.description') }}</dt>
                    </th>
                    <td colspan="4">
                        {!! $application->description !!}
                    </td>
                    <td width="10%">
                        @if ($application->icon_id === null)
                            <img src='/images/application.png' width='120' height='120'>
                        @else
                            <img src='{{ route('admin.documents.show', $application->icon_id) }}' width='100'
                                 height='100'>
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.menu.ecosystem.title_short") }}
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.responsible') }}
                    </th>
                    <td>
                        {{ $application->responsible }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.application.fields.entity_resp') }}
                    </th>
                    <td>
                        @if ($application->entityResp!=null)
                            <a href="{{ route('admin.entities.show', $application->entity_resp_id) }}">
                                {{ $application->entityResp->name ?? '' }}
                            </a>
                        @endif
                    </td>
                    <th width="10%">
                        {{ trans('cruds.application.fields.entities') }}
                    </th>
                    <td>
                        @foreach($application->entities as $entity)
                            <a href="{{ route('admin.entities.show', $entity->id) }}">{{ $entity->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th width="10%">
                        <dt>{{ trans('cruds.application.fields.functional_referent') }}</dt>
                    </th>
                    <td width="15%">
                        {{ $application->functional_referent }}
                    </td>
                    <th width="10%">
                        <dt>{{ trans('cruds.application.fields.editor') }}</dt>
                    </th>
                    <td width="15%">
                        {{ $application->editor }}
                    </td>
                    <th width="10%">
                        <dt>{{ trans('cruds.application.fields.users') }}</dt>
                    </th>
                    <td width="15%">
                        {{ $application->users }}
                    </td>
                </tr>
                <tr>
                    <th>
                        <dt>{{ trans('cruds.application.fields.cartographers') }}</dt>
                    </th>
                    <td>
                        @foreach($application->cartographers as $cartographer)
                            {{ $cartographer->name }}
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                    <th>
                        <dt>{{ trans('cruds.application.fields.administrators') }}</dt>
                    </th>
                    <td>
                        @foreach($application->administrators as $administrator)
                            <a href="{{ route('admin.admin-users.show', $administrator->id) }}">{{ $administrator->user_id }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tbody>
            </table>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.menu.application.title_short") }}
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.technology') }}
                    </th>
                    <td width="20%">
                        {{ $application->technology }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.application.fields.type') }}
                    </th>
                    <td width="20%">
                        {{ $application->type }}
                    </td>
                    <th width="10%">
                        <dt>{{ trans('cruds.application.fields.external') }}</dt>
                    <td width="20%">
                        {{ $application->external }}
                    </td>
                </tr>
                <tr>
                    <th>
                        <dt>{{ trans('cruds.application.fields.install_date') }}</dt>
                    </th>
                    <td>
                        {{ $application->install_date }}
                    </td>
                    <th>
                        <dt>{{ trans('cruds.application.fields.update_date') }}</dt>
                    </th>
                    <td>
                        {{ $application->update_date }}
                    </td>
                </tr>
                <tr>
                    <th>
                        <dt>{{ trans('cruds.application.fields.events') }}</dt>
                    </th>
                    <td>
                        <button class="btn btn-info events_list_button">
                            {{ trans('cruds.application.fields.events_list_button') }}
                        </button>
                    </td>
                </tr>
                <tr>
                    <th>
                        <dt>{{ trans('cruds.application.fields.documentation') }}</dt>
                    </th>
                    <td colspan="5">
                        @if (filter_var($application->documentation, FILTER_VALIDATE_URL))
                            <a href="{{ $application->documentation }}">{{ $application->documentation }}</a>
                        @else
                            {{ $application->documentation }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        <dt>{{ trans('cruds.application.fields.databases') }}</dt>
                    </th>
                    <td colspan="2">
                        @foreach($application->databases as $database)
                            <a href="{{ route('admin.databases.show', $database->id) }}">{{ $database->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                    <th>
                        <dt>{{ trans('cruds.application.fields.services') }}</dt>
                    </th>
                    <td colspan="2">
                        @foreach($application->services as $service)
                            <a href="{{ route('admin.application-services.show', $service->id) }}">{{ $service->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tbody>
            </table>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            Sécurité
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="60%">
                        <dt>{{ trans('cruds.application.fields.security_need') }}</dt>
                    </th>
                    <th width="20%">
                        <dt>{{ trans('cruds.application.fields.RTO') }}</dt>
                    </th>
                    <th width="20%">
                        <dt>{{ trans('cruds.application.fields.RPO') }}</dt>
                    </th>
                </tr>
                <tr>
                    <td>
                        <table class="table table-striped table-borderless" cellspacing="5" cellpadding="5" border="0">
                            <tbody>
                            <td align="right" valign="middle">
                                {{ trans("global.confidentiality") }}
                            </td>
                            <td align="left">
                                @if ($application->security_need_c==0)
                                    {{ trans('global.none') }}
                                @endif
                                @if ($application->security_need_c==1)
                                    <span class="veryLowRisk">{{ trans('global.low') }}</span>
                                @endif
                                @if ($application->security_need_c==2)
                                    <span class="lowRisk">{{ trans('global.medium') }}</span>
                                @endif
                                @if ($application->security_need_c==3)
                                    <span class="mediumRisk">{{ trans('global.strong') }}</span>
                                @endif
                                @if ($application->security_need_c==4)
                                    <span class="highRisk">{{ trans('global.very_strong') }}</span>
                                @endif
                            </td>
                            <td align="right" valign="middle">
                                {{ trans("global.integrity") }}
                            </td>
                            <td align="left">
                                @if ($application->security_need_i==0)
                                    {{ trans('global.none') }}
                                @endif
                                @if ($application->security_need_i==1)
                                    <span class="veryLowRisk">{{ trans('global.low') }}</span>
                                @endif
                                @if ($application->security_need_i==2)
                                    <span class="lowRisk">{{ trans('global.medium') }}</span>
                                @endif
                                @if ($application->security_need_i==3)
                                    <span class="mediumRisk">{{ trans('global.strong') }}</span>
                                @endif
                                @if ($application->security_need_i==4)
                                    <span class="highRisk">{{ trans('global.very_strong') }}</span>
                                @endif
                            </td>
                            <td align="right" valign="middle">
                                {{ trans('global.availability') }}
                            </td>
                            <td align="left">
                                @if ($application->security_need_a==0)
                                    {{ trans('global.none') }}
                                @endif
                                @if ($application->security_need_a==1)
                                    <span class="veryLowRisk">{{ trans('global.low') }}</span>
                                @endif
                                @if ($application->security_need_a==2)
                                    <span class="lowRisk">{{ trans('global.medium') }}</span>
                                @endif
                                @if ($application->security_need_a==3)
                                    <span class="mediumRisk">{{ trans('global.strong') }}</span>
                                @endif
                                @if ($application->security_need_a==4)
                                    <span class="highRisk">{{ trans('global.very_strong') }}</span>
                                @endif
                            </td>
                            <td align="right" valign="middle">
                                {{ trans('global.tracability') }}
                            </td>
                            <td align="left">
                                @if ($application->security_need_t==0)
                                    {{ trans('global.none') }}
                                @endif
                                @if ($application->security_need_t==1)
                                    <span class="veryLowRisk">{{ trans('global.low') }}</span>
                                @endif
                                @if ($application->security_need_t==2)
                                    <span class="lowRisk">{{ trans('global.medium') }}</span>
                                @endif
                                @if ($application->security_need_t==3)
                                    <span class="mediumRisk">{{ trans('global.strong') }}</span>
                                @endif
                                @if ($application->security_need_t==4)
                                    <span class="highRisk">{{ trans('global.very_strong') }}</span>
                                @endif
                            </td>
                            @if (config('mercator-config.parameters.security_need_auth'))
                                <td align="right" valign="middle">
                                    {{ trans('global.authenticity') }}
                                </td>
                                <td align="left">
                                    @if ($application->security_need_auth==0)
                                        {{ trans('global.none') }}
                                    @endif
                                    @if ($application->security_need_auth==1)
                                        <span class="veryLowRisk">{{ trans('global.low') }}</span>
                                    @endif
                                    @if ($application->security_need_auth==2)
                                        <span class="lowRisk">{{ trans('global.medium') }}</span>
                                    @endif
                                    @if ($application->security_need_auth==3)
                                        <span class="mediumRisk">{{ trans('global.strong') }}</span>
                                    @endif
                                    @if ($application->security_need_auth==4)
                                        <span class="highRisk">{{ trans('global.very_strong') }}</span>
                                    @endif
                                </td>
                            @endif
                            </tbody>
                        </table>
                    </td>
                    <td>
                        @if (intdiv($application->rto,60 * 24) > 0)
                            {{ intdiv($application->rto,60 * 24) }}
                            @if (intdiv($application->rto,60 * 24) > 1)
                                {{ trans('global.days') }}
                            @else
                                {{ trans('global.day') }}
                            @endif
                        @endif
                        @if ((intdiv($application->rto,60) % 24) > 0)
                            {{ intdiv($application->rto,60) % 24 }}
                            @if ((intdiv($application->rto,60) % 24) > 1)
                                {{ trans('global.hours') }}
                            @else
                                {{ trans('global.hour') }}
                            @endif
                        @endif
                        @if (($application->rto % 60) > 0)
                            {{ $application->rto % 60 }}
                            @if (($application->rto % 60) > 1)
                                {{ trans('global.minutes') }}
                            @else
                                {{ trans('global.minute') }}
                            @endif
                        @endif
                    </td>
                    <td>
                        @if (intdiv($application->rpo,60 * 24) > 0)
                            {{ intdiv($application->rpo,60 * 24) }}
                            @if (intdiv($application->rpo,60 * 24) > 1)
                                {{ trans('global.days') }}
                            @else
                                {{ trans('global.day') }}
                            @endif
                        @endif
                        @if ((intdiv($application->rpo,60) % 24) > 0)
                            {{ intdiv($application->rpo,60) % 24 }}
                            @if ((intdiv($application->rpo,60) % 24) > 1)
                                {{ trans('global.hours') }}
                            @else
                                {{ trans('global.hour') }}
                            @endif
                        @endif
                        @if (($application->rpo % 60) > 0)
                            {{ $application->rpo % 60 }}
                            @if (($application->rpo % 60) > 1)
                                {{ trans('global.minutes') }}
                            @else
                                {{ trans('global.minute') }}
                            @endif
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            Common Platform Enumeration (CPE)
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.vendor') }}
                    </th>
                    <td width="22%">
                        {{ $application->vendor }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.application.fields.product') }}
                    </th>
                    <td width="22%">
                        {{ $application->product }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.application.fields.version') }}
                    </th>
                    <td width="22%">
                        {{ $application->version }}
                    </td>
                    <td>
                        <form action="{{ route('admin.cve.search','cpe:2.3:a:'. $application->vendor.':'. $application->product . ':' . $application->version) }}"
                              method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="submit" class="btn btn-info"
                                   value="{{ trans('global.search') }}" {{ (($application->vendor==null)||($application->product==null)) ? 'disabled' : '' }} />
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.menu.metier.title_short") }}
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.processes') }}
                    </th>
                    <td>
                        @foreach($application->processes as $process)
                            <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.activities') }}
                    </th>
                    <td>
                        @foreach($application->activities as $activity)
                            <a href="{{ route('admin.activities.show', $activity->id) }}">{{ $activity->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.menu.logical_infrastructure.title_short") }}
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.logical_servers') }}
                    </th>
                    <td>
                        @foreach($application->logicalServers as $logical_server)
                            <a href='{{ route("admin.logical-servers.show", $logical_server->id) }}'>{{ $logical_server->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.security_devices') }}
                    </th>
                    <td>
                        @foreach($application->securityDevices as $security_device)
                            <a href='{{ route("admin.security-devices.show", $security_device->id) }}'>{{ $security_device->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $application->created_at ? $application->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $application->updated_at ? $application->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>

    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.applications.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Variable contenant la liste des évènements affichés sur la popup
            var swalHtml = @json($application->events);

            /**
             * Contruction de la liste des évènements
             * @returns {string}
             */
            function makeHtmlForSwalEvents() {
                let events = swalHtml;
                let ret = '<ul>';
                events.forEach(function (event) {
                    ret += '<li data-id="' + event.id + '" style="text-align: left; margin-bottom: 20px;">' + event.message + '</br>';
                    ret += '<span style="font-size: 12px;">Date : ' + moment(event.created_at).format('DD-MM-YYYY') + ' | Utilisateur : ' + event.user.name + '</span>';
                });
                ret += '</ul>';
                return ret;
            }

            /**
             * Fire the popup
             */
            $('.events_list_button').click(function (e) {
                e.preventDefault()
                Swal.fire({
                    title: 'Évènements',
                    icon: 'info',
                    html: makeHtmlForSwalEvents(),
                    showCloseButton: true
                });
            });
        });
    </script>
@endsection
