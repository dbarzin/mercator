@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.database.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.databases.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=DATABASE_{{$database->id}}">
                    {{ trans('global.explore') }}
                </a>

                @can('database_edit')
                    <a class="btn btn-info" href="{{ route('admin.databases.edit', $database->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('entity_delete')
                    <form action="{{ route('admin.databases.destroy', $database->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.database.fields.name') }}
                        </th>
                        <td>
                            {{ $database->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.description') }}
                        </th>
                        <td>
                            {!! $database->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.entities') }}
                        </th>
                        <td>
                            @foreach($database->entities as $key => $entities)
                                <span class="label label-info">{{ $entities->name }}</span>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.entity_resp') }}
                        </th>
                        <td>
                            {{ $database->entity_resp->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.responsible') }}
                        </th>
                        <td>
                            {{ $database->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.informations') }}
                        </th>
                        <td>
                            @foreach($database->informations as $key => $informations)
                                <span class="label label-info">{{ $informations->name }}</span>
                                @if (!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.type') }}
                        </th>
                        <td>
                            {{ $database->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.security_need') }}
                        </th>
                        <td>
                            {{ trans('global.confidentiality') }} :
                                {{ array(0=>trans('global.none'), 1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$database->security_need_c] ?? "" }}
                            <br>
                            {{ trans('global.integrity') }} :
                                {{ array(0=>trans('global.none'), 1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$database->security_need_i] ?? "" }}
                            <br>
                            {{ trans('global.availability') }} :
                                {{ array(0=>trans('global.none'), 1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$database->security_need_a] ?? "" }}
                            <br>
                            {{ trans('global.tracability') }} :
                                {{ array(0=>trans('global.none'), 1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$database->security_need_t] ?? "" }} 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.external') }}
                        </th>
                        <td>
                            {{ $database->external }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.databases.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $database->created_at ? $database->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $database->updated_at ? $database->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>

@endsection