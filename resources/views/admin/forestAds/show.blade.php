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
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.forestAd.fields.id') }}
                        </th>
                        <td>
                            {{ $forestAd->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
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