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
                            @foreach($process->activities as $key => $activities)
                                <span class="label label-info">{{ $activities->name }}</span>
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
                            @foreach($process->entities as $key => $entities)
                                <span class="label label-info">{{ $entities->name }}</span>
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
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$process->security_need_c] ?? "" }}
                            <br>
                            {{ trans('global.integrity') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$process->security_need_i] ?? "" }}
                            <br>
                            {{ trans('global.availability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$process->security_need_a] ?? "" }}
                            <br>
                            {{ trans('global.tracability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
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
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.processes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>


@endsection