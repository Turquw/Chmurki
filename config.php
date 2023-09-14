<?php
session_start();

function setSessionVariable($name, $value) {
    $_SESSION[$name] = $value;
}

function getSessionVariable($name, $default = null) {
    if (isset($_SESSION[$name])) {
        return $_SESSION[$name];
    } else {
        return $default;
    }
}


function destroySession() {
    session_destroy();
}
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'chmury';

$mysqli = new mysqli($host, $username, $password, $database);


if ($mysqli->connect_error) {
    die("Błąd połączenia z bazą danych: " . $mysqli->connect_error);
}


$mysqli->set_charset("utf8");


function querySelect($mysqli, $query) {

    if (!is_string($query) || empty($query)) {
        return false;
    }
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        return false;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    } else {
        return false;
    }
    $stmt->close();
}
function executeQuery($mysqli, $query, $params = array())
{

    if (!is_string($query) || empty($query)) {
        return false;
    }

    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        return false;
    }

    if (!empty($params)) {
        $types = '';
        $bindParams = array();

        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $bindParams[] = $param;
        }

        array_unshift($bindParams, $types);

        call_user_func_array(array($stmt, 'bind_param'), $bindParams);
    }

    return $stmt->execute();
}
function settemplate($templateFile) {

    if (!file_exists($templateFile)) {
        die("Plik szablonu nie istnieje: $templateFile");
    }


    $template = file_get_contents($templateFile);


    return $template;
}

function addmodule($template, $targetString, $htmlFilePath) {
    // Sprawdź, czy plik HTML istnieje
    if (!file_exists($htmlFilePath)) {
        die("Plik HTML nie istnieje: $htmlFilePath");
    }

    $insertHtml = file_get_contents($htmlFilePath);


    $pos = strpos($template, $targetString);


    if ($pos === false) {
        return $template;
    }


    $modifiedTemplate = substr_replace($template, $insertHtml, $pos, strlen($targetString));

    return $modifiedTemplate;
}
function safelyGetFormData($inputName) {
    if (isset($_POST[$inputName])) {
        $value = trim($_POST[$inputName]);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        return $value;
    } else {
        return "";
    }
}
function zapiszObraz($nazwaPliku, $sciezkaDocelowa) {
    // Sprawdź, czy plik został przesłany bez błędów
    if (move_uploaded_file($nazwaPliku, $sciezkaDocelowa)) {
        return true;
    } else {
        return false;
    }
}

function zapiszNazwePlikuWBazieDanych($nazwaPliku, $tabela, $kolumna, $mysqli) {

    $zapytanie = "INSERT INTO $tabela ($kolumna) VALUES (?)";


    $stmt = $mysqli->prepare($zapytanie);
    $stmt->bind_param("s", $nazwaPliku);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
function insertHtmlAtPosition($template, $targetString, $insertHtml) {

    $pos = strpos($template, $targetString);

    if ($pos === false) {
        return $template;
    }

    $modifiedTemplate = substr_replace($template, $insertHtml, $pos, strlen($targetString));

    return $modifiedTemplate;
}
function splitString($inputString) {
    $words = explode(" ", $inputString);
    return $words;
}
