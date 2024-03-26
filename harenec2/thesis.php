<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once '../config.php';
?>

<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIS Bakalárske práce</title>
    <link rel="icon" type="image/x-icon" href="images/dawg.png">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <div class="container">
        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php">Rozvrh</a>
                </li>
                <li class="nav-item">
                    <a href="thesis.php">Bakalárky</a>
                </li>
            </ul>
        </nav>
        <form id="fetch" action="getRowData.php" method="POST" style="display: none;">
            <input type="hidden" name="name" id="input-name">
        </form>
    </div>
    <main class="container">
        <h1>Zoznam bakalárskych prác.</h1>
        <div class="table-nav">
            <div class="table-selector">
                <h4>Počet záznamov na stránku:</h4>
                <select name="page-length" id="page-length">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="-1">Všetky</option>
                </select>
            </div>

            <div class="table-selector">
                <h4>Filter podľa roku:</h4>
                <select name="filter-year" id="filter-year">
                    <option value="">Vyberte rok</option>
                    <?php
                    // $sql = "SELECT DISTINCT year FROM prizes";
                    // $stmt = $pdo->query($sql);

                    // // Fetch and loop through the data to generate options
                    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    //     echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
                    // }
                    // unset($stmt);
                    ?>
                </select>
            </div>
        </div>

        <table id="myTable-thesis" class="table table-striped table-hover" width="100%">
            <thead>
                <tr>
                    <th>Deň</th>
                    <th>Od</th>
                    <th>Do</th>
                    <th>Predmet</th>
                    <th>Akcia</th>
                    <th>Miestnosť</th>
                    <th>Vyučujúci</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </main>

    <div id="modal2" class="modal modal2 hidden">
        <div class="info-modal">
            <h2>Laureát.</h2>
            <div class="modal-data">

            </div>
            <img id="close-modal" src="images/close-icon.svg" alt="close">
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <!-- <script src="js/tableData.js"></script> -->
</body>

</html>