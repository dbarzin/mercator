@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.macroProcessus.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.macro-processuses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
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
                            @if ($macroProcessus->security_need==1) 
                                Public
                            @elseif ($macroProcessus->security_need==2)
                                Internal
                            @elseif ($macroProcessus->security_need==3)
                                Confidential
                            @elseif ($macroProcessus->security_need==4)
                                Secret
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
                            @foreach($macroProcessus->processes as $key => $processes)
                                <span class="label label-info">{{ $processes->identifiant }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.macro-processuses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection