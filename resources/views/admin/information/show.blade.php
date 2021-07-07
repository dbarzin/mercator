@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.information.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.information.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.information.fields.name') }}
                        </th>
                        <td>
                            {{ $information->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.information.fields.descrition') }}
                        </th>
                        <td>
                            {!! $information->descrition !!}
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
                            {{ trans('cruds.information.fields.process') }}
                        </th>
                        <td>
                            @foreach($information->processes as $key => $process)
                                <span class="label label-info">{{ $process->identifiant }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.information.fields.security_need') }}
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
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.information.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#informations_databases" role="tab" data-toggle="tab">
                {{ trans('cruds.database.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="informations_databases">
            @includeIf('admin.information.relationships.informationsDatabases', ['databases' => $information->informationsDatabases])
        </div>
    </div>
</div>

@endsection