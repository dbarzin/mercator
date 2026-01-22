<div class="row">
    <div class="col-md-6">
        <dt>{{ trans('cruds.workstation.fields.name') }}</dt>
        {{ $workstation->name }}
    </div>
    <div class="col-md-3">
        <dt>{{ trans('cruds.workstation.fields.type') }}</dt>
        {{ $workstation->type }}
    </div>
    <div class="col-md-3">
        <dt>{{ trans('cruds.workstation.fields.status') }}</dt>
        {{ $workstation->status }}
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <dt>{{ trans('cruds.workstation.fields.description') }}</dt>
        {!! $workstation->description !!}
    </div>
    <div class="col-md-3">
        <img src="{{ $workstation->icon_id === null ? '/images/workstation.png' : route('admin.documents.show', $workstation->icon_id) }}" width='120' height='120'/>
    </div>
</div>
