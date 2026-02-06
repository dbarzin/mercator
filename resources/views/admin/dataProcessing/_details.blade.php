<table class="table table-bordered table-striped table-report" id="{{ $dataProcessing->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.dataProcessing.fields.name') }}
        </th>
        <td>
            {{ $dataProcessing->name }}
        </td>
        <th width="10%">
            {{ trans('cruds.dataProcessing.fields.legal_basis') }}
        </th>
        <td>
            {{ $dataProcessing->legal_basis }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.description') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->description !!}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.responsible') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->responsible !!}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.purpose') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->purpose !!}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.lawfulness') }}
        </th>
        <td colspan="3">
            <table width="100%">
                <td>
                    <input class="form-check-input" type="checkbox"
                           {{ $dataProcessing->lawfulness_consent ? "checked" : "" }} disabled>
                    {{ trans('cruds.dataProcessing.fields.lawfulness_consent') }}
                </td>
                <td>
                    <input class="form-check-input" type="checkbox"
                           {{ $dataProcessing->lawfulness_contract ? "checked" : "" }} disabled>
                    {{ trans('cruds.dataProcessing.fields.lawfulness_contract') }}
                </td>
                <td>
                    <input class="form-check-input" type="checkbox"
                           {{ $dataProcessing->lawfulness_legal_obligation ? "checked" : "" }} disabled>
                    {{ trans('cruds.dataProcessing.fields.lawfulness_legal_obligation') }}
                </td>
                <td>
                    <input class="form-check-input" type="checkbox"
                           {{ $dataProcessing->lawfulness_vital_interest ? "checked" : "" }} disabled>
                    {{ trans('cruds.dataProcessing.fields.lawfulness_vital_interest') }}
                </td>
                <td>
                    <input class="form-check-input" type="checkbox"
                           {{ $dataProcessing->lawfulness_public_interest ? "checked" : "" }} disabled>
                    {{ trans('cruds.dataProcessing.fields.lawfulness_public_interest') }}
                </td>
                <td>
                    <input class="form-check-input" type="checkbox"
                           {{ $dataProcessing->lawfulness_legitimate_interest ? "checked" : "" }} disabled>
                    {{ trans('cruds.dataProcessing.fields.lawfulness_legitimate_interest') }}
                </td>
            </table>
        </td>
    </tr>
    <tr>
        <th>
            &nbsp;
        </th>
        <td colspan='3'>
            {!! $dataProcessing->lawfulness !!}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.categories') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->categories !!}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.recipients') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->recipients !!}
        </td>
    </tr>


    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.transfert') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->transfert !!}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.retention') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->retention !!}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.processes') }}
        </th>
        <td colspan='3'>
            @foreach($dataProcessing->processes as $process)
                <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->name }}</a>
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.applications') }}
        </th>
        <td colspan='3'>
            @foreach($dataProcessing->applications as $application)
                <a href="{{ route('admin.applications.show', $application->id) }}">{{ $application->name }}</a>
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.information') }}
        </th>
        <td colspan='3'>
            @foreach($dataProcessing->informations as $information)
                <a href="{{ route('admin.information.show', $information->id) }}">{{ $information->name }}</a>
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.documents') }}
        </th>
        <td colspan='3'>
            @foreach($dataProcessing->documents as $document)
                <a href="{{ route('admin.documents.show', $document->id) }}">{{ $document->filename }}</a>
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>

    </tbody>
</table>
