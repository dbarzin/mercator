<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>

    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-icons.css') }}" rel="stylesheet">

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
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('sidebar-hidden');
            document.getElementById('content').classList.toggle('content-expanded');
        }
    </script>
</head>
<body>
    @include('partials.navbar')
    <div class="d-flex">
    @include('partials.sidebar')
        <div class="content flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>
    <!-- SweetAlert -->
    <script src="/js/sweetalert2.all.min.js"></script>

    <!-- JQuery -->
    <script src="/js/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="/js/bootstrap.bundle.min.js"></script>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let sidebar = document.querySelector(".sidebar");
            let dropdowns = document.querySelectorAll(".sidebar .dropdown-toggle");

            dropdowns.forEach(dropdown => {
                dropdown.addEventListener("click", function() {
                    // Ferme les autres menus
                    document.querySelectorAll(".sidebar .collapse.show").forEach(openMenu => {
                        if (openMenu !== this.nextElementSibling) {
                            openMenu.classList.remove("show");
                        }
                    });

                    // Centrer l'élément ouvert dans la scrollbar
                    setTimeout(() => {
                        let rect = this.getBoundingClientRect();
                        let sidebarRect = sidebar.getBoundingClientRect();
                        sidebar.scrollTop += rect.top - sidebarRect.top - 50;
                    }, 300);
                });
            });

            let openMenu = document.querySelector(".sidebar .collapse.show");

            if (openMenu) {
                setTimeout(() => {
                    let rect = openMenu.getBoundingClientRect();
                    let sidebarRect = sidebar.getBoundingClientRect();
                    sidebar.scrollTop += rect.top - sidebarRect.top - (sidebar.clientHeight / 2) + (rect.height / 2);
                }, 300);
            }
        });
    </script>
    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</body>
</html>
