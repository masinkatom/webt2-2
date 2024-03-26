
$(document).ready(function () {
    document.getElementById("page-length").addEventListener("input", handleTableRows);
    document.getElementById("filter-year").addEventListener("input", filterYearRows);
    document.getElementById("add-timetable-data").addEventListener("click", submitTimetable);
    document.getElementById("delete-timetable-data").addEventListener("click", deleteTimetable);
    document.getElementById("refresh-timetable-data").addEventListener("click", refreshData);
    
    var table;

    function refreshData() {
        fetch('./getAisTimetable.php')
            .then(response => response.json()) // Parse the response as text
            .then(data => {
                console.log("Data received from server:", data); // Log the parsed JSON data

                // Initialize DataTables with the fetched data
                table = $('#myTable').DataTable({
                    responsive: true,
                    data: data,
                    scrollX: true,
                    layout: {
                        topStart: null,
                        topEnd: null,
                        bottomStart: null,
                        bottomEnd: 'paging'
                    }
                });


            }) // Closing parenthesis for the then method
            .catch(error => {
                console.error('Error fetching data:', error);
            }
        );
    }


    function submitTimetable() {
        var data = $("#data").val(); // Get the value of the input field
        $.ajax({
            type: "POST",
            url: "setAisTimetable.php",
            data: { 
                action: "submit" 
            }, // Send data to the server
            success: function (response) {
                alert(response);
                // You can perform additional actions after successful submission
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error occurred while submitting data.");
            }
        });
    }

    function deleteTimetable() {
        var data = $("#data").val(); // Get the value of the input field
        $.ajax({
            type: "POST",
            url: "setAisTimetable.php",
            data: { 
                action: "delete" 
            }, // Send data to the server
            success: function (response) {
                alert(response);
                // You can perform additional actions after successful submission
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error occurred while submitting data.");
            }
        });
    }

    function handleTableRows(e) {
        table.page.len(e.target.value).draw();
    }

    function filterYearRows(e) {
        table.column(0).search(e.target.value).draw();
    }


});