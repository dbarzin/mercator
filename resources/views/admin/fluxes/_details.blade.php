<table class="table table-bordered table-striped">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.flux.fields.name') }}
        </th>
        <td>
            {{ $flux->name }}
        </td>
        <th width="10%">
            {{ trans('cruds.flux.fields.nature') }}
        </th>
        <td>
            {{ $flux->nature }}
        </td>
        <th width="10%">
            {{ trans('cruds.flux.fields.attributes') }}
        </th>
        <td>
            @foreach(explode(" ",$flux->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.flux.fields.description') }}
        </th>
        <td colspan="5">
            {!! $flux->description !!}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.flux.fields.source') }}
        </th>
        <td colspan="1">
            @if ($flux->application_source!=null)
                <a href="{{ route('admin.applications.show',$flux->application_source->id) }}">
                    {{ $flux->application_source->name }}
                </a>
            @endif
            @if (auth()->user()->granularity>=2)
                @if($flux->service_source!=null)
                    <a href="{{ route('admin.application-services.show', $flux->service_source->id) }}">
                        {{ $flux->service_source->name }}
                    </a>
                @endif
                @if ($flux->module_source!=null)
                    <a href="{{ route('admin.application-modules.show', $flux->module_source->id) }}">
                        {{ $flux->module_source->name }}
                    </a>
                @endif
            @endif
            @if ($flux->database_source!=null)
                <a href="{{ route('admin.databases.show',$flux->database_source->id) }}">
                    {{ $flux->database_source->name }}
                </a>
            @endif
        </td>

        <th>
            {{ trans('cruds.flux.fields.destination') }}
        </th>
        <td colspan="3">
            @if ($flux->application_dest!=null)
                <a href="{{ route('admin.applications.show',$flux->application_dest->id) }}">
                    {{ $flux->application_dest->name }}
                </a>
            @endif
            @if (auth()->user()->granularity>=2)
                @if ($flux->service_dest!=null)
                    <a href="{{ route('admin.application-services.show', $flux->service_dest->id) }}">
                        {{ $flux->service_dest->name }}
                    </a>
                @endif
                @if ($flux->module_dest!=null)
                    <a href="{{ route('admin.application-modules.show', $flux->module_dest->id) }}">
                        {{ $flux->module_dest->name }}
                    </a>
                @endif
            @endif
            @if ($flux->database_dest!=null)
                <a href="{{ route('admin.databases.show',$flux->database_dest->id) }}">
                    {{ $flux->database_dest->name }}
                </a>
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.flux.fields.information') }}
        </th>
        <td colspan="5">
            @foreach($flux->informations as $info)
                <a href="{{ route('admin.information.show',$info->id) }}">{{$info->name}}</a>
                @if (!$loop->last) , @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.flux.fields.crypted') }}
        </th>
        <td>
            @if ($flux->crypted==0)
                Non
            @elseif ($flux->crypted==1)
                Oui
            @endif
        </td>
        <th>
            {{ trans('cruds.flux.fields.bidirectional') }}
        </th>
        <td colspan="3">
            @if ($flux->bidirectional==0)
                Non
            @elseif ($flux->bidirectional==1)
                Oui
            @endif
        </td>
    </tr>
    </tbody>
</table>
