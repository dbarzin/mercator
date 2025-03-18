document.addEventListener("DOMContentLoaded", function () {

table = $('{{ $id }}').DataTable({
        keys: true,
        stateSave: true,
        responsive: true,
        colReorder: true,
		autoWidth: true,
        columnDefs: [
            // Première colonne -> sélections
            {
                targets: 0,
                orderable: false,
                render: DataTable.render.select(),
            },
            // Dernière colonne allignée à droite
            {
                targets: -1,
                className: 'dt-body-right'
            }
        ],
        layout:
        {
    		paging: true,
            keys: {
                columns: ':not(:first-child)',
            },
        },

        select: {
            style: 'os',
            selector: 'td:first-child',
            headerCheckbox: 'select-page',
            items: 'row'
        },

        @if (isset($order))
        order: {!! $order !!},
        @else
        order: [[1, 'asc']],
        @endif
        pageLength: 100,

        buttons: [
            {
              extend: 'colvis',
              columns: ':not(:first-child):not(:last-child)'
            },
            {
              extend: 'copy',
              className: 'btn-default',
              exportOptions: {
                columns: ':not(:first-child):not(:last-child)'
              }
          },
            {
              extend: 'csv',
              title: "Mercator - {{ $title }} - {{ Carbon\Carbon::today()->format('Ymd') }}",
              className: 'btn-default',
              exportOptions: {
                columns: ':not(:first-child):not(:last-child)'
              }
          },
            {
              extend: 'excel',
              title: "Mercator - {{ $title }} - {{ Carbon\Carbon::today()->format('Ymd') }}",
              className: 'btn-default',
              exportOptions: {
                  columns: ':not(:first-child):not(:last-child)'
              }
          },
            {
              extend: 'pdf',
              test: 'PDF',
              title: "Mercator - {{ $title }} - {{ Carbon\Carbon::today()->format('Ymd') }}",
              className: 'btn-default',
              exportOptions: {
                columns: ':not(:first-child):not(:last-child)'
              }
          },
            {
              extend: 'print',
              title: "Mercator - {{ $title }} - {{ Carbon\Carbon::today()->format('Ymd') }}",
              className: 'btn-default',
              exportOptions: {
                columns: ':not(:first-child):not(:last-child)'
              }
          },
          @if ($canDelete)
          {
                text: "{{ trans('global.datatables.delete') }}",
                className: 'btn-danger',
                action:
                    function (e, dt, node, config) {
                      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                          return $(entry).data('entry-id')
                      });

                      if (ids.length === 0) {
                        alert("{{ trans('global.datatables.zero_selected') }}")
                      }

                      else if (confirm("{{ trans('global.areYouSure') }}")) {
                        $.ajax({
                          method: 'POST',
                          headers: {'x-csrf-token': _token},
                          url: "{{ $URL }}",
                          data: { ids: ids, _method: 'DELETE' }})
                          .done(function ()
                              { location.reload() })
                        }
                    }
                }
            @endif
            ],
        }
    );
    table
        .buttons(0, null)
        .container()
        .prependTo(table.table().container());
});
