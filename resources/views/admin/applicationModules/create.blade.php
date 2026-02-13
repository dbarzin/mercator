@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.application-modules.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.applicationModule.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="label-required" for="name">{{ trans('cruds.applicationModule.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', '') }}" maxlength="64" required autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationModule.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-2"
                           for="description">{{ trans('cruds.applicationModule.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description" id="description">{!! old('description') !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationModule.fields.description_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="buildings">{{ trans('cruds.applicationModule.fields.services') }}</label>
                    <select class="form-control select2 {{ $errors->has('services') ? 'is-invalid' : '' }}"
                            name="services[]" id="services" multiple>
                        @foreach($services as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('services', [])) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('services'))
                        <div class="invalid-feedback">
                            {{ $errors->first('services') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationModule.fields.services_helper') }}</span>
                </div>
            </div>

            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                Common Platform Enumeration (CPE)
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vendor">{{ trans('cruds.application.fields.vendor') }}</label>
                            <select id="vendor-selector" class="form-control vendor-selector" name="vendor">
                                <option>{{ old('vendor', '') }}</option>
                            </select>
                            <span class="help-block">{{ trans('cruds.application.fields.vendor_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product">{{ trans('cruds.application.fields.product') }}</label>
                            <select id="product-selector" class="form-control" name="product">
                                <option>{{ old('product', '') }}</option>
                            </select>
                            @if($errors->has('product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('product') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.product_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="version">{{ trans('cruds.application.fields.version') }}</label>
                            <select id="version-selector" class="form-control" name="version">
                                <option>{{ old('version', '') }}</option>
                            </select>
                            @if($errors->has('version'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('version') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.version_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <br>
                            <button type="button" class="btn btn-info" id="guess"
                                    alt="Guess vendor and product base on application name">Guess
                            </button>
                            <span class="help-block"></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.application-modules.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection

@section('scripts')

    @vite(['resources/js/cpe.js'])
    <script>

    /*****************************************/
    /* CPE Search
    */
    window.cpePart = 'a';

    </script>
@endsection
