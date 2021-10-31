@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("doc.guide.title") }}
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-6" style="min-width: 460px;">
                        <p>{!! trans("doc.guide.p1") !!}</p>
                        <p>{!! trans("doc.guide.p2") !!}</p>
                        <p>{!! trans("doc.guide.p3") !!}</p>
                        <p>{!! trans("doc.guide.p4") !!}</p>
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