<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'curlHelper.php';

$apidata = [
    'response' => "",
    'http_code' => ""
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $departmentId = $_POST['select-department'];
    $thesisType = $_POST['select-type'];
    
    $apiBaseUrl = 'https://node10.webte.fei.stuba.sk/harenec2/api';
    
    $response = sendRequest($apiBaseUrl . "/api_thesis.php/theses/".$thesisType."/".$departmentId, 'GET');
    
    $apidata = json_decode($response, true);
}


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
                    <a href="theses.php">Práce</a>
                </li>
            </ul>
        </nav>
        <form id="fetch" action="getRowData.php" method="POST" style="display: none;">
            <input type="hidden" name="name" id="input-name">
        </form>
    </div>
    <main class="container">
        <h1>Zoznam záverečných prác.</h1>
        <div class="table-nav">
            <form id="form-department" class="in-row" method="post"
                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                
                <div class="form-input">
                    <select name="select-department" id="select-department">
                        <option value="0">Vyber ústav</option>
                        <option value="642">Ustav automobilovej mechatroniky</option>
                        <option value="548">Ustav elektroenergetiky a aplikovanej elektrotechniky</option>
                        <option value="549">Ustav elektroniky a fotoniky</option>
                        <option value="550">Ustav elektrotechniky</option>
                        <option value="816">Ustav informatiky a matematiky</option>
                        <option value="817">Ustav jadrového a fyzikálneho inžinierstva</option>
                        <option value="818">Ustav multimediálnych informačných a komunikačných technológií</option>
                        <option value="356">Ustav robotiky a kybernetiky</option>
                    </select>
                </div>
                <div class="form-input">
                    <select name="select-type" id="select-type">
                        <option value="0">Vyber typ práce</option>
                        <option value="BP">Bakalárska práca</option>
                        <option value="DP">Diplomová práca</option>
                        <option value="DizP">Dizertačná práca</option>
                    </select>
                </div>

                <div class="form-input">
                    <button id="department-submit-btn" class="btn-compact" type="submit">Hľadaj</button>
                </div>

            </form>

            <div class="table-selector">
                <input type="text" name="find-thesis" id="find-thesis" placeholder="Hľadaj prácu">
            </div>

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

        </div>

        <table id="myTable" class="table table-striped table-hover" width="100%">
            <thead>
                <tr>
                    <th>Názov témy</th>
                    <th>Vedúci práce</th>
                    <th>Program</th>
                    <th>Abstrakt</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (strlen($apidata['response']) > 0) {
                        $tableData = json_decode($apidata['response'], true);
                        foreach ($tableData as $row) {
                            echo "<tr>";
                            echo '
                            <td>'.$row['topic'].'</td>
                            <td>'.$row['supervisor'].'</td>
                            <td>'.$row['programme'].'</td>
                            <td>'.$row['abstract'].'</td>
                            ';
                            echo "</tr>";
                        }
                        
                    }
                ?>
            </tbody>
        </table>
    </main>

    <div id="modal2" class="modal modal2 hidden">
        <div class="info-modal">
            <h2 id="thesis-name-modal">Abstrakt pre </h2>
            <div class="modal-data">
                
            </div>
            <img id="close-modal" src="images/close-icon.svg" alt="close">
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script src="js/thesesData.js"></script>
</body>

</html>