<?php
class Thesis
{
    public function __construct()
    {
    }
    public function getAllTheses($departmentId, $thesisType)
    {
        $data = [];

        // init curl
        $ch = curl_init();
        // URL adresa stránky s osobným rozvrhom AIS
        $url = "https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=" . $departmentId;

        // Nastavenie CURL možností
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);


        // If you need to set custom headers, define the $headers array with the appropriate header values
        $headers = array(
            'Content-Type: application/xml',
            // Add more headers if needed
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Vykonanie CURL požiadavky
        $response = curl_exec($ch);

        // Kontrola HTTP kódu odpovede
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200) {
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($response, 'HTML-ENTITIES', 'UTF-8'));

            $form = $dom->getElementsByTagName('form')->item(0);
            $table = $form->getElementsByTagName('table')->item(3);
            $rows = $table->getElementsByTagName('tr');

            // Iterate through rows
            foreach ($rows as $row) {
                $cells = $row->getElementsByTagName('td');
                $rowData = [];
                if ($cells->length > 0) {
                    if ($cells[1]->nodeValue === $thesisType) {
                        if ($cells->length == 11) {
                            $rowData = [
                                "id" => rtrim($cells[0]->nodeValue, "."),
                                "type" => $cells[1]->nodeValue,
                                "topic" => $cells[2]->nodeValue,
                                "supervisor" => $cells[3]->nodeValue,
                                "department" => $cells[4]->nodeValue,
                                "programme" => $cells[5]->nodeValue,
                                "track" => $cells[6]->nodeValue,
                                "abstract" => $this->getAbstract("https://is.stuba.sk" . $cells[8]->firstChild->getAttribute('href'))
                            ];
                        } elseif ($cells->length == 10) {
                            $rowData = [
                                "id" => rtrim($cells[0]->nodeValue, "."),
                                "type" => $cells[1]->nodeValue,
                                "topic" => $cells[2]->nodeValue,
                                "supervisor" => $cells[3]->nodeValue,
                                "department" => $cells[4]->nodeValue,
                                "programme" => $cells[5]->nodeValue,
                                "track" => "",
                                "abstract" => $this->getAbstract("https://is.stuba.sk" . $cells[7]->firstChild->getAttribute('href'))
                            ];
                        }
                        $data[] = $rowData;

                    }
                }
            }

        } else {
            echo "Chyba: Nepodarilo sa získať údaje z AIS. HTTP kód: " . $httpCode;
        }
        // Uzatvorenie CURL spojenia
        curl_close($ch);
        return $data;
    }
    public function getAllFreeTheses($departmentId, $thesisType)
    {
        $data = [];

        // init curl
        $ch = curl_init();
        // URL adresa stránky s osobným rozvrhom AIS
        $url = "https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=" . $departmentId;

        // Nastavenie CURL možností
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // If you need to set custom headers, define the $headers array with the appropriate header values
        $headers = array(
            'Content-Type: application/xml',
            // Add more headers if needed
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Vykonanie CURL požiadavky
        $response = curl_exec($ch);

        // Kontrola HTTP kódu odpovede
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200) {
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($response, 'HTML-ENTITIES', 'UTF-8'));

            $form = $dom->getElementsByTagName('form')->item(0);
            $table = $form->getElementsByTagName('table')->item(3);
            $rows = $table->getElementsByTagName('tr');

            // Iterate through rows
            foreach ($rows as $row) {
                $cells = $row->getElementsByTagName('td');
                $rowData = [];
                if ($cells->length > 0 && $this->checkForFree($cells[$cells->length - 2]->nodeValue)) {
                    if ($cells[1]->nodeValue === $thesisType) {
                        if ($cells->length == 11) {
                            $rowData = [
                                "id" => rtrim($cells[0]->nodeValue, "."),
                                "type" => $cells[1]->nodeValue,
                                "topic" => $cells[2]->nodeValue,
                                "supervisor" => $cells[3]->nodeValue,
                                "department" => $cells[4]->nodeValue,
                                "programme" => $cells[5]->nodeValue,
                                "track" => $cells[6]->nodeValue,
                                "abstract" => $this->getAbstract("https://is.stuba.sk" . $cells[8]->firstChild->getAttribute('href'))
                            ];
                        } elseif ($cells->length == 10) {
                            $rowData = [
                                "id" => rtrim($cells[0]->nodeValue, "."),
                                "type" => $cells[1]->nodeValue,
                                "topic" => $cells[2]->nodeValue,
                                "supervisor" => $cells[3]->nodeValue,
                                "department" => $cells[4]->nodeValue,
                                "programme" => $cells[5]->nodeValue,
                                "track" => "",
                                "abstract" => $this->getAbstract("https://is.stuba.sk" . $cells[7]->firstChild->getAttribute('href'))
                            ];
                        }
                        $data[] = $rowData;

                    }
                }
            }

        } else {
            echo "Chyba: Nepodarilo sa získať údaje z AIS. HTTP kód: " . $httpCode;
        }
        // Uzatvorenie CURL spojenia
        curl_close($ch);
        return $data;
    }
    public function addThesis($data)
    {

    }
    public function updateThesis($id, $data)
    {

    }
    public function deleteThesis($id)
    {

    }

    private function getAbstract($url)
    {

        // Inicializácia CURL
        $ch = curl_init();

        // Nastavenie CURL možností
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // If you need to set custom headers, define the $headers array with the appropriate header values
        $headers = array(
            'Content-Type: application/xml',
            // Add more headers if needed
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Vykonanie CURL požiadavky
        $response = curl_exec($ch);

        $abstract = "";

        // Kontrola HTTP kódu odpovede
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200) {
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($response, 'HTML-ENTITIES', 'UTF-8'));

            $table = $dom->getElementsByTagName('table')->item(0);
            $rows = $table->getElementsByTagName('tr')->item(10);
            $abstract = $rows->getElementsByTagName('td')->item(1)->nodeValue;


        } else {
            echo "Chyba: Nepodarilo sa získať údaje z AIS. HTTP kód: " . $httpCode;
        }
        // Uzatvorenie CURL spojenia
        curl_close($ch);
        return $abstract;
    }

    private function checkForFree($input)
    {
        $participants = explode("/", trim($input));
        $participants[0] = trim($participants[0]);
        $participants[1] = trim($participants[1]);
        if ($participants[1] == "--") {
            return true;
        } else if ($participants[0] < $participants[1]) {
            return true;
        }
        return false;
    }
}
