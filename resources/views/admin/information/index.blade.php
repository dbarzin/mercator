@extends('layouts.admin')
@section('content')
@can('information_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.information.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.information.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.information.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.information.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.owner') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.sensitivity') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.security_need') }}
                            @if (config('mercator-config.parameters.security_need_auth'))
                            + {{ trans("global.authenticity_short") }}
                            @endif
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($information as $key => $information)
                        <tr data-entry-id="{{ $information->id }}"
                            @if(($information->description==null)||
                                ($information->owner==null)||
                                ($information->administrator==null)||
                                ($information->storage==null)||
                                ((auth()->user()->granularity>=2)&&
                                    (
                                    ($information->security_need_c==null)||
                                    ($information->security_need_i==null)||
                                    ($information->security_need_a==null)||
                                    ($information->security_need_t==null)
                                    )
                                )||
                                ($information->sensitivity==null)
                                )
                                                      class="table-warning"
                            @endif
                            >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.information.show', $information->id) }}">
                                {{ $information->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $information->description ?? '' !!}
                            </td>
                            <td>
                                {!! $information->owner ?? '' !!}
                            </td>
                            <td>
                                {{ $information->sensitivity ?? '' }}
                            </td>
                            <td nowrap>
                                @php
                                if ($information->security_need_c==0)
                                    echo "<span class='noRisk'>0</span>";
                                elseif ($information->security_need_c==1)
                                    echo "<span class='veryLowRisk'>1</span>";
                                elseif ($information->security_need_c==2)
                                    echo "<span class='lowRisk'>2</span>";
                                elseif ($information->security_need_c==3)
                                    echo "<span class='mediumRisk'>3</span>";
                                elseif ($information->security_need_c==4)
                                    echo "<span class='highRisk'>4</span>";
                                else
                                    echo "<span> * </span>";
                                echo " - ";
                                if ($information->security_need_i==0)
                                    echo "<span class='noRisk'>0</span>";
                                elseif ($information->security_need_i==1)
                                    echo "<span class='veryLowRisk'>1</span>";
                                elseif ($information->security_need_i==2)
                                    echo "<span class='lowRisk'>2</span>";
                                elseif ($information->security_need_i==3)
                                    echo "<span class='mediumRisk'>3</span>";
                                elseif ($information->security_need_i==4)
                                    echo "<span class='highRisk'>4</span>";
                                else
                                    echo "<span> * </span>";
                                echo " - ";
                                if ($information->security_need_a==0)
                                    echo "<span class='noRisk'>0</span>";
                                elseif ($information->security_need_a==1)
                                    echo "<span class='veryLowRisk'>1</span>";
                                elseif ($information->security_need_a==2)
                                    echo "<span class='lowRisk'>2</span>";
                                elseif ($information->security_need_a==3)
                                    echo "<span class='mediumRisk'>3</span>";
                                elseif ($information->security_need_a==4)
                                    echo "<span class='highRisk'>4</span>";
                                else
                                    echo "<span> * </span>";
                                echo " - ";
                                if ($information->security_need_t==0)
                                    echo "<span class='noRisk'>0</span>";
                                elseif ($information->security_need_t==1)
                                    echo "<span class='veryLowRisk'>1</span>";
                                elseif ($information->security_need_t==2)
                                    echo "<span class='lowRisk'>2</span>";
                                elseif ($information->security_need_t==3)
                                    echo "<span class='mediumRisk'>3</span>";
                                elseif ($information->security_need_t==4)
                                    echo "<span class='highRisk'>4</span>";
                                else
                                    echo "<span> * </span>";
                                if (config('mercator-config.parameters.security_need_auth')) {
                                    echo "-";
                                    if ($information->security_need_auth==0)
                                        echo "<span class='noRisk'>0</span>";
                                    elseif ($information->security_need_auth==1)
                                        echo "<span class='veryLowRisk'>1</span>";
                                    elseif ($information->security_need_auth==2)
                                        echo "<span class='lowRisk'>2</span>";
                                    elseif ($information->security_need_auth==3)
                                        echo "<span class='mediumRisk'>3</span>";
                                    elseif ($information->security_need_auth==4)
                                        echo "<span class='highRisk'>4</span>";
                                    else
                                        echo "<span> * </span>";
                                    }
                                @endphp
                            </td>
                            <td nowrap>
                                @can('information_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.information.show', $information->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('information_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.information.edit', $information->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('information_delete')
                                    <form action="{{ route('admin.information.destroy', $information->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.information.title_singular"),
    'URL' => route('admin.information.massDestroy'),
    'canDelete' => auth()->user()->can('information_delete') ? true : false
));
</script>
@endsection
