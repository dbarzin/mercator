@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Reporting
                </div>
                <div class="card-body">
                  <ul>
                    <li>
                      Applications par groupe applicatif<br><br>
                    </li>
                    <li>
                      Applications par serveur<br><br>
                    </li>
                    <li>
                      Configuration des serveurs<br><br>
                    </li>
                    <li>
                      Inventaire des commutateurs<br><br>
                    </li>
                  </ul>

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