@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.forestAd.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.forest-ads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                @can('entity_edit')
                    <a class="btn btn-info" href="{{ route('admin.forest-ads.edit', $forestAd->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('entity_delete')
                    <form action="{{ route('admin.forest-ads.destroy', $forestAd->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.forestAd.fields.name') }}
                        </th>
                        <td>
                            {{ $forestAd->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.forestAd.fields.description') }}
                        </th>
                        <td>
                            {!! $forestAd->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.forestAd.fields.zone_admin') }}
                        </th>
                        <td>
                            {{ $forestAd->zone_admin->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.forestAd.fields.domaines') }}
                        </th>
                        <td>
                            @foreach($forestAd->domaines as $key => $domaines)
                                <span class="label label-info">{{ $domaines->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.forest-ads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection