@extends('layouts.admin')

@section('content')

<div class="card">

    <div class="card-header">
        {{ trans("cruds.configuration.documents.title") }}
    </div>

        <div class="p-3">
            <div data-role="panel" data-title-caption="{{ trans('cruds.document.list') }}" data-collapsible="true" data-title-icon="<span class='mif-chart-line'></span>">

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('cruds.configuration.documents.name') }}</th>
                <th>{{ trans('cruds.configuration.documents.mimetype') }}</th>
                <th>{{ trans('cruds.configuration.documents.size') }}</th>
                <th>{{ trans('cruds.configuration.documents.hash') }}</th>
                <th>{{ trans('cruds.configuration.documents.status') }}</th>
            </tr>
            </thead>

            @foreach ($documents as $doc)
            <tr>
                <td>
                    {{ $doc->id }}
                </td>
                <td>
                    <a href="{{ route('admin.documents.show',$doc->id) }}">{{ substr($doc->filename,0,64) }}</a>
                </td>
                <td>
                    {{ $doc->mimetype }}
                </td>
                <td>
                    {{ $doc->humanSize() }}
                </td>
                <td>
                    {{ $doc->hash }}
                    <br>
                </td>
                <td>
                    <b>
                    @if (file_exists(storage_path('docs/').$doc->id))
                        @if ($doc->hash == hash_file("sha256", storage_path('docs/').$doc->id))
                            <font color="green">OK</font>
                        @else
                            <font color="red">HASH FAILS</font>
                        @endif
                    @else
                            <font color="red">MISSING</font>
                    @endif
                    </b>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@endsection
