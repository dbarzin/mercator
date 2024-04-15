@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.certificates.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=CERT_{{$certificate->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('certificate_edit')
        <a class="btn btn-info" href="{{ route('admin.certificates.edit', $certificate->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('certificate_delete')
        <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.certificate.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.certificate.fields.name') }}
                        </th>
                        <td>
                            {{ $certificate->name }}
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.certificate.fields.type') }}
                        </th>
                        <td>
                            {{ $certificate->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificate.fields.description') }}
                        </th>
                        <td>
                            {!! $certificate->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificate.fields.start_validity') }}
                        </th>
                        <td>
                            {!! $certificate->start_validity !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificate.fields.end_validity') }}
                        </th>
                        <td>
                            {!! $certificate->end_validity !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificate.fields.last_notification') }}
                        </th>
                        <td>
                            {!! $certificate->last_notification !!}
                            <br>
                            {{ trans('cruds.certificate.fields.last_notification_helper') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificate.fields.logical_servers') }}
                        </th>
                        <td>
                            @foreach($certificate->logical_servers as $server)
                                <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                                    {{ $server->name }}
                                </a>
                                @if(!$loop->last)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificate.fields.applications') }}
                        </th>
                        <td>
                            @foreach($certificate->applications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                    {{ $application->name }}
                                </a>
                                @if(!$loop->last)
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
        {{ trans('global.created_at') }} {{ $certificate->created_at ? $certificate->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $certificate->updated_at ? $certificate->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.certificates.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
