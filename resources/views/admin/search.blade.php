@extends('layouts.admin')

@section("content")
    <div class="card">

        @if (empty($searchableData))
        <div class="card-header">
            Empty
        </div>
        @else

        @php
            $curModel = null;
        @endphp

		@foreach($searchableData as $item)

            @if ($curModel!==$item['model'])

                @if ($curModel!==null)
                    </table>
                </div>
            </div>
                @endif

                @php
                    $curModel = $item['model'];
                @endphp

                    <div class="card-header">
                        {{ $item['name'] }}
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-{{ $curModel }}">
                    <thead>
                      <tr>
                          <th width="40"></th>
                    @foreach($item['fields_formated'] as $field)
                    <th>{{ $field }}</th>
                    @endforeach
                    <th style="display:none;"></th>
                    </tr>
                </thead>
            @endif

                <tr>
                    <td></td>
                @foreach($item['fields'] as $field)
                    <td>
                        @if ($loop->first)
                        <a href='{{ $item['url'] }}'>
                        @endif
                        {!! $item['data'][$field] !!}</td>
                        @if ($loop->first)
                    </a>
                    @endif
                @endforeach
                <td style="display:none;"></td>
                </tr>
		@endforeach
    </table>
	</div>
    @endif
</div>
@endsection



@section('scripts')
@parent
<script>
$(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'asc' ]],
    pageLength: 100, stateSave: true, stateSave: true,
    "lengthMenu": [ 10, 50, 100, 500 ],
  });

  @php
      $curModel = null;
  @endphp
  @foreach($searchableData as $item)
      @if ($curModel!==$item['model'])
        let table_{{ $item['model'] }} = $('.datatable-{{ $item['model'] }}:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        @php
            $curModel = $item['model'];
        @endphp
        @endif
  @endforeach

})

</script>
@endsection
