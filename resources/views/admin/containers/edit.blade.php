@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route('admin.containers.update', [$container->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.container.title_singular') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.container.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $container->name) }}" required maxlength="32">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.container.fields.name_helper') }}</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="type">{{ trans('cruds.container.fields.type') }}</label>
                        <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" maxlength='255'>
                            @if (!$type_list->contains(old('type')))
                                <option> {{ old('type') }}</option>'
                            @endif
                            @foreach($type_list as $t)
                                <option {{ (old('type') ? old('type') : $container->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.container.fields.type_helper') }}</span>
                    </div>
                </div>


            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label class="recommended" for="description">{{ trans('cruds.container.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $container->description) !!}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.container.fields.description_helper') }}</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="iconSelect">{{ trans('global.icon_select') }}</label>
                        <select id="iconSelect" name="iconSelect" class="form-control"></select>
                    </div>
                    <div class="form-group">
                        <input type="file" id="iconFile" name="iconFile" accept="image/png" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="applications">{{ trans('cruds.container.fields.applications') }}</label>
                        <select class="form-control select2 {{ $errors->has('containers') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                            @foreach($applications as $id => $applications)
                                <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $container->applications->contains($id)) ? 'selected' : '' }}>{{ $applications }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('applications'))
                            <div class="invalid-feedback">
                                {{ $errors->first('applications') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.container.fields.applications_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="logical_servers">{{ trans('cruds.container.fields.logical_servers') }}</label>
                        <select class="form-control select2 {{ $errors->has('logical_servers') ? 'is-invalid' : '' }}" name="logical_servers[]" id="logical_servers" multiple>
                            @foreach($logical_servers as $id => $logical_server)
                                <option value="{{ $id }}" {{ (in_array($id, old('logical_servers', [])) || $container->logicalServers->contains($id)) ? 'selected' : '' }}>{{ $logical_server }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('logical_servers'))
                            <div class="invalid-feedback">
                                {{ $errors->first('logical_servers') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.container.fields.logical_servers_helper') }}</span>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.containers.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script src="/js/DynamicSelect.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: []
      }
    );
  }

  $(".select2-free").select2({
        placeholder: "{{ trans('global.pleaseSelect') }}",
        allowClear: true,
        tags: true
    })

    // ---------------------------------------------------------------------
    // Initialize imageSelect
	imagesData =
		[
            {
                value: '-1',
                img: '/images/container.png',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ $container->icon_id === null ? "true" : "false"}},
            },
            @foreach($icons as  $icon)
            {
                value: '{{ $icon }}',
                img: '{{ route('admin.documents.show', $icon) }}',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ $container->icon_id === $icon ? "true" : "false" }},
            },
            @endforeach
        ];

    // Initialize the Dynamic Selects
    dynamicSelect = new DynamicSelect('#iconSelect', {
        columns: 2,
        height: '140px',
        width: '160px',
        dropdownWidth: '300px',
        placeholder: 'Select an icon',
        data: imagesData,
    });

    // Handle file upload and verification
    $('#iconFile').on('change', function(e) {
        const file = e.target.files[0];

        if (file && file.type === 'image/png') {
            const img = new Image();
            img.src = URL.createObjectURL(file);

            img.onload = function() {
                // Check size
                if (img.size > 65535) {
                    alert('Image size must be < 65kb');
                    return;
                    }
                if ((img.width > 255) || (img.height > 255)) {
                    alert('Could not be more than 256x256 pixels.');
                    return;
                }

                // Encode the image in base64
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Add the image to the select2 options
					imagesData.push(
		                {
		                    value: file.name,
		                    img: event.target.result,
		                    imgHeight: '100px',
		                });
					// refresh
					dynamicSelect.refresh(imagesData, file.name);
                };
                reader.readAsDataURL(file);
            }
        } else {
            alert('Select a PNG image.');
        }
    });

});
</script>
@endsection
