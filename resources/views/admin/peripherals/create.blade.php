@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.peripherals.store") }}" enctype="multipart/form-data">
    @csrf

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.peripheral.title_singular') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="required" for="name">{{ trans('cruds.peripheral.fields.name') }}</label>
            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
            @if($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.peripheral.fields.name_helper') }}</span>
        </div>

        <div class="form-group">
            <label for="description">{{ trans('cruds.peripheral.fields.description') }}</label>
            <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
            @if($errors->has('description'))
                <div class="invalid-feedback">
                    {{ $errors->first('description') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.peripheral.fields.description_helper') }}</span>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="domain">{{ trans('cruds.peripheral.fields.domain') }}</label>
                    <select class="form-control select2-free {{ $errors->has('domain') ? 'is-invalid' : '' }}" name="domain" id="domain">

                        @if (!$domain_list->contains(old('domain')))
                        <option> {{ old('domain') }}</option>'
                        @endif
                        @foreach($domain_list as $domain)
                        <option {{ old('domain') == $domain ? 'selected' : '' }}>{{$domain}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('domain'))
                        <div class="invalid-feedback">
                            {{ $errors->first('domain') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.peripheral.fields.domain_helper') }}</span>
                </div>
            </div>

            <div class="col-md-10">

                <div class="form-group">
                    <label for="type">{{ trans('cruds.peripheral.fields.type') }}</label>
                    <select class="form-control select2-free {{ $errors->has('domain') ? 'is-invalid' : '' }}" name="type" id="type">
                        @if (!$type_list->contains(old('type')))
                        <option> {{ old('type') }}</option>'
                        @endif
                        @foreach($type_list as $type)
                        <option {{ old('type') == $type ? 'selected' : '' }}>{{$type}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.peripheral.fields.type_helper') }}</span>
                </div>
            </div>
        </div>

    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.ecosystem.title_short") }}
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="recommended" for="provider_id">{{ trans('cruds.peripheral.fields.provider') }}</label>
                    <select class="form-control select2 {{ $errors->has('provider') ? 'is-invalid' : '' }}" name="provider_id" id="provider_id">
                        <option></option>
                        @foreach($entities as $id => $name)
                        <option value="{{ $id }}" {{ old('provider_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('provider'))
                    <div class="invalid-feedback">
                        {{ $errors->first('provider') }}
                    </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.peripheral.fields.provider_helper') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="responsible">{{ trans('cruds.peripheral.fields.responsible') }}</label>
                    <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsible" id="responsible">
                        @if (!$type_list->contains(old('responsible')))
                        <option> {{ old('responsible') }}</option>'
                        @endif
                        @foreach($responsible_list as $responsible)
                        <option {{ old('responsible') == $responsible ? 'selected' : '' }}>{{$responsible}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.peripheral.fields.responsible_helper') }}</span>
                </div>
            </div>

        </div>

    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.application.title_short") }}
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">

        <div class="form-group">
            <label for="applications">{{ trans('cruds.peripheral.fields.applications') }}</label>
            <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                @foreach($applications as $id => $name)
                    <option value="{{ $id }}" {{ in_array($id, old('applications', [])) ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @if($errors->has('applications'))
                <div class="invalid-feedback">
                    {{ $errors->first('applications') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.peripheral.fields.applications_helper') }}</span>
        </div>
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        Common Plateforme Enumeration (CPE)
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="vendor">{{ trans('cruds.peripheral.fields.vendor') }}</label>
                        <div class="form-group">
                            <select id="vendor-selector" class="form-control select2-free" name="vendor">
                                <option>{{ old('vendor', '') }}</option>
                            </select>
                            <span class="help-block">{{ trans('cruds.peripheral.fields.vendor_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product">{{ trans('cruds.peripheral.fields.product') }}</label>
                        <select id="product-selector" class="form-control select2-free" name="product">
                            <option>{{ old('product', '') }}</option>
                        </select>
                        @if($errors->has('product'))
                            <div class="invalid-feedback">
                                {{ $errors->first('product') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.peripheral.fields.product_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="version">{{ trans('cruds.peripheral.fields.version') }}</label>
                        <select id="version-selector" class="form-control select2-free" name="version">
                            <option>{{ old('version', '') }}</option>
                        </select>
                        @if($errors->has('version'))
                            <div class="invalid-feedback">
                                {{ $errors->first('version') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.peripheral.fields.version_helper') }}</span>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <br>
                        <button type="button" class="btn btn-info" id="guess" alt="Guess vendor and product base on application name">Guess</button>
                        <span class="help-block"></span>
                    </div>
                </div>

            </div>
        </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.physical_infrastructure.title_short") }}
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="site_id">{{ trans('cruds.peripheral.fields.site') }}</label>
                    <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                        <option></option>
                        @foreach($sites as $id => $site)
                            <option value="{{ $id }}" {{ old('site_id') == $id ? 'selected' : '' }}>{{ $site }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('site'))
                        <div class="invalid-feedback">
                            {{ $errors->first('site') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.peripheral.fields.site_helper') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="building_id">{{ trans('cruds.peripheral.fields.building') }}</label>
                    <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                        <option></option>
                        @foreach($buildings as $id => $building)
                            <option value="{{ $id }}" {{ old('building_id') == $id ? 'selected' : '' }}>{{ $building }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('building'))
                        <div class="invalid-feedback">
                            {{ $errors->first('building') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.peripheral.fields.building_helper') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="bay_id">{{ trans('cruds.peripheral.fields.bay') }}</label>
                    <select class="form-control select2 {{ $errors->has('bay') ? 'is-invalid' : '' }}" name="bay_id" id="bay_id">
                        <option></option>
                        @foreach($bays as $id => $bay)
                            <option value="{{ $id }}" {{ old('bay_id') == $id ? 'selected' : '' }}>{{ $bay }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('bay'))
                        <div class="invalid-feedback">
                            {{ $errors->first('bay') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.peripheral.fields.bay_helper') }}</span>
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

  $(".select2-free").select2({
        placeholder: "{{ trans('global.pleaseSelect') }}",
        allowClear: true,
        tags: true
    })

    // CPE
    // ------------------------------------------------
    $('#vendor-selector').select2({
      placeholder: 'Start typing to search',
      tags: true,
      ajax: {
        url: '/admin/cpe/search/vendors',
        data: function(params) {
          var query = {
            part: "a",
            search: params.term,
          };
          return query;
        },
        processResults: function(data) {
          var results = [];
          if (data.length) {
            $.each(data, function(id, vendor) {
              results.push({
                id: vendor.name,
                text: vendor.name
              });
            });
          }

          return {
            results: results
          };
        }
      }
    });

    // ------------------------------------------------
    $('#product-selector').select2({
      placeholder: 'Start typing to search',
      tags: true,
      ajax: {
        url: '/admin/cpe/search/products',
        data: function(params) {
          var query = {
            part: "a",
            vendor: $("#vendor-selector").val(),
            search: params.term,
          };
          return query;
        },
        processResults: function(data) {
          var results = [];
          if (data.length) {
            $.each(data, function(id, product) {
              results.push({
                id: product.name,
                text: product.name
              });
            });
          }
          return {
            results: results
          };
        }
      }
    });

    // ------------------------------------------------
    $('#version-selector').select2({
      placeholder: 'Start typing to search',
      tags: true,
      ajax: {
        url: '/admin/cpe/search/versions',
        data: function(params) {
          var query = {
            part: "a",
            vendor: $("#vendor-selector").val(),
            product: $("#product-selector").val(),
            search: params.term,
          };
          return query;
        },
        processResults: function(data) {
          var results = [];
          if (data.length) {
            $.each(data, function(id, version) {
              results.push({
                id: version.name,
                text: version.name
              });
            });
          }
          return {
            results: results
          };
        }
      }

      });

    // CPE Guesser
    // ===========
    function generateCPEList(data) {
        let ret = '<div style="max-height: 300px; overflow-y: scroll;">';
        ret += '<table class="table compact">'
        ret += '<thead><tr><th>Vendor</th><th>Product</th><th></th></tr></thead>';
        data.forEach (function(element) {
            ret += '<tr>';
            ret += '<td>' + element.vendor_name + '</td>';
            ret += '<td>' + element.product_name +'</td>';
            ret += '<td>' + '<a class="select_cpe" data-vendor="'+element.vendor_name+'" data-product="'+element.product_name+'" href="#"> <i class="fa fa-check" style="color:green"></i></a>'
            ret += '</td>';
            ret += '</tr>';
        });
        ret += '</table></div>';
        return ret;
    }

    // CPE Guesser window
    $('#guess').click(function (event) {
        let name = $("#name").val();
        $.get("/admin/cpe/search/guess?search="+encodeURIComponent(name))
        .then((result)=>
            Swal.fire({
                title: "Matching",
                html: generateCPEList(result),
                didOpen(popup) {
                    $('.select_cpe').on('click', function(e) {
                        e.preventDefault();
                        let vendor = $(this).data('vendor');
                        $("#vendor-selector").append('<option>'+vendor+'</option>');
                        $("#vendor-selector").val(vendor);
                        let product = $(this).data('product');
                        $("#product-selector").append('<option>'+product+'</option>');
                        $("#product-selector").val(product);
                        $("#version-selector").append('<option></option>');
                        $("#version-selector").val(null);
                        swal.close();
                    })
                },
                showConfirmButton: false,
                showCancelButton: true,
                customClass: {
                    container:   {
                        'max-height': "6em",
                        'overflow-y': 'scroll',
                        'width': '100%',
                    }
                }
            }));
        });

    // submit the correct button when "enter" key pressed
        $("form input").keypress(function (e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $('input[type=submit].default').click();
            return false;
        }
        else {
            return true;
        }
    });

});
</script>
@endsection
