@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.actor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.actors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.actor.fields.id') }}
                        </th>
                        <td>
                            {{ $actor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.actor.fields.name') }}
                        </th>
                        <td>
                            {{ $actor->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.actor.fields.contact') }}
                        </th>
                        <td>
                            {{ $actor->contact }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.actor.fields.nature') }}
                        </th>
                        <td>
                            {{ $actor->nature }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.actor.fields.type') }}
                        </th>
                        <td>
                            {{ $actor->type }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.actors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection