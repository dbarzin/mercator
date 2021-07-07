@extends('layouts.admin')

@section('styles')

.wrap-ds-chitiet+.select2-container {
  width: 100% !important;
}

.select2-search--dropdown {
  display: none !important;
}

.wrap-ds-chitiet+.select2-container .select2-selection {
  height: 55px;
  border-top-right-radius: 10px;
  border-top-left-radius: 10px;
  border: none;
  font-family: 'Montserrat';
  font-weight: 600;
  font-size: 0.93rem;
  color: #fff;
}

.wrap-ds-chitiet+.select2-container .select2-selection--single .select2-selection__rendered {
  color: #fff;
  padding-left: 20px;
  padding-top: 13px;
}

.wrap-ds-chitiet+.select2-container--default .select2-selection--single .select2-selection__arrow {
  margin-right: 21px;
  margin-top: 11px;
}

.default {
  background: linear-gradient(to right, #f8ca6b, #fbad7d);
}

.defaultColor {
  background: linear-gradient(to right, #f8ca6b, #fbad7d);
}

.select2-selection--single{
background-color:transparent;
}

.blueColor {
  background: linear-gradient(to right, blue, blue);
}

.pinkColor {
  background: linear-gradient(to right, #FFC0CB, #FFC0CB);
}

@endsection

@section('content')
<div class="card">
    <div class="card-header">
        Titre 2
    </div>

    <div class="card-body">


    <div class="form-group">
      <select class="form-control select2 risk wrap-ds-chitiet" id="ds-chitiet-option">
        <option value="checkin">Check-in</option>
        <option value="notcheckin">Not check-in</option>
        <option value="cancel">Cancel</option>
      </select>
    </div>


  </div>
</div>
@endsection

@section('scripts')
@parent

<script language="javascript">

// https://stackoverflow.com/questions/53405493/how-can-i-set-a-different-color-for-each-option-in-a-select2

$(document).ready(function() {
  $('.risk').select2()
  $('.risk').on('select2:select', function(e) {
    var data = e.params.data;
    $(".risk + .select2-selection").removeClass("defaultColor blueColor blueColor");
    CheckValues(data.element.value)
    console.log(data);
  });

  function CheckValues(value) {
    switch (value) {
      case "checkin":
        $(".risk + .select2-selection").addClass("defaultColor");
        break;
      case "notcheckin":
        $(".risk + .select2-selection").addClass("blueColor");
        break;
      case "cancel":
        $(".risk + .select2-selection").addClass("pinkColor");
        break;
    }
  }
  
  CheckValues($(".risk").val())

});

//With "not check-in": background color change to blue, and with "cancel": background color change to pink.


</script>
@endsection


