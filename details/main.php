<?php
require_once('../config.php');
$template=settemplate('../template/template.html');
$modifiedTemplate = addmodule($template, '$TEMPLATE', 'index.html');
$id = $_GET['id'];
$query = "SELECT `name`, `full_name`, `people`, `mail`, `management`, `facebook`, `instagram`, `website`, `description`, `project`, `university`, `logo`, `background`, `hashtags`, `recruitment`, `field`, `short_desc` FROM `science_club` WHERE `id_science_clube`=$id";
$result = querySelect($mysqli, $query);

if ($result !== false) {
    foreach ($result as $row) {
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$name', $row['name']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$full_name', $row['full_name']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$people', $row['people']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$email', $row['mail']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$facebook', $row['facebook']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$instagram', $row['instagram']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info_basic', html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8'));
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$collage', $row['university']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$key', $row['hashtags']);
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$proiect_basic', html_entity_decode($row['project'], ENT_QUOTES, 'UTF-8'));
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
                $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$menagment',
                     '<label>'.$member['role'] . ' ' . $member['name'] . '</label>$menagment');

            }
        }
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$menagment',
            '');


    }
    $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$info', '');
} else {
    echo $query;

}
echo $modifiedTemplate;

