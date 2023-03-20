@extends('layouts.admin')
@section('content')

<?php
function bytesToHuman($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
    return round($bytes, 2) . ' ' . $units[$i];
}
?>

<div class="card">

    <div class="card-header">
        {{ trans("cruds.configuration.documents.title") }}
    </div>

    <div class="card-body">

		<div class="p-3">
		    <div data-role="panel" data-title-caption="{{ trans('cruds.document.index') }}" data-collapsible="true" data-title-icon="<span class='mif-chart-line'></span>">

		    <div class="grid">
		        <div class="row">
		            <div class="cell-3">
		            {{ trans('cruds.configuration.documents.count') }} : {{ $count }}
		            </div>
		        </div>
		        <div class="row">
		            <div class="cell-3">
		            {{ trans('cruds.configuration.documents.total_size') }} : {{ bytesToHuman($sum) }} 
		            </div>
		        </div>
		        <div class="row">
		            <div class="cell-3">
		            	<br>
		            </div>
		        </div>
		        <div class="row">
		            <div class="cell-3">
		                <form action="{{ route('admin.config.documents.check') }}">
				            <div class="form-group">
				                <button class="btn btn-success" type="submit">
				                    {{ trans('global.check') }}
				                </button>
				            </div>
				        </form>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
@endsection
