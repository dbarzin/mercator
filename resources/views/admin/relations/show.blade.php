@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.relation.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.relations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.id') }}
                        </th>
                        <td>
                            {{ $relation->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.name') }}
                        </th>
                        <td>
                            {{ $relation->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.type') }}
                        </th>
                        <td>
                            {{ $relation->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.description') }}
                        </th>
                        <td>
                            {!! $relation->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.inportance') }}
                        </th>
                        <td>
                            @if ($relation->inportance==1)
                                Faible
                            @elseif ($relation->inportance==2)
                                Moyen
                            @elseif ($relation->inportance==3)
                                Fort
                            @elseif ($relation->inportance==4)
                                Critique
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.source') }}
                        </th>
                        <td>
                            {{ $relation->source->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.destination') }}
                        </th>
                        <td>
                            {{ $relation->destination->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.relations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection