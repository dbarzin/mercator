<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <!-- link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" /-->
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"-->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

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

    <!-- link href="https://unpkg.com/@coreui/coreui@2.1.16/dist/css/coreui.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/coreui.min.css') }}" rel="stylesheet" />

    <!-- Dropzone -->
    <!-- https://rawgit.com/enyo/dropzone/master/dist/dropzone.min.css -->
    <link rel="stylesheet" href="{{ asset('/css/dropzone.css') }}">

    <!-- Datepicker -->
    <!-- https://vitalets.github.io/bootstrap-datepicker/ -->
    <link href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />

    <!-- custom css -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    <!-- DynamicSelect -->
    <link href="{{ asset('css/DynamicSelect.css') }}" rel="stylesheet" />

    <style>
@yield('styles')
    </style>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show">
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/admin">
            <span class="navbar-brand-full">{{ (env('APP_NAME') === null) || (env('APP_NAME') === "Laravel") ? "Mercator" : env('APP_NAME') }}</span>
            <span class="navbar-brand-minimized"></span>
        </a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>
    <!-------------------------------------------------->
    <ul class="nav nav-pills mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
          {{ trans('panel.views') }}
        </a>
            <ul class="dropdown-menu" style="">
                @can('gdpr_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/gdpr">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.gdpr') }}
                    </a>
                </li>
                @endcan
                @can('ecosystem_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/ecosystem">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.ecosystem') }}
                    </a>
                </li>
                @endcan
                @can('metier_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/information_system">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.information_system') }}
                    </a>
                </li>
                @endcan
                @can('application_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/applications">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.applications') }}
                    </a>
                </li>
                @can('flux_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/application_flows">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.application_flows') }}
                    </a>
                </li>
                @endcan
                @endcan
                @can('administration_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/administration">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.administration') }}
                    </a>
                </li>
                @endcan
                @can('infrastructure_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/logical_infrastructure">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.logical_infrastructure') }}
                    </a>
                </li>
                @endcan
                @can('physicalinfrastructure_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/physical_infrastructure">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.physical_infrastructure') }}
                    </a>
                </li>
                @endcan
                @can('physical_link_access')
                <li>
                    <a class="dropdown-item" href="/admin/report/network_infrastructure">
                        <i class="fa-fw fas fa-cogs nav-icon"></i>
                        {{ trans('panel.menu.network_infrastructure') }}
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                {{ trans('panel.menu.tools') }}
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.report.explore') }}">
                        <i class="fa-fw fas fa-globe"></i>
                        {{ trans('panel.menu.explore') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="/profile/preferences">
                        <i class="fa-fw fas fa-gear nav-icon"></i>
                        {{ trans('panel.menu.options') }}
                    </a>
                </li>
                @can('profile_password_edit')
                <li>
                    <a class="dropdown-item" href="/profile/password">
                        <i class="fa-fw fas fa-lock nav-icon"></i>
                        {{ trans('panel.menu.password') }}
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                &nbsp {{ trans('panel.menu.help') }}
            </a>
            <ul class="dropdown-menu">
              <li>
                  <a class="dropdown-item" href="/admin/doc/schema">
                        <i class="fa-fw fas fa-map nav-icon"></i>
                      {{ trans('panel.menu.schema') }}
                  </a>
              </li>
              <li>
                  <a class="dropdown-item" href="/admin/doc/guide">
                        <i class="fa-fw fas fa-book nav-icon"></i>
                      {{ trans('panel.menu.guide') }}
                  </a>
              </li>
              <li>
                  <a class="dropdown-item" href="/admin/doc/about">
                        <i class="fa-fw fas fa-info nav-icon"></i>
                      {{ trans('panel.menu.about') }}
                  </a>
              </li>
          </ul>
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
    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="/js/sweetalert2.all.min.js"></script>

    <!--script src="https://code.jquery.com/jquery-3.7.1.js"></script-->
    <script src="{{ asset ('/js/jquery-3.7.1.js') }}"></script>

    <!-- Bootstrap -->
    <!-- script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script -->
    <script src="/js/bootstrap.bundle.min.js"></script>

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


    <script src="{{ asset('js/main.js') }}"></script>
    <script>
$(function() {

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
        text: '{{ trans('global.select_all') }}',
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
        text: '{{ trans('global.deselect_all') }}',
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'copy',
        className: 'btn-default',
        text: '{{ trans('global.datatables.copy') }}',
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: '{{ trans('global.datatables.csv') }}',
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: '{{ trans('global.datatables.excel') }}',
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: '{{ trans('global.datatables.pdf') }}',
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: '{{ trans('global.datatables.print') }}',
        exportOptions: {
          columns: [':visible:not(:last-child):gt(0)']
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: '{{ trans('global.datatables.colvis') }}',
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });
  $.fn.dataTable.ext.classes.sPageButton = '';
});
    </script>
@yield('scripts')

</body>
</html>
