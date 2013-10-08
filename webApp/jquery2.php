<?php
    $advert = array(
        'ajax' => 'Hello world!',
        'advert' => $row['adverts'],
     );
    echo json_encode($advert);
?>