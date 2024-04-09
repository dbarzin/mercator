@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.logical-flows.update", [$logicalFlow->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.logicalFlow.title_singular') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="recommended" for="name">{{ trans('cruds.logicalFlow.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $logicalFlow->name) }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.name_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="recommended" for="description">{{ trans('cruds.logicalFlow.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $logicalFlow->description) !!}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.description_helper') }}</span>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------>

            <div class="row">
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="recommended" for="name">{{ trans('cruds.logicalFlow.fields.protocol') }}</label>
                        <input class="form-control {{ $errors->has('protocol') ? 'is-invalid' : '' }}" type="text" name="protocol" id="protocol" value="{{ old('protocol', $logicalFlow->protocol) }}" required>
                        @if($errors->has('protocol'))
                            <div class="invalid-feedback">
                                {{ $errors->first('protocol') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.protocol_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.logicalFlow.fields.source_ip_range') }}</label>
                        <input class="form-control {{ $errors->has('protocol') ? 'is-invalid' : '' }}" type="text" name="source_ip_range" id="source_ip_range" value="{{ old('source_ip_range', $logicalFlow->source_ip_range) }}" required>
                        @if($errors->has('source_ip_range'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source_ip_range') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.source_ip_range_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.logicalFlow.fields.source_port') }}</label>
                        <input class="form-control {{ $errors->has('protocol') ? 'is-invalid' : '' }}" type="text" name="source_port" id="source_port" value="{{ old('source_port', $logicalFlow->source_port) }}">
                        @if($errors->has('source_port'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source_port') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.source_port_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.logicalFlow.fields.dest_ip_range') }}</label>
                        <input class="form-control {{ $errors->has('source_ip_range') ? 'is-invalid' : '' }}" type="text" name="dest_ip_range" id="dest_ip_range" value="{{ old('dest_ip_range', $logicalFlow->dest_ip_range) }}" required>
                        @if($errors->has('dest_ip_range'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dest_ip_range') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.dest_ip_range_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.logicalFlow.fields.dest_port') }}</label>
                        <input class="form-control {{ $errors->has('protocol') ? 'is-invalid' : '' }}" type="text" name="dest_port" id="dest_port" value="{{ old('dest_port', $logicalFlow->dest_port) }}">
                        @if($errors->has('dest_port'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source_port') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.dest_port_helper') }}</span>
                    </div>
                </div>


            </div>


        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
      var allEditors = document.querySelectorAll('.ckeditor');
      for (var i = 0; i < allEditors.length; ++i) {
        ClassicEditor.create(
          allEditors[i], {
            extraPlugins: []
          }
        );
      }
    });

    $(document).ready(function() {
      $(".select2-free").select2({
            placeholder: "{{ trans('global.pleaseSelect') }}",
            allowClear: true,
            tags: true
        })
      });
</script>
@endsection
