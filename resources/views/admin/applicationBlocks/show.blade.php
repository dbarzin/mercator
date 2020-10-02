@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationBlock.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-blocks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationBlock.fields.id') }}
                        </th>
                        <td>
                            {{ $applicationBlock->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationBlock.fields.name') }}
                        </th>
                        <td>
                            {{ $applicationBlock->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationBlock.fields.description') }}
                        </th>
                        <td>
                            {!! $applicationBlock->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationBlock.fields.responsible') }}
                        </th>
                        <td>
                            {{ $applicationBlock->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Applications
                        </th>
                        <td>
                            @foreach($applicationBlock->applications as $key => $application)
                                <span class="label label-info">{{ $application->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-blocks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>


@endsection