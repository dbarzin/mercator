@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.relations.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=REL_{{$relation->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('entity_edit')
        <a class="btn btn-info" href="{{ route('admin.relations.edit', $relation->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('entity_delete')
        <form action="{{ route('admin.relations.destroy', $relation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan

</div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.relation.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.relation.fields.name') }}
                        </th>
                        <td>
                            {{ $relation->name }}
                        </td>
                        <th width="10%">
                            {{ trans('cruds.relation.fields.type') }}
                        </th>
                        <td>
                            {{ $relation->type }}
                        </td>
                        <th width="10%">
                            {{ trans('cruds.relation.fields.attributes') }}
                        </th>
                        <td>
                            {{ $relation->attributes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.reference') }}
                        </th>
                        <td>
                            {{ $relation->reference }}
                        </td>
                        <th>
                            {{ trans('cruds.relation.fields.order_number') }}
                        </th>
                        <td>
                            {{ $relation->order_number }}
                        </td>
                        <th>
                            {{ trans('cruds.relation.fields.responsible') }}
                        </th>
                        <td>
                            {{ $relation->responsible }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.description') }}
                        </th>
                        <td colspan="5">
                            {!! $relation->description !!}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.source') }}
                        </th>
                        <td>
                            <a href="{{ route('admin.entities.show', $relation->source_id) }}">
                            {{ $relation->source->name ?? '' }}
                            </a>
                        </td>
                        <th>
                            {{ trans('cruds.relation.fields.destination') }}
                        </th>
                        <td>
                            <a href="{{ route('admin.entities.show', $relation->destination_id) }}">
                            {{ $relation->destination->name ?? '' }}
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.start_date') }}
                        </th>
                        <td>
                            {{ $relation->start_date }}
                        </td>
                        <th>
                            {{ trans('cruds.relation.fields.end_date') }}
                        </th>
                        <td>
                            {{ $relation->end_date }}
                        </td>
                        <th>
                            {{ trans('cruds.relation.fields.active') }}
                        </th>
                        <td>
                            {{ $relation->active }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.importance') }}
                        </th>
                        <td>
                            @if ($relation->importance==1)
                                {{ trans('cruds.relation.fields.importance_level.low') }}
                            @elseif ($relation->importance==2)
                                {{ trans('cruds.relation.fields.importance_level.medium') }}
                            @elseif ($relation->importance==3)
                                {{ trans('cruds.relation.fields.importance_level.high') }}
                            @elseif ($relation->importance==4)
                                {{ trans('cruds.relation.fields.importance_level.critical') }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.comments') }}
                        </th>
                        <td colspan="3">
                            {!! $relation->comments !!}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.relation.fields.documents') }}
                        </th>
                        <td colspan="3">
                            @foreach($relation->documents as $document)
                                <a href="{{ route('admin.documents.show', $document->id) }}">{{ $document->filename }}</a>
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $relation->created_at ? $relation->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $relation->updated_at ? $relation->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.relations.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection
