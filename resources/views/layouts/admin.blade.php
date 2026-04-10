<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', trans('panel.page.untitled')) | {{ trans('panel.site_title') }}</title>
    <script>
        window._lang = {
            colvis:  "{{ trans('global.datatables.colvis') }}",
            copy:    "{{ trans('global.datatables.copy') }}",
            print:   "{{ trans('global.datatables.print') }}",
            delete:  "{{ trans('global.datatables.delete') }}"
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <script>
    try {
        if (localStorage.getItem('sidebar_hidden') === 'true') {
            document.documentElement.classList.add('sidebar-preload-hidden');
        }
    } catch (_) {
        // Storage unavailable; no-op
    }
    </script>
</head>
<body>
    @include('partials.navbar')
    <div class="d-flex">
    @include('partials.sidebar')
        <div id="content-home" class="content flex-grow-1 p-3">
            @if(session('message'))
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="alert alert-danger">
                    <ul class="list-group-flush">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    <script>
        (function() {
            const isHidden = localStorage.getItem('sidebar_hidden') === 'true';
            if (isHidden) {
                document.getElementById('sidebar').classList.add('sidebar-hidden');
                document.getElementById('content-home').classList.add('content-expanded');
            }
        })();

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content-home');

            sidebar.classList.toggle('sidebar-hidden');
            content.classList.toggle('content-expanded');

            // Sauvegarder l'état
            const isHidden = sidebar.classList.contains('sidebar-hidden');
            localStorage.setItem('sidebar_hidden', isHidden);
        }
    </script>
    @yield('scripts')
    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</body>
</html>
