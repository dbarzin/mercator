@extends('layouts.admin')

@section('styles')

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
            <option>Breakfast</option>
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
    console.log("ready.");
$(document).ready(function () {
        $('myField').select2();
        $('.select2-container--default .select2-selection--multiple .select2-selection__choice').each(function () {
            var title = $(this).attr('title');
            console.log(title);
            if (title === 'Breakfast') {                
                $(this).parent().css({ 'background-color': "green" });
            }
            if (title === 'Brunch') {
                $(this).parent().css({ 'background-color': "red" });
            }
            if (title === 'Lunch') {
                $(this).parent().css({ 'background-color': "blue" });
            }
        });
    });
</script>

@endsection


