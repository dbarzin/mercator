@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Titre
    </div>

    <div class="card-body">
      <div class="form-group">
    
        <label>Field name</label>

        <select class="form-control free-text" name="cppss" id="cppss">
            <option selected></option>
            <option>Papier</option>
            <option>Pierre</option>
            <option>Ciseau</option>
            <option>Serpent</option>
            <option>Spock</option>
        </select>

      </div>
  </div>
</div>
@endsection

@section('scripts')
@parent
@endsection


