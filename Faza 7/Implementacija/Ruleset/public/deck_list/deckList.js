$(document).ready(function() {
    $('#deckTable').DataTable();

    $('#deckTable tbody').on('click', 'tr', function () {
        //var data = table.row( this ).data();
        window.location = $(this).data("href");
    } );
} );