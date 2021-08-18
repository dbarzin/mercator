@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.subnetwork.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.subnetworks.update", [$subnetwork->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.subnetwork.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $subnetwork->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subnetwork.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="description">{{ trans('cruds.subnetwork.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $subnetwork->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subnetwork.fields.description_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-sm">
                
                    <div class="form-group">
                        <label for="address">{{ trans('cruds.subnetwork.fields.address') }}</label>
                        <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', $subnetwork->address) }}">
                        @if($errors->has('address'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.subnetwork.fields.address_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="ip_allocation_type">{{ trans('cruds.subnetwork.fields.ip_allocation_type') }}</label>
                        <select class="form-control select2-free {{ $errors->has('ip_allocation_type') ? 'is-invalid' : '' }}" name="ip_allocation_type" id="ip_allocation_type">
                            @if (!$ip_allocation_type_list->contains(old('ip_allocation_type')))
                                <option> {{ old('ip_allocation_type') }}</option>'
                            @endif
                            @foreach($ip_allocation_type_list as $t)
                                <option {{ (old('ip_allocation_type') ? old('ip_allocation_type') : $subnetwork->ip_allocation_type) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('ip_allocation_type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('ip_allocation_type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.subnetwork.fields.ip_allocation_type_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="gateway_id">{{ trans('cruds.subnetwork.fields.gateway') }}</label>
                        <select class="form-control select2 {{ $errors->has('gateway') ? 'is-invalid' : '' }}" name="gateway_id" id="gateway_id">
                            @foreach($gateways as $id => $gateway)
                                <option value="{{ $id }}" {{ ($subnetwork->gateway ? $subnetwork->gateway->id : old('gateway_id')) == $id ? 'selected' : '' }}>{{ $gateway }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('gateway'))
                            <div class="invalid-feedback">
                                {{ $errors->first('gateway') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.subnetwork.fields.gateway_helper') }}</span>
                    </div>

                </div>



                <div class="col-sm">

                    <div class="form-group">
                        <label for="dmz">{{ trans('cruds.subnetwork.fields.dmz') }}</label>
                        <select class="form-control select2-free {{ $errors->has('dmz') ? 'is-invalid' : '' }}" name="dmz" id="dmz">
                            @if (!$wifi_list->contains(old('dmz')))
                                <option> {{ old('responsible_exp') }}</option>'
                            @endif
                            @foreach($dmz_list as $z)
                                <option {{ (old('dmz') ? old('dmz') : $subnetwork->dmz) == $z ? 'selected' : '' }}>{{$z}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('dmz'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dmz') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.subnetwork.fields.wifi_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="wifi">{{ trans('cruds.subnetwork.fields.wifi') }}</label>
                        <select class="form-control select2-free {{ $errors->has('wifi') ? 'is-invalid' : '' }}" name="wifi" id="wifi">
                            @if (!$wifi_list->contains(old('wifi')))
                                <option> {{ old('responsible_exp') }}</option>'
                            @endif
                            @foreach($wifi_list as $w)
                                <option {{ (old('wifi') ? old('wifi') : $subnetwork->wifi) == $w ? 'selected' : '' }}>{{$w}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('wifi'))
                            <div class="invalid-feedback">
                                {{ $errors->first('wifi') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.subnetwork.fields.wifi_helper') }}</span>
                    </div>


                    <div class="form-group">
                        <label for="responsible_exp">{{ trans('cruds.subnetwork.fields.responsible_exp') }}</label>
                        <select class="form-control select2-free {{ $errors->has('responsible_exp') ? 'is-invalid' : '' }}" name="responsible_exp" id="responsible_exp">
                            @if (!$responsible_exp_list->contains(old('responsible_exp')))
                                <option> {{ old('responsible_exp') }}</option>'
                            @endif
                            @foreach($responsible_exp_list as $t)
                                <option {{ (old('responsible_exp') ? old('responsible_exp') : $subnetwork->responsible_exp) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('responsible'))
                            <div class="invalid-feedback">
                                {{ $errors->first('responsible_exp') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.subnetwork.fields.responsible_exp_helper') }}</span>
                    </div>

            </div>
        </div>


            <div class="form-group">
                <label for="connected_subnets_id">{{ trans('cruds.subnetwork.fields.connected_subnets') }}</label>
                <select class="form-control select2 {{ $errors->has('connected_subnets') ? 'is-invalid' : '' }}" name="connected_subnets_id" id="connected_subnets_id">
                    @foreach($connected_subnets as $id => $connected_subnets)
                        <option value="{{ $id }}" {{ ($subnetwork->connected_subnets ? $subnetwork->connected_subnets->id : old('connected_subnets_id')) == $id ? 'selected' : '' }}>{{ $connected_subnets }}</option>
                    @endforeach
                </select>
                @if($errors->has('connected_subnets'))
                    <div class="invalid-feedback">
                        {{ $errors->first('connected_subnets') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subnetwork.fields.connected_subnets_helper') }}</span>
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