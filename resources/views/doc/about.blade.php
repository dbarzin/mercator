@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans("doc.about.title")}}
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-3" >
            <a href="https://www.sourcentis.com/mercator"><img src="/images/mercator.png" class="img-fluid"></a>
        </div>
        <div class="col-7">
            {!! trans("doc.about.text") !!}
        </div>
      </div>
  </div>
</div>
@endsection

@section('scripts')
@parent
@endsection
