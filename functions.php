<?php

function getTop(String $path): ?array {
    if (file_exists($path)) {
        return json_decode(file_get_contents($path), true);
    }
}


function show($data, $die = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    if ($die) {
        die;
    }
}