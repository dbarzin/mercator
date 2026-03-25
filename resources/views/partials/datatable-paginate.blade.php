document.addEventListener("DOMContentLoaded", function () {

    let table = $('{{ $id }}').DataTable({
        // 👉 plus de pagination/search côté DataTables
        paging: false,
        searching: false,
        info: false,
        stateSave: false, // désactive pour éviter qu'il garde un vieux pageLength

        responsive: true,
        colReorder: true,
        autoWidth: true,

        columnDefs: [
            {
                targets: 0,
                orderable: false,
                render: DataTable.render.select(),
            },
            {
                targets: -1,
            }
        ],

        // IMPORTANT : pas de pageLength, pas de lengthMenu ici
        // pageLength: 100,
        // lengthMenu: [...],

        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: null,
            bottomEnd: null,
        },

        @if (isset($order))
        order: {!! $order !!},
        @else
        order: [[1, 'asc']],
        @endif

        buttons: [
            // tes boutons colvis / copy / csv / excel / pdf / print…
        ],
    });

    table
        .buttons(0, null)
        .container()
        .prependTo(table.table().container());
});
