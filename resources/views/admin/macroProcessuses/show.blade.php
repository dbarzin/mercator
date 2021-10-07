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

                @can('macro_processus_edit')
                    <a class="btn btn-info" href="{{ route('admin.macro-processuses.edit', $macroProcessus->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('macro_processus_delete')
                    <form action="{{ route('admin.macro-processuses.destroy', $macroProcessus->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('global.confidentiality') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$macroProcessus->security_need_c] ?? "" }}
                            <br>
                            {{ trans('global.integrity') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$macroProcessus->security_need_i] ?? "" }}
                            <br>
                            {{ trans('global.availability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$macroProcessus->security_need_a] ?? "" }}
                            <br>
                            {{ trans('global.tracability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$macroProcessus->security_need_t] ?? "" }}                            
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
                                @if (!$loop->last)
                                ,
                                @endif                                                                
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