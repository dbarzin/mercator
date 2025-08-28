<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ trans('panel.site_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="card">
    <div class="card-header">
        Test page
    </div>
    <div class="card-body">
        <div id="graph-container" style="width:100%;height:400px;border:1px solid #ccc"></div>
    </div>
</div>
@vite('resources/js/map.test.ts')
</body>
</html>
