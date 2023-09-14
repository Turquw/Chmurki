<?php
require_once('../config.php');
$template=settemplate('template_admin.html');
$modifiedTemplate = addmodule($template, '$TEMPLATE', 'science_club.html');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "kutas";
    $sciezka = '../image/logo/';
    $sciezka2 = '../image/bacground/';
    $full_name=safelyGetFormData("full_name");
    $short_name=safelyGetFormData("short_name");
    $info_basic=safelyGetFormData("info_basic");
    $email=safelyGetFormData("email");
    $people=safelyGetFormData("people");
    $collage=safelyGetFormData("collage");
    $facebook=safelyGetFormData("facebook");
    $instagram=safelyGetFormData("instagram");
    $short_desc=safelyGetFormData("short_desc");
    $web=safelyGetFormData("web");
    $field=safelyGetFormData("field");
    $key=safelyGetFormData("key");
    if(isset($_POST['recruitment'])&&$_POST['recruitment']==='Yes')
    {
        $recruitment="tak";
    }
    else
    {
        $recruitment="nie";
    }
    $proiect_basic=safelyGetFormData("proiect_basic");
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



    $query = "INSERT INTO `science_club`(`name`, `full_name`, `people`, `mail`, `management`, `facebook`, `instagram`, `website`, `description`, `project`, `university`,`logo`, `background`,`recruitment`,`field`,`hashtags`,`short_desc`) VALUES ('$short_name', '$full_name', $people, '$email', '$membersJSON', '$facebook', '$instagram', '$web', '$info_basic', '$proiect_basic', '$collage','$nazwaPliku1','$nazwaPliku2','$recruitment','$field','$key','$short_desc')";

    $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', $query);
    if ($mysqli->query($query)) {

        $mysqli->commit();
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', '<div class="debug" id="debug">Dodano Poprawnie <i class="fa-solid fa-x" id="close_debug"></i></div>');

    } else {

        $mysqli->rollback();

    }
}
else
{
    $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', '');
}


echo $modifiedTemplate;
