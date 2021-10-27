@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.application.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                @can('application_edit')
                    <a class="btn btn-info" href="{{ route('admin.applications.edit', $application->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('application_delete')
                    <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan

            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.application.fields.name') }}
                        </th>
                        <td>
                            {{ $application->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.description') }}
                        </th>
                        <td>
                            {!! $application->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.entities') }}
                        </th>
                        <td>
                            @foreach($application->entities as $entity)
                                <a href="{{ route('admin.entities.show', $entity->id) }}">{{ $entity->name }}</span>
                                @if(!$loop->last)
                                ,
                                @endif                                
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.entity_resp') }}
                        </th>
                        <td>
                            {{ $application->entity_resp->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.responsible') }}
                        </th>
                        <td>
                            {{ $application->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.technology') }}
                        </th>
                        <td>
                            {{ $application->technology }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.type') }}
                        </th>
                        <td>
                            {{ $application->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.users') }}
                        </th>
                        <td>
                            {{ $application->users }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.security_need') }}
                        </th>
                        <td>
                            {{ trans('global.confidentiality') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_c] ?? "" }}
                            <br>
                            {{ trans('global.integrity') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_i] ?? "" }}
                            <br>
                            {{ trans('global.availability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_a] ?? "" }}
                            <br>
                            {{ trans('global.tracability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_t] ?? "" }} 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.external') }}
                        </th>
                        <td>
                            {{ $application->external }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.processes') }}
                        </th>
                        <td>
                            @foreach($application->processes as $process)
                                <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->identifiant }}</span>
                                @if(!$loop->last)
                                ,
                                @endif                                
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.services') }}
                        </th>
                        <td>
                            @foreach($application->services as $service)
                                <a href="{{ route('admin.application-services.show', $service->id) }}">{{ $service->name }}</span>
                                @if(!$loop->last)
                                ,
                                @endif                                
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.databases') }}
                        </th>
                        <td>
                            @foreach($application->databases as $database)
                                <a href="{{ route('admin.databases.show', $database->id) }}">{{ $database->name }}</span>
                                @if(!$loop->last)
                                ,
                                @endif                                
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.logical_servers') }}
                        </th>
                        <td>
                            @foreach($application->logical_servers as $logical_server)
                                <a href='{{ route("admin.logical-servers.show", $logical_server->id) }}'>{{ $logical_server->name }}</span>
                                @if(!$loop->last)
                                ,
                                @endif                                
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.application_block') }}
                        </th>
                        <td>
                            @if ($application->application_block!=null)
                            <a href='{{ route("admin.application-blocks.show", $application->application_block->id) }}'>{{ $application->application_block->name }}</a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection