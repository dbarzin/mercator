@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.man.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @can('man_edit')
                    <a class="btn btn-info" href="{{ route('admin.mans.edit', $man->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('man_delete')
                    <form action="{{ route('admin.mans.destroy', $man->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan

            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.man.fields.name') }}
                        </th>
                        <td>
                            {{ $man->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.man.fields.lans') }}
                        </th>
                        <td>
                            @foreach($man->lans as $key => $lans)
                                <span class="label label-info">{{ $lans->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $man->created_at->format(trans('global.timestamp')) }} |
        {{ trans('global.updated_at') }} {{ $man->updated_at->format(trans('global.timestamp')) }} 
    </div>
</div>
@endsection