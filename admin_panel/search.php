<?php
require_once('../config.php');


$collage = $_POST['collage'];
$field = $_POST['field'];
$searchInput = $_POST['search_input'];
$recruitment = $_POST['recruitment'];


$query = "SELECT * FROM `science_club` 
          WHERE 
          (`university` LIKE '%$searchInput%' OR 
           `full_name` LIKE '%$searchInput%' OR
           `name` LIKE '%$searchInput%' OR 
           `hashtags` LIKE '%$searchInput%') 
          AND 
          `field` LIKE '%$field%' 
          AND 
          `recruitment` LIKE '%$recruitment%'"; // Dodaj przeszukiwanie na podstawie inputa "search_input"


$result = $mysqli->query($query);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $wordArray = splitString($row['hashtags']);
        $word_has='';
        foreach ($wordArray as $word) {
           $word_has=$word_has.'<a>'.$word.' </a>';
        }
     echo '<div class="resoult">
    <div class="skn">
   
        <img src="../image/logo/'.$row['logo'].'">
        <div class="right-info">
            <label class="title_skn">
                '.$row['name'].'
            </label>
            <label class="small_title">
                <a>'.$row['university'].'</a><a> '.$row['field'].' </a>'.$word_has.'
            </label>
            <label class="description">
               '.html_entity_decode($row['short_desc'], ENT_QUOTES, 'UTF-8').'
            </label>
        </div>
        
     <a href="edit.php?id='.$row['id_science_clube'].'" class="button_class">Zobacz więcej</a>
       
    </div>

</div>';



    }

} else {
    echo "<label class='none'>Brak wyników.</label>";
}


$mysqli->close();
?>
