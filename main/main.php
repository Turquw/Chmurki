<?php
require_once('../config.php');
$template=settemplate('../template/template.html');
$modifiedTemplate = addmodule($template, '$TEMPLATE', 'main.html');
echo $modifiedTemplate;