<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ---------------------------------URL of the page containing the table

require_once '../config.php';
// login


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = [];

    if ($_POST['action'] === "submit") {

        $username = 'xadamko';
        $password = 'Tms19Ad.mk06';
        $aisId = '115043';

        // init curl
        $ch = curl_init();
        // URL adresa stránky s osobným rozvrhom AIS
        $url = "https://is.stuba.sk/auth/katalog/rozvrhy_view.pl?rozvrh_student_obec=1&zobraz=1&format=list&rozvrh_student=" . $aisId . ';lang=sk';

        // Inicializácia CURL
        $ch = curl_init();

        // Nastavenie CURL možností
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Set the username and password
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        // Set the form fields required for login
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'destination' => '/auth/?lang=sk',
            'credential_0' => $username,
            'credential_1' => $password,
            'login' => 'Prihlásiť sa',
            'credential_2' => '',
            'credential_cookie' => '1',
        ]));

        // Handle cookies
        curl_setopt($ch, CURLOPT_COOKIEJAR, './cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, './cookies.txt');

        // If you need to set custom headers, define the $headers array with the appropriate header values
        $headers = array(
            'Content-Type: application/xml',
            // Add more headers if needed
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Vykonanie CURL požiadavky
        $response = curl_exec($ch);

        // Get the request header
        $requestHeader = curl_getinfo($ch, CURLINFO_HEADER_OUT);

        // Kontrola HTTP kódu odpovede
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200) {
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($response, 'HTML-ENTITIES', 'UTF-8'));

            $table = $dom->getElementById('tmtab_1');

            $rows = $table->getElementsByTagName('tr');

            // Iterate through rows
            foreach ($rows as $row) {
                $cells = $row->getElementsByTagName('td');
                $rowData = [];
                // Iterate through cells
                foreach ($cells as $cell) {
                    $rowData[] = $cell->nodeValue;
                }
                $data[] = $rowData;
            }
            array_shift($data);

            // Convert data to JSON format
            // $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            // // Output JSON data
            // // Get JSON data from the request body
            // $json = file_get_contents('php://input');
            // $data = json_decode($json, true);

        } else {
            echo "Chyba: Nepodarilo sa získať údaje z AIS. HTTP kód: " . $httpCode;
        }
        // Uzatvorenie CURL spojenia
        curl_close($ch);

        // PRIDANIE DAT DO DB
        try {
            foreach ($data as $dataRow) {
                $stmt = $pdo->prepare("INSERT INTO `timetable`(`day`, `time_from`, `time_to`, `subject`, `action`, `room`, `teacher`) 
                VALUES (:day, :time_from, :time_to, :subject, :action, :room, :teacher)");

                $stmt->bindParam(':day', $dataRow[0]);
                $stmt->bindParam(':time_from', $dataRow[1]);
                $stmt->bindParam(':time_to', $dataRow[2]);
                $stmt->bindParam(':subject', $dataRow[3]);
                $stmt->bindParam(':action', $dataRow[4]);
                $stmt->bindParam(':room', $dataRow[5]);
                $stmt->bindParam(':teacher', $dataRow[6]);

                $stmt->execute();
                unset($stmt);
            }

            echo "Dáta boli uložené!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }
    // VYMAZANIE DAT Z DB
    if ($_POST['action'] === "delete") {
        try {
            $stmt = $pdo->prepare("DELETE FROM timetable");
            $stmt->execute();
            unset($stmt);
            $stmt = $pdo->prepare("ALTER TABLE timetable AUTO_INCREMENT = 1");
            $stmt->execute();
            unset($stmt);
            echo "Dáta boli vymazané!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

