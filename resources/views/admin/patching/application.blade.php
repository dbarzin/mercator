@extends('layouts.admin')
@section('content')

<form method="POST" action='{{ route("admin.patching.application") }}' enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <input type='hidden' name="id" value='{{$application->id}}'/>

    <!---------------------------------------------------------------------------------------------------->
    <div class="row">
        <div class="col-sm">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.patching.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                &nbsp;
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </div>
    </div>

    <div class="card">
    <!---------------------------------------------------------------------------------------------------->
        <div class="card-header">
        {{ trans('panel.menu.patching') }}
        </div>
    <!---------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.application.fields.name') }}
                        </th>
                        <td>
                            {{ $application->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.description') }}
                        </th>
                        <td>
                            {!! $application->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-header">
            Patching
        </div>
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="recommended" for="attributes">{{ trans('cruds.application.fields.attributes') }}</label>
                        <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" name="attributes[]" id="attributes[]" multiple>
                            @foreach($attributes_list as $a)
                                <option {{ str_contains(old('attributes') ? old('attributes') : $application->attributes, $a) ? 'selected' : '' }}>{{$a}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('attributes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('attributes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.attributes_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <table width="100%">
                        <tr>
                            <td width="90%">
                                <div class="form-group">
                                    <label for="update_date">{{ trans('cruds.application.fields.update_date') }}</label>
                                    <input class="form-control datepicker" type="text" id="update_date" name="update_date"
                                    value="{{ old('update_date', $application->update_date) }}"
                                    >
                                    <span class="help-block">{{ trans('cruds.application.fields.update_date_helper') }}</span>
                                </div>
                            </td>
                            <td>
                                <a href='' class="nav-link" id="clock">
                                    <i class=" nav-icon fas fa-clock">
                                    </i>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="update_date">
                            <div class="row">
                                <div class="col-sm">
                                    Periodicité
                                </div>
                                <div class="col-lg">
                                    &nbsp; &nbsp;
                                    <input class="form-check-input" type="checkbox" name="global_periodicity" />
                                    <label class="form-check-label" >
                                        Global
                                    </label>
                                </div>
                            </div>
                        </label>
                        <select class="form-control select2" name="patching_frequency" id="patching_frequency">
                            <option vlaue="0"></option>
                            <option value="1" {{ ($application->patching_frequency===1) ? "selected" : ""}}>1 month</option>
                            <option value="2" {{ ($application->patching_frequency===2) ? "selected" : ""}}>2 months</option>
                            <option value="3" {{ ($application->patching_frequency===3) ? "selected" : ""}}>3 months</option>
                            <option value="4" {{ ($application->patching_frequency===4) ? "selected" : ""}}>4 months</option>
                            <option value="6" {{ ($application->patching_frequency===6) ? "selected" : ""}}>6 months</option>
                            <option value="12" {{ ($application->patching_frequency===12) ? "selected" : ""}}>12 months</option>
                        </select>
                        <span class="help-block">Fréquence de mise à jour</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="next_update">{{ trans('cruds.application.fields.next_update') }}</label>
                        <input class="form-control date" type="text" name="next_update"  id="next_update" value="{{ old('next_update', $application->next_update) }}">
                        <span class="help-block">{{ trans('cruds.application.fields.next_update_helper') }}</span>
                    </div>
                </div>
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
                <label for="name">{{ trans('cruds.application.fields.vendor') }}</label>
                <div class="form-group">
                    <select id="vendor-selector" class="form-control select2-free" name="vendor">
                        <option>{{ old('vendor', $application->vendor) }}</option>
                    </select>
                    <span class="help-block">{{ trans('cruds.application.fields.vendor_helper') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">{{ trans('cruds.application.fields.product') }}</label>
                <select id="product-selector" class="form-control select2-free" name="product">
                    <option>{{ old('name', $application->product) }}</option>
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
                <select id="version-selector" class="form-control select2-free" name="version">
                    <option>{{ old('version', $application->version) }}</option>
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
                <button type="button" class="btn btn-info" id="guess" alt="Guess vendor and product base on application name">Guess</button>
                <span class="help-block"></span>
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

    /**
     * Contruction de la liste des évènements
     * @returns {string}
     */
    function generateEventsList() {
        let ret = '<ul>';
        @json($application->events).forEach (function(event) {
            ret += '<li data-id="'+event.id+'" style="text-align: left; margin-bottom: 20px; position: relative">';
            ret += '<a class="delete_event" style="cursor: pointer; position: absolute;right: 0;top: 5px;" href="#">';
            ret += '<i data-toggle="wy-nav-top" class="fa fa-times"></i></a>'+event.message+'</br>';
            ret += '<span style="font-size: 12px;">Date : '+ moment(event.created_at).format('DD-MM-YYYY')
            ret += ' | Utilisateur : '+event.user.name+'</span>';
        });
        ret += '</ul>';
        return ret;
    }

    /**
     * Fire the popup
     */
        $('.events_list_button').click(function(e) {
            e.preventDefault()
            Swal.fire({
                title: 'Évènements',
                // icon: 'info',
                html: generateEventsList(),
                didOpen(popup) {
                    $('.delete_event').on('click', function(e) {
                        e.preventDefault();
                        let event_id = $(this).parent().data('id');
                        var that = $(this);
                        if (event_id) {
                            $.ajax({
                                url: '/admin/application-events/' + event_id,
                                type: "DELETE",
                                data: {
                                    m_application_id: {{ $application->id }},
                                    _token: "{{ csrf_token() }}"
                                },
                                success: (data) => {
                                    that.parent().remove();
                                    // Mise à jour des évènements pour la popup
                                    swalHtml = data.events;
                                    Swal.fire('Evènement supprimé !', '', 'success');
                                },
                                error: () => {
                                    Swal.fire('Une erreur est survenue', '', 'error');
                                }
                            })
                        }
                    });
                }
            })
        });

    /**
     * Send AJAX for adding an event
     */
        $('#addEventBtn').on('click', function(e) {
            e.preventDefault();
            let app_id = {{ $application->id }};
            let user_id = {{ auth()->id() }};
            let message = $('#eventMessage').val();
            if(message !== '' && user_id && app_id) {
                $.post("{{ route("admin.application-events.store") }}", {
                    m_application_id: app_id,
                    user_id: user_id,
                    message: message,
                    _token: "{{ csrf_token() }}"
                }, "json")
                .done((data) => {
                    // Mise à jour des évènements pour la popup
                    swalHtml = data.events;
                    Swal.fire('Evènement ajouté !', '', 'success');
                    $('#eventMessage').val('');
                })
                .fail(() => {
                    Swal.fire('Une erreur est survenue', '', 'error');
                })
            }
        });

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
        });

        function template(data, container) {
          if (data.id==4) {
             return '\<span class="highRisk"\>'+data.text+'</span>';
         } else if (data.id==3) {
             return '\<span class="mediumRisk"\>'+data.text+'</span>';
         } else if (data.id==2) {
             return '\<span class="lowRisk"\>'+data.text+'</span>';
         } else if (data.id==1) {
             return '\<span class="veryLowRisk"\>'+data.text+'</span>';
         } else {
             return data.text;
         }
     }

     $('.risk').select2({
      templateSelection: template,
      escapeMarkup: function(m) {
          return m;
      }
    });

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

//=============================================================================

$('#update_date')
    .datetimepicker({
        format: 'DD/MM/YYYY'
    })
   .on('dp.change', function (e) {
       if ($('#patching_frequency').val()=="") {
           $('#next_update').val("");
       }
       else {
           var parts = $('#update_date.datepicker').val().split("/");
           var d = new Date(parseInt(parts[2], 10),
                             parseInt(parts[1], 10) - 1,
                             parseInt(parts[0], 10));
           d.setMonth(d.getMonth()+parseInt($('#patching_frequency').val()));
           $("#next_update").val(
               (d.getDate()>9 ? d.getDate() : ('0' + d.getDate())) + '/' +
               (d.getMonth()>8 ? (d.getMonth()+1) : ('0' + (d.getMonth()+1))) + '/' + d.getFullYear());
       }
   });

$('#clock').click(function (e) {
    if ($('#patching_frequency').val()!="") {
       $('#update_date').val('{{ now()->format('d/m/Y') }}');
       let d = new Date();
       d.setMonth(d.getMonth() + parseInt($('#patching_frequency').val()));
       $('#next_update').val(
           (d.getDate()>9 ? d.getDate() : ('0' + d.getDate())) + '/' +
           (d.getMonth()>8 ? (d.getMonth()+1) : ('0' + (d.getMonth()+1))) + '/' + d.getFullYear());
        }
    return false;
});

$('#patching_frequency').on('select2:select', function (e) {
    if ($('#patching_frequency').val()=="") {
        $('#next_update').val("");
    }
    else {
        var parts = $('#update_date.datepicker').val().split("/");
        var d = new Date(parseInt(parts[2], 10),
                          parseInt(parts[1], 10) - 1,
                          parseInt(parts[0], 10));
        d.setMonth(d.getMonth() + parseInt($('#patching_frequency').val()));
        $('#next_update').val(
            (d.getDate()>9 ? d.getDate() : ('0' + d.getDate())) + '/' +
            (d.getMonth()>8 ? (d.getMonth()+1) : ('0' + (d.getMonth()+1))) + '/' + d.getFullYear());
    }
});

//=============================================================================


});

</script>
@endsection
