$(document).ready(function () {
    document.getElementById("page-length").addEventListener("input", handleTableRows);
    document.getElementById("find-thesis").addEventListener("input", findThesis);

    table = $('#myTable').DataTable({
        responsive: true,
        scrollX: true,
        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: null,
            bottomEnd: 'paging'
        }
    });

    table.columns(3).visible(false);

    function handleTableRows(e) {
        table.page.len(e.target.value).draw();
    }

    function findThesis(e) {
        table.search(e.target.value).draw();
    }

    $('#myTable tbody').on('click', 'tr', function () {
        let rowData = table.row(this).data();
        console.log(rowData);

        $('.modal-data').html("<p>" + rowData[3] + "</p>");
        $('#thesis-name-modal').html("Abstrakt pre " + rowData[0]);
        $('#modal2').removeClass('hidden');

    });
    let btnModalClose = document.getElementById("close-modal");
    let modal = document.getElementById("modal2");
    window.onclick = function(event) {
        if (event.target == modal || event.target == btnModalClose) {
            $('#modal2').addClass("hidden");
        }

    }
});