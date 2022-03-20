@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.process.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.processes.index') }}">
                    {{ trans('global.back_to_list') }}
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
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.process.fields.identifiant') }}
                        </th>
                        <td>
                            {{ $process->identifiant }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.description') }}
                        </th>
                        <td>
                            {!! $process->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.in_out') }}
                        </th>
                        <td>
                            {!! $process->in_out !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.activities') }}
                        </th>
                        <td>
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
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.entities') }}
                        </th>
                        <td>
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
                            {{ trans('cruds.process.fields.security_need') }}
                        </th>
                        <td>
                            {{ trans('global.confidentiality') }} :
                                {{ array(0=>trans('global.none'),1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$process->security_need_c] ?? "" }}
                            <br>
                            {{ trans('global.integrity') }} :
                                {{ array(0=>trans('global.none'),1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$process->security_need_i] ?? "" }}
                            <br>
                            {{ trans('global.availability') }} :
                                {{ array(0=>trans('global.none'),1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$process->security_need_a] ?? "" }}
                            <br>
                            {{ trans('global.tracability') }} :
                                {{ array(0=>trans('global.none'),1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$process->security_need_t] ?? "" }}                            
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.owner') }}
                        </th>
                        <td>
                            {{ $process->owner }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.macroprocessus') }}
                        </th>
                        <td>
                            @if($process->macroProcess!=null)
                                <a href="{{ route('admin.macro-processuses.show', $process->macroProcess->id) }}">
                                    {{ $process->macroProcess->name }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.processes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $process->created_at->format(trans('global.timestamp')) ?? '' }} |
        {{ trans('global.updated_at') }} {{ $process->updated_at->format(trans('global.timestamp')) ?? '' }} 
    </div>
</div>
@endsection