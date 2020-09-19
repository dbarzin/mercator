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
                        <th>
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
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.process.fields.security_need') }}
                        </th>
                        <td>
                            @if ($process->security_need==1) 
                                Public
                            @elseif ($process->security_need==2)
                                Internal
                            @elseif ($process->security_need==3)
                                Confidential
                            @elseif ($process->security_need==4)
                                Secret
                            @endif                            
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