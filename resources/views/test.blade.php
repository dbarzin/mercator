@extends('layouts.admin')

@section('styles')

.select2-container--default .select2-results__option--highlighted[aria-selected] {
background-color: red;
color: #fff;
}
 
// Remove focus outline
 
.select2-container *:focus {
    outline: none;
 }

.yellow {
    background-color: yellow !important;    
}

@endsection

@section('content')
<div class="card">
    <div class="card-header">
        Titre
    </div>

    <div class="card-body">
      <div class="form-group">
    
        <label>Field name</label>

        <select class="form-control select2" name="cppss" id="cppss">
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

<div class="card">
    <div class="card-header">
        Color Test
    </div>

    <div class="card-body">
      <div class="form-group">
    

<div>

    <div wire:ignore>
        <select class="form-control select2 myField" id="test">
            <option selected></option>
            <option>Papier</option>
            <option class="yellow">Breakfast</option>
            <option>Brunch</option>
            <option>Serpent</option>
            <option>Spock</option>
        </select>
    </div>

</div>



      </div>
  </div>
</div>
@endsection



@section('scripts')
@parent

<script>


</script>

@endsection


