<?php
require_once('../config.php');
$template = settemplate('template_admin.html');
$modifiedTemplate = addmodule($template, '$TEMPLATE', 'science_club_edit.html');
$id = $_GET['id'];




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sciezka = '../image/logo/';
    $sciezka2 = '../image/bacground/';
    $full_name = safelyGetFormData("full_name");
    $short_name = safelyGetFormData("short_name");
    $info_basic = safelyGetFormData("info_basic");
    $email = safelyGetFormData("email");
    $people = safelyGetFormData("people");
    $collage = safelyGetFormData("collage");
    $facebook = safelyGetFormData("facebook");
    $instagram = safelyGetFormData("instagram");
    $web = safelyGetFormData("web");
    $short_desc = safelyGetFormData("short_desc");
    $field = safelyGetFormData("field");
    $key = safelyGetFormData("key");
    if (isset($_POST['recruitment']) && $_POST['recruitment'] === 'Yes') {
        $recruitment = "tak";
    } else {
        $recruitment = "nie";
    }
    $proiect_basic = safelyGetFormData("proiect_basic");
    $membersData = $_POST['membersData'];
    $membersArray = json_decode($membersData, true);
    $membersJSON = json_encode($membersArray);

    $mysqli->begin_transaction();

    $nazwaPliku1 = "";
    $nazwaPliku2 = "";

    // Generuj unikalne nazwy plików
    $unikalnyId1 = uniqid();
    $unikalnyId2 = uniqid();

    // Obsługa pierwszego obrazka
    $nazwaPliku1 = "";
    $nazwaPliku2 = "";

    // Generuj unikalne nazwy plików
    $unikalnyId1 = uniqid();
    $unikalnyId2 = uniqid();

// Obsługa pierwszego obrazka

    // Pobierz istniejące dane z bazy danych na podstawie $id


    $id = 39;
    $querys = "SELECT * FROM `science_club` WHERE `id_science_clube`=$id";
    $results = querySelect($mysqli, $querys);

    if ($results !== false) {
        foreach ($results as $rows) {
            if (isset($_FILES['background']) && $_FILES['background']['error'] === UPLOAD_ERR_OK) {
                if (!empty($rows['background'])) {
                    $oldBackgroundPath = '../image/bacground/' . $rows['background'];
                    if (file_exists($oldBackgroundPath)) {
                        unlink($oldBackgroundPath);
                    }
                }
            }
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                if (!empty($rows['logo'])) {
                    $oldLogoPath = '../image/logo/' . $rows['logo'];
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }
            }
        }

// Obsługa pierwszego obrazka
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $rozszerzenie1 = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $nazwaPliku1 = $unikalnyId1 . "." . $rozszerzenie1;
            $sciezkaDocelowa1 = $sciezka . $nazwaPliku1;

            if (!zapiszObraz($_FILES['logo']['tmp_name'], $sciezkaDocelowa1)) {
                $mysqli->rollback();

                $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', '<div class="debug" id="debug">Bład w wysyłaniu loga<i class="fa-solid fa-x" id="close_debug"></i></div>');
                return;
            }
        }
        else
        {
            $nazwaPliku1=$rows['logo'];
        }

        if (isset($_FILES['background']) && $_FILES['background']['error'] === UPLOAD_ERR_OK) {
            $rozszerzenie2 = pathinfo($_FILES['background']['name'], PATHINFO_EXTENSION);
            $nazwaPliku2 = $unikalnyId2 . "." . $rozszerzenie2;
            $sciezkaDocelowa2 = $sciezka2 . $nazwaPliku2;

            if (!zapiszObraz($_FILES['background']['tmp_name'], $sciezkaDocelowa2)) {
                $mysqli->rollback();
                $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', '<div class="debug" id="debug">Bład podczas wysyłania zdjecia w tle <i class="fa-solid fa-x" id="close_debug"></i></div>');
                return;
            }
        }
        else
        {
            $nazwaPliku2=$rows['background'];
        }


        $query = "UPDATE `science_club` SET `name`='$short_name', `full_name`='$full_name', `people`='$people', `mail`='$email', `management`='$membersJSON', `facebook`='$facebook', `instagram`='$instagram', `website`='$web', `description`='$info_basic', `project`='$proiect_basic', `university`='$collage', `logo`='$nazwaPliku1', `background`='$nazwaPliku2', `recruitment`='$recruitment', `field`='$field', `hashtags`='$key',`short_desc`='$short_desc' WHERE `id_science_clube` = '$id'";

        if ($mysqli->query($query)) {

            $mysqli->commit();
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', '<div class="debug" id="debug">Dodano Poprawnie <i class="fa-solid fa-x" id="close_debug"></i></div>');

        } else {

            $mysqli->rollback();

        }
    }
}

    $query = "SELECT `name`, `full_name`, `people`, `mail`, `management`, `facebook`, `instagram`, `website`, `description`, `project`, `university`, `logo`, `background`, `hashtags`, `recruitment`, `field`, `short_desc` FROM `science_club` WHERE `id_science_clube`=$id";
    $result = querySelect($mysqli, $query);

    if ($result !== false) {
        foreach ($result as $row) {
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$short_name', $row['name']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$full_name', $row['full_name']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$people', $row['people']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$email', $row['mail']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$facebook', $row['facebook']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$instagram', $row['instagram']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info_basic', $row['description']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$collage', $row['university']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$key', $row['hashtags']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$proiect_basic', $row['project']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$web', $row['website']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$filed', $row['field']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$logo_path', $row['logo']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$background_path', $row['background']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$logo_path', $row['logo']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$background_path', $row['background']);
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$short_desc', $row['short_desc']);
            if ($row['recruitment'] === 'tak') {
                $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$recruitment', 'checked');
            } else {
                $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$recruitment', '');
            }

            $membersData = json_decode($row['management'], true);
            if ($membersData !== null && is_array($membersData)) {
                foreach ($membersData as $member) {

                    $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$members',
                        '
        <script>
        addMember_base("' . $member['role'] . '","' . $member['name'] . '");
        </script>$members
        
        ');

                }
            }
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$members',
                '');


        }
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', '');
    } else {
        echo $query;

    }

// Zamykanie połączenia z bazą danych


echo $modifiedTemplate;
