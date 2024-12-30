<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>

    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/coreui.min.css') }}" rel="stylesheet" />

    <!-- Fontawesome -->
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet" />

    <!-- Datatables -->
    <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet" />

    <!-- link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" /-->
    <link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />

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

    @yield('styles')
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
                {{ trans('panel.menu.preferences') }}
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="/profile/preferences">
                    <i class="fa-fw fas fa-gear nav-icon"></i>
                    {{ trans('panel.menu.options') }}
                </a>
                @can('profile_password_edit')
                <a class="dropdown-item" href="/profile/password">
                    <i class="fa-fw fas fa-lock nav-icon"></i>
                    {{ trans('panel.menu.password') }}
                </a>
                @endcan
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                {{ trans('panel.menu.tools') }}
            </a>
            <div class="dropdown-menu">
                @can('patching_access')
                <a class="dropdown-item" href="/admin/patching/index">
                    <i class="fa-fw fas fa-wrench"></i>
                    {{ trans('panel.menu.patching') }}
                </a>
                @endcan
                <a class="dropdown-item" href="/admin/report/explore">
                    <i class="fa-fw fas fa-globe"></i>
                    {{ trans('panel.menu.explore') }}
                </a>
                <a class="dropdown-item" href="/admin/doc/report">
                    <i class="fa-fw fas fa-file"></i>
                    {{ trans('panel.menu.reports') }}
                </a>
            </div>
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

    <!-- SweetAlert -->
    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="/js/sweetalert2.all.min.js"></script>

    <!-- JQuery -->
    <script src="/js/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="/js/bootstrap.bundle.min.js"></script>

    <!-- CoreUI - needed for sidebars -->
    <script src="/js/coreui.min.js"></script>

    <!-- Datatables -->
    <script src="/js/datatables.min.js"></script>

    <script src="/js/ckeditor.js"></script>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script-->
    <script src="/js/moment.min.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script-->
    <script src="/js/bootstrap-datetimepicker.min.js"></script>

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script-->
    <script src="/js/select2.full.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    @yield('scripts')

</body>
</html>
