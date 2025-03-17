@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.workstations.store") }}" enctype="multipart/form-data">
    @csrf

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.workstation.title_singular') }}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.workstation.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.name_helper') }}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="type">{{ trans('cruds.workstation.fields.type') }}</label>
                    <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                        @if (!$type_list->contains(old('type')))
                            <option> {{ old('type') }}</option>'
                        @endif
                        @foreach($type_list as $t)
                            <option {{ old('type') == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.name_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <label for="description">{{ trans('cruds.workstation.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.description_helper') }}</span>
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
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        Configuration
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="cpu">{{ trans('cruds.workstation.fields.cpu') }}</label>
                    <select class="form-control select2-free {{ $errors->has('cpu') ? 'is-invalid' : '' }}" name="cpu" id="cpu">
                        @if (!$type_list->contains(old('cpu')))
                            <option> {{ old('cpu') }}</option>'
                        @endif
                        @foreach($cpu_list as $c)
                            <option {{ old('cpu') == $c ? 'selected' : '' }}>{{$c}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('cpu'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cpu') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.cpu_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="memory">{{ trans('cruds.workstation.fields.memory') }}</label>
                    <input class="form-control {{ $errors->has('memory') ? 'is-invalid' : '' }}" type="text" name="memory" id="memory" value="{{ old('memory', '') }}">
                    @if($errors->has('memory'))
                        <div class="invalid-feedback">
                            {{ $errors->first('memory') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.memory_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="disk">{{ trans('cruds.workstation.fields.disk') }}</label>
                    <input class="form-control {{ $errors->has('disk') ? 'is-invalid' : '' }}" type="text" name="disk" id="disk" value="{{ old('disk', '') }}">
                    @if($errors->has('disk'))
                        <div class="invalid-feedback">
                            {{ $errors->first('disk') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.disk_helper') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.application.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-lg">


                <div class="form-group">
                    <label for="applications">{{ trans('cruds.workstation.fields.applications') }}</label>
                    <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                        @foreach($application_list as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('applications', [])) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('applications'))
                        <div class="invalid-feedback">
                            {{ $errors->first('applications') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.applications_helper') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="operating_system">{{ trans('cruds.workstation.fields.operating_system') }}</label>
                    <select class="form-control select2-free {{ $errors->has('operating_system') ? 'is-invalid' : '' }}" name="operating_system" id="operating_system">
                        @if (!$type_list->contains(old('operating_system')))
                            <option> {{ old('operating_system') }}</option>'
                        @endif
                        @foreach($operating_system_list as $os)
                            <option {{ old('operating_system')  == $os ? 'selected' : '' }}>{{$os}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('operating_system'))
                    <div class="invalid-feedback">
                        {{ $errors->first('operating_system') }}
                    </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.operating_system_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="address_ip">{{ trans('cruds.workstation.fields.address_ip') }}</label>
                    <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text" name="address_ip" id="address_ip" value="{{ old('address_ip', '') }}">
                    @if($errors->has('address_ip'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address_ip') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.address_ip_helper') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.physical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="site_id">{{ trans('cruds.workstation.fields.site') }}</label>
                    <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                        @foreach($sites as $id => $site)
                            <option value="{{ $id }}" {{ old('site_id') == $id ? 'selected' : '' }}>{{ $site }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('site'))
                        <div class="invalid-feedback">
                            {{ $errors->first('site') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.site_helper') }}</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="building_id">{{ trans('cruds.workstation.fields.building') }}</label>
                    <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                        @foreach($buildings as $id => $building)
                            <option value="{{ $id }}" {{ old('building_id') == $id ? 'selected' : '' }}>{{ $building }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('building'))
                        <div class="invalid-feedback">
                            {{ $errors->first('building') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workstation.fields.building_helper') }}</span>
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

    // ---------------------------------------------------------------------
    // Initialize imageSelect
	imagesData =
		[
            {
                value: '-1',
                img: '/images/workstation.png',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ old('icon_id') === null ? "true" : "false" }},
            },
            @foreach($icons as $icon)
            {
                value: '{{ $icon }}',
                img: '{{ route('admin.documents.show', $icon) }}',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ old('icon_id') === $icon ? "true" : "false" }},
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
                    // Add image to the select2 options
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
