@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.physicalLink.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.links.update', [$link->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.physicalLink.fields.src') }}</label>
                        <select class="form-control select2 {{ $errors->has('src_id') ? 'is-invalid' : '' }}" name="src_id" id="src_id">
                            @foreach($devices as $id => $name)
                                <option value="{{ $id }}" {{ (old('src_id') ? old('src_id') : $link->src_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('src_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('src_id') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalLink.fields.src_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.physicalLink.fields.src_port') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="src_port" id="src_port" value="{{ old('src_port', $link->src_port) }}">
                        @if($errors->has('src_port'))
                            <div class="invalid-feedback">
                                {{ $errors->first('src_port') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalLink.fields.src_port_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.physicalLink.fields.dest') }}</label>
                        <select class="form-control select2 {{ $errors->has('src_id') ? 'is-invalid' : '' }}" name="dest_id" id="dest_id">
                            @foreach($devices as $id => $name)
                                <option value="{{ $id }}" {{ (old('dest_id') ? old('dest_id') : $link->dest_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('dest_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dest_id') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalLink.fields.dest_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.physicalLink.fields.dest_port') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="dest_port" id="dest_port" value="{{ old('dest_port', $link->dest_port) }}">
                        @if($errors->has('dest_port'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dest_port') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalLink.fields.dest_port_helper') }}</span>
                    </div>
                </div>

            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
      var allEditors = document.querySelectorAll('.ckeditor');
      for (var i = 0; i < allEditors.length; ++i) {
        ClassicEditor.create(
          allEditors[i], {
            extraPlugins: [SimpleUploadAdapter]
          }
        );
      }
    });
</script>

@endsection
