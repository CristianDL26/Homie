<?php

function fetchAPIResults($input) {
    $apiUrl = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=".urlencode($input)."&language=it&components=country:it&key=AIzaSyD8NKq8Y7ZWcdnprjHHsH153OrNT3HyVmk";

    $response = file_get_contents($apiUrl);

    if ($response !== false) {
        $results = json_decode($response, true);

        return $results;
    } else {
        return false;
    }
}

$input = $_POST['search']; 
$results = fetchAPIResults($input);


if ($results !== false) {
    // Display the results
    foreach ($results as $result) {
        echo $result['name'] . "<br>";
    }
} else {
    echo "Error fetching API results.";
}

?>