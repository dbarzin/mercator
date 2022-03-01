<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <!-- link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" />
    <!--link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet" />
    <!--<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <!-- link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/buttons.dataTables.min.css') }}" rel="stylesheet" />
    <!-- link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/select.dataTables.min.css') }}" rel="stylesheet" />
    <!-- link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
    <!-- link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />


    <!-- link href="https://unpkg.com/@coreui/coreui@2.1.16/dist/css/coreui.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/coreui.min.css') }}" rel="stylesheet" />
    <!-- link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/dropzone.min.css') }}" rel="stylesheet" />
    <!-- custom css -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

</head>

<style>
    @yield('styles')
</style>

<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show">
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/admin">
            <span class="navbar-brand-full">{{ trans('panel.site_title') }}</span>
            <span class="navbar-brand-minimized">{{ trans('panel.site_title') }}</span>
        </a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="nav navbar-nav mr-auto">
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ trans('panel.views') }}
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="/admin/report/ecosystem">{{ trans('panel.menu.ecosystem') }}</a>
                      <a class="dropdown-item" href="/admin/report/information_system">{{ trans('panel.menu.information_system') }}</a>
                      <a class="dropdown-item" href="/admin/report/applications">{{ trans('panel.menu.applications') }}</a>
                      <a class="dropdown-item" href="/admin/report/application_flows">{{ trans('panel.menu.application_flows') }}</a>
                      <a class="dropdown-item" href="/admin/report/administration">{{ trans('panel.menu.administration') }}</a>
                      <a class="dropdown-item" href="/admin/report/logical_infrastructure">{{ trans('panel.menu.logical_infrastructure') }}</a>
                      <a class="dropdown-item" href="/admin/report/physical_infrastructure">{{ trans('panel.menu.physical_infrastructure') }}</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      &nbsp {{ trans('panel.menu.preferences') }}
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="/profile/preferences">{{ trans('panel.menu.options') }}</a>
                      <a class="dropdown-item" href="/profile/password">{{ trans('panel.menu.password') }}</a>
                      @can('configure')
                      <a class="dropdown-item" href="/admin/configuration">{{ trans('panel.menu.config') }}</a>
                      @endcan
                    </div>
                </li>
                </li>

                <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      &nbsp {{ trans('panel.menu.documentation') }}
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="/admin/doc/report">{{ trans('panel.menu.reports') }}</a>
                      <a class="dropdown-item" href="/admin/doc/schema">{{ trans('panel.menu.schema') }}</a>
                      <a class="dropdown-item" href="/admin/doc/guide">{{ trans('panel.menu.guide') }}</a>
                      <a class="dropdown-item" href="/admin/doc/about">{{ trans('panel.menu.about') }}</a>
                    </div>
                </li>
        </ul>
    </header>

    <div class="app-body">
        @include('partials.menu')
        <main class="main">

            <div style="padding-top: 20px" class="container-fluid">
                @if(session('message'))
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                @endif
                @if($errors->count() > 0)
                    <div class="alert alert-danger">
                        <ul class="list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')

            </div>
        </main>
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script -->
    <script src="{{ asset ('/js/jquery.min.js') }}"></script>
    <!-- script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script-->
    <script src="/js/bootstrap.min.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script -->
    <script src="/js/popper.min.js"></script>
    <!-- script src="https://unpkg.com/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script -->
    <script src="/js/coreui.min.js"></script>
    <!-- script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script -->
    <script src="/js/jquery.dataTables.min.js"></script>
    <!-- script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script-->
    <script src="/js/dataTables.bootstrap4.min.js"></script>
    <!-- script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script -->
    <script src="/js/dataTables.buttons.min.js"></script>
    <!-- script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script-->
    <script src="/js/buttons.flash.min.js"></script>
    <!-- script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script-->
    <script src="/js/buttons.html5.min.js"></script>
    <!-- script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script-->
    <script src="/js/buttons.print.min.js"></script>
    <!-- script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script-->
    <script src="/js/buttons.colVis.min.js"></script>
    <!-- script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script-->
    <script src="/js/pdfmake.min.js"></script>
    <!-- script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script-->
    <script src="/js/vfs_fonts.js"></script>
    <!-- script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script-->
    <script src="/js/jszip.min.js"></script>
    <!-- script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script-->
    <script src="/js/dataTables.select.min.js"></script>
    <!-- script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script-->
    <script src="/js/ckeditor.js"></script>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script-->
    <script src="/js/moment.min.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script-->
    <script src="/js/bootstrap-datetimepicker.min.js"></script>

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script-->
    <script src="/js/select2.full.min.js"></script>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script-->
    <script src="/js/dropzone.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
  let selectAllButtonTrans = '{{ trans('global.select_all') }}'
  let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

  let languages = {
    'fr': '/i18n/French.json'
  };

  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      url: languages['{{ app()->getLocale() }}']
    },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 100, stateSave: true,
    dom: 'lBfrtip<"actions">',
    buttons: [
      {
        extend: 'selectAll',
        className: 'btn-primary',
        text: selectAllButtonTrans,
        exportOptions: {
          columns: ':visible'
        },
        action: function(e, dt) {
          e.preventDefault()
          dt.rows().deselect();
          dt.rows({ search: 'applied' }).select();
        }
      },
      {
        extend: 'selectNone',
        className: 'btn-primary',
        text: selectNoneButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'copy',
        className: 'btn-default',
        text: copyButtonTrans,
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: csvButtonTrans,
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: excelButtonTrans,
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: pdfButtonTrans,
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: printButtonTrans,
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: colvisButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });
  $.fn.dataTable.ext.classes.sPageButton = '';
});

$(document).ready(function() {
    $('.searchable-field').select2({
        minimumInputLength: 3,
        ajax: {
            url: '{{ route("admin.globalSearch") }}',
            dataType: 'json',
            type: 'GET',
            delay: 200,
            data: function (param) {
                return {
                     search: param.term
                };
            },
            results: function (data) {
                return {
                    data
                };
            }
        },
        escapeMarkup: function (markup) { return markup; },
        templateResult: formatItem,
        templateSelection: formatItemSelection,
        placeholder : '{{ trans('global.search') }}...',
        language: {
            inputTooShort: function(args) {
                var remainingChars = args.minimum - args.input.length;
                var translation = '{{ trans('global.search_input_too_short') }}';

                return translation.replace(':count', remainingChars);
            },
            errorLoading: function() {
                return '{{ trans('global.results_could_not_be_loaded') }}';
            },
            searching: function() {
                return '{{ trans('global.searching') }}';
            },
            noResults: function() {
                return '{{ trans('global.no_results') }}';
            },
        }
    });
    function formatItem (item) {
        if (item.loading) {
            return '{{ trans('global.searching') }}...';
        }
        var markup = "<div class='searchable-link' href='" + item.url + "'>";
        markup += "<div class='searchable-title'>" + item.model + "</div>";
        $.each(item.fields, function(key, field) {
            markup += "<div class='searchable-fields'>" + item.fields_formated[field] + " : " + item[field] + "</div>";
        });
        markup += "</div>";

        return markup;
    }

    function formatItemSelection (item) {
        if (!item.model) {
            return '{{ trans('global.search') }}...';
        }
        return item.model;
    }
    $(document).delegate('.searchable-link', 'click', function() {
        var url = $(this).attr('href');
        window.location = url;
    });
});

    </script>
    @yield('scripts')
</body>

</html>
