@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("doc.about.title")}}
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-3" style="min-width: 460px;">
                        <img src="/images/mercator.jpg">
                    </div>
                    <div class="col-5">
                        <p> {!! trans("doc.about.p1") !!} </p>
                        <p> {!! trans("doc.about.p2") !!} </p>
                        <p> {!! trans("doc.about.p3") !!} </p>
                        <p> {!! trans("doc.about.p4") !!} </p>
                        <p> {!! trans("doc.about.p5") !!} </p>
                    </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('scripts')
@parent
@endsection
