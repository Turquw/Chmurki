<?php
require_once('../config.php');
$template = settemplate('template_admin.html');
$modifiedTemplate = addmodule($template, '$TEMPLATE', 'list_science.html');




$query_university = "SELECT DISTINCT `university`,`field` FROM `science_club`";
$result_university = querySelect($mysqli, $query_university);
$modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$collage_options', '
        <option value="">Dowolna</option>$collage_options');
$modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$field', '
        <option value="">Dowolna</option>$field');

    foreach ($result_university as $row_university ) {

        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$collage_options', '
        <option value="'.$row_university['university'].'">'.$row_university['university'].'</option>$collage_options');
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$field', '
        <option value="'.$row_university['field'].'">'.$row_university['field'].'</option>$field');
    }
$modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$collage_options', '');
$modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$field', '');








$query = "SELECT * FROM `science_club`";
$result = querySelect($mysqli, $query);

if ($result !== false) {
    foreach ($result as $row) {


        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$resoult', '<div class="resoult">
    <div class="skn">
   
        <img src="../image/logo/'.$row['logo'].'">
        <div class="right-info">
            <label class="title_skn">
                '.$row['name'].'
            </label>
            <label class="small_title">
                <a>'.$row['university'].'</a><a> '.$row['field'].'</a> $hashtags
            </label>
            <label class="description">
               '.html_entity_decode($row['short_desc'], ENT_QUOTES, 'UTF-8').'
            </label>
        </div>
        
     <a href="edit.php?id='.$row['id_science_clube'].'" class="button_class">Zobacz więcej</a>
       
    </div>

</div>$resoult');

        $wordArray = splitString($row['hashtags']);

        foreach ($wordArray as $word) {
            $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate,'$hashtags','<a>'.$word.'</a> $hashtags');
        }
        $modifiedTemplate = insertHtmlAtPosition($modifiedTemplate,'$hashtags','');
    }
}
$modifiedTemplate = insertHtmlAtPosition($modifiedTemplate, '$resoult','');
echo $modifiedTemplate;