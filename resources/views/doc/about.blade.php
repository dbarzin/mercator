@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans("dummy.title")}}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    {{ trans("dummy.text")}}
                </div>
                <div class="col-7">
                    {{ $now }}
                </div>
            </div>
        </div>
    </div>
@endsection

