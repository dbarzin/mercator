@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.domaineAd.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.domaine-ads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.domaineAd.fields.name') }}
                        </th>
                        <td>
                            {{ $domaineAd->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.domaineAd.fields.description') }}
                        </th>
                        <td>
                            {!! $domaineAd->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.domaineAd.fields.domain_ctrl_cnt') }}
                        </th>
                        <td>
                            {{ $domaineAd->domain_ctrl_cnt }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.domaineAd.fields.user_count') }}
                        </th>
                        <td>
                            {{ $domaineAd->user_count }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.domaineAd.fields.machine_count') }}
                        </th>
                        <td>
                            {{ $domaineAd->machine_count }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.domaineAd.fields.relation_inter_domaine') }}
                        </th>
                        <td>
                            {{ $domaineAd->relation_inter_domaine }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.forestAd.title') }}
                        </th>
                        <td>
                            @foreach($domaineAd->domainesForestAds as $key => $forestAd)
                                <span class="label label-info">{{ $forestAd->name }}</span>
                            @endforeach                            
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.domaine-ads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>


@endsection