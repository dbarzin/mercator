@extends('layouts.admin')
@section('content')
@can('entity_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.entities.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.entity.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.entity.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.entity_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.is_external') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.contact_point') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.relations') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entities as $key => $entity)
                        <tr data-entry-id="{{ $entity->id }}"
                            @if(($entity->description==null)||
                                ($entity->contact_point==null)||
                                ($entity->security_level==null)||
                                ($entity->contact_point==null)||
                                ($entity->processes->count()==0)
                                )
                                class="table-warning"
                            @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.entities.show', $entity->id) }}">
                                {{ $entity->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $entity->entity_type }}
                            </td>
                            <td>
                                {!!  $entity->is_external  == null ? '' : trans('global.'.($entity->is_external ? 'yes' : 'no'))  !!}
                            </td>
                            <td>
                                {!! $entity->contact_point  ?? '' !!}
                            </td>
                            <td>
                                @foreach ($entity->destinationRelations as $relation)
                                    <a href="/admin/relations/{{ $relation->id }}">{{ $relation->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('entity_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.entities.show', $entity->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('entity_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.entities.edit', $entity->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('entity_delete')
                                    <form action="{{ route('admin.entities.destroy', $entity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.entity.title_singular"),
    'URL' => route('admin.entities.massDestroy'),
    'canDelete' => auth()->user()->can('entity_delete') ? true : false
));
</script>
@endsection
