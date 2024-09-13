<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

function downloadProfessionals()
{
    include 'db_connection.php';
    header('Content-Type: application/json');

    $query = "
        SELECT 
            p.piva, p.nome, p.cognome, p.professione, p.indirizzo, p.prezzo_orario, p.prezzo_chiamata, p.is_active, 
            COALESCE(AVG(o.rating), 0) AS rating  
        FROM 
            homie.pro_data p
        LEFT JOIN 
            homie.orders o ON p.piva = o.pro_id AND o.rating IS NOT NULL
        GROUP BY 
            p.piva, p.nome, p.cognome, p.professione, p.indirizzo, p.prezzo_orario, p.prezzo_chiamata, p.is_active
    ";

    $result = $conn->query($query);

    $professionals = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $coords = getCachedCoords($row['indirizzo']);

            $professional = [
                'nome' => ucfirst($row['nome']) . " " . ucfirst($row['cognome']),
                'professione' => ucfirst($row['professione']),
                'lat' => floatval($coords['lat']),
                'lng' => floatval($coords['lng']),
                'rating' => round(floatval($row['rating']), 2),
                'image' => strtolower(str_replace(' ', '', $row['nome']) . "-" . str_replace(' ', '', $row['cognome']) . "-" . $row['piva'] . ".jpeg"),
                'prezzo_orario' => "€" . $row['prezzo_orario'],
                'prezzo_chiamata' => "€" . $row['prezzo_chiamata'],
                'piva' => $row['piva'],
                'position' => $row['indirizzo'],
                'is_active' => $row['is_active']
            ];
            $professionals[] = $professional;
        }
        echo json_encode($professionals);
    } else {
        echo json_encode([]);
    }
    $conn->close();
}


function getCachedCoords($address)
{
    $cacheFile = 'cache/' . md5($address) . '.json'; // Assicurati che la cartella cache sia scrivibile
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) { // Cache valida per 1 ora
        return json_decode(file_get_contents($cacheFile), true);
    }

    $coords = getLatLong($address);
    if ($coords && $coords['lat'] !== null && $coords['lng'] !== null) {
        file_put_contents($cacheFile, json_encode($coords));
    }

    return $coords;
}

function getLatLong($address)
{
    $apiKey = "AIzaSyD8NKq8Y7ZWcdnprjHHsH153OrNT3HyVmk";
    $baseUrl = "https://maps.googleapis.com/maps/api/geocode/json";
    $formattedAddress = urlencode($address);
    $url = "{$baseUrl}?address={$formattedAddress}&key={$apiKey}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Imposta un timeout di 30 secondi
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['results'][0])) {
        $geometry = $data['results'][0]['geometry']['location'];
        return ["lat" => $geometry['lat'], "lng" => $geometry['lng']];
    } else {
        return ["lat" => null, "lng" => null];
    }
}


function getAddress()
{
    include 'db_connection.php';
    $userId = $_SESSION['userid'];
    header('Content-Type: application/json');
    $query = "SELECT indirizzo FROM homie.user_data WHERE userid = $userId";
    $result = $conn->query($query);

    if ($row = $result->fetch_assoc()) {
        $coords = getCachedCoords($row['indirizzo']);
        echo json_encode(['success' => true, 'address' => $row['indirizzo'], 'lat' => (float) $coords['lat'], 'lng' => (float) $coords['lng']]);
    } else {
        echo json_encode(['success' => false]);
    }

    $conn->close();
}


function updateAddress()
{
    include 'db_connection.php';
    $data = json_decode(file_get_contents('php://input'), true);
    $newAddress = $data['address'];
    $coords = getCachedCoords($newAddress);

    $userId = $_SESSION['userid'];
    $query = "UPDATE homie.user_data SET indirizzo = ? WHERE userid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newAddress, $userId);

    if ($stmt->execute()) {
        $_SESSION['indirizzo'] = $newAddress;
        echo json_encode(['success' => true, 'lat' => $coords['lat'], 'lng' => $coords['lng']]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}


function updatePrice()
{
    include 'db_connection.php';
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }

    $priceType = $data['priceType'];
    $value = intval($data['value']);
    $pro_id = $_SESSION['piva'];
    $column = $priceType === 'callInput' ? 'prezzo_chiamata' : 'prezzo_orario';
    $sql = "UPDATE homie.pro_data SET $column = $value WHERE piva = '$pro_id'";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
        if ($priceType === 'callInput') {
            $_SESSION['p_chiamata'] = $value;
        } else {
            $_SESSION['p_orario'] = $value;
        }
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $conn->close();
}


function updateActive()
{
    include 'db_connection.php';
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }

    if (!isset($data['isActive'])) {
        echo json_encode(['success' => false, 'error' => 'Missing isActive field']);
        exit;
    }

    $isActive = $data['isActive'] === true ? 1 : 0; 
    $pro_id = $_SESSION['piva'];
    $sql = "UPDATE homie.pro_data SET is_active = $isActive WHERE piva = '$pro_id'";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
        $_SESSION['is_active'] = $isActive;
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $conn->close();
}

function addRequest()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $requestDetails = [
        'requestId' => uniqid(),
        'userId' => $_SESSION['userid'],
        'userName' => ucfirst($_SESSION['name']) . " " . ucfirst($_SESSION['cognome']),
        'userAddress' => $_SESSION['indirizzo'],
        'userLng' => $data['userLng'],
        'userLat' => $data['userLat'],
        'professionalName' => $data['professionalName'],
        'professionalId' => $data['professionalId'],
        'status' => 'pending',
        'details' => $data['details'],
        'timestamp' => date('c')
    ];

    $file = 'requests.json';
    $current_data = file_exists($file) ? file_get_contents($file) : '[]';
    $array_data = json_decode($current_data, true);
    $array_data[] = $requestDetails;
    $final_data = json_encode($array_data, JSON_PRETTY_PRINT);
    if (file_put_contents($file, $final_data)) {
        echo json_encode(['success' => true, 'requestId' => $requestDetails['requestId']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error saving data']);
    }
}

function cancelRequest()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $requestId = $data['requestId'];

    $file = 'requests.json';
    if (file_exists($file)) {
        $current_data = file_get_contents($file);
        $array_data = json_decode($current_data, true);
        $found = false;

        foreach ($array_data as $key => $entry) {
            if ($entry['requestId'] === $requestId) {
                $array_data[$key]['status'] = 'canceled';
                $found = true;
                break;
            }
        }

        if ($found) {
            $final_data = json_encode($array_data, JSON_PRETTY_PRINT);
            if (file_put_contents($file, $final_data)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to save the updated data']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Request not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Request file does not exist']);
    }
}

function clearCanceledRequest()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $requestId = $data['requestId'];
    $file = 'requests.json';
    if (file_exists($file)) {
        $current_data = file_get_contents($file);
        $array_data = json_decode($current_data, true);
        $updated = false;

        foreach ($array_data as $key => $entry) {
            if ($entry['status'] === 'canceled' && $entry['requestId'] === $requestId) {
                unset($array_data[$key]);
                $updated = true;
            }
        }

        if ($updated) {
            $final_data = json_encode($array_data, JSON_PRETTY_PRINT);
            if (file_put_contents($file, $final_data)) {
                echo json_encode(['success' => true]);
                return;
            }
        }
        echo json_encode(['success' => false, 'error' => 'No matching request found or request already processed']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Request file does not exist']);
    }
}

function acceptRequest($requestId)
{
    $file = 'requests.json';
    if (file_exists($file)) {
        $current_data = file_get_contents($file);
        $array_data = json_decode($current_data, true);
        $updated = false;

        foreach ($array_data as $key => $entry) {
            if ($entry['requestId'] === $requestId && $entry['status'] === 'pending') {
                $array_data[$key]['status'] = 'accepted';
                $updated = true;
                break;
            }
        }

        if ($updated) {
            $final_data = json_encode($array_data, JSON_PRETTY_PRINT);
            if (file_put_contents($file, $final_data)) {
                echo json_encode(['success' => true]);
                return;
            }
        }
        echo json_encode(['success' => false, 'error' => 'No matching request found or request already processed']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Request file does not exist']);
    }
}

function deleteRequest($requestId)
{
    $filePath = 'requests.json';
    if (!file_exists($filePath)) {
        echo json_encode(['success' => false, 'error' => 'File not found']);
        return;
    }
    $data = json_decode(file_get_contents($filePath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Error decoding JSON']);
        return;
    }
    $index = null;
    foreach ($data as $key => $request) {
        if ($request['requestId'] === $requestId) {
            $index = $key;
            break;
        }
    }
    if ($index !== null) {
        array_splice($data, $index, 1);
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error saving data']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Request not found']);
    }
}

function endRequest($requestId)
{
    include 'db_connection.php';

    $filePath = 'requests.json';
    if (!file_exists($filePath)) {
        echo json_encode(['success' => false, 'error' => 'File not found']);
        return;
    }

    $data = json_decode(file_get_contents($filePath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Error decoding JSON']);
        return;
    }

    $requestDetails = null;
    foreach ($data as $key => $request) {
        if ($request['requestId'] === $requestId) {
            $requestDetails = $request;
            $requestDetails['status'] = 'completed';
            $data[$key] = $requestDetails;
            break;
        }
    }

    if (!$requestDetails) {
        echo json_encode(['success' => false, 'error' => 'Request not found']);
        return;
    }
    $requestDetails['rating'] = 0;

    if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT))) {
        $sql = "INSERT INTO homie.orders (order_id, user_id, pro_id, rating, date, details, accepted, completed)
                VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $accepted = 1;
        $completed = 1;
        $stmt->bind_param(
            "sisssii",
            $requestDetails['requestId'],
            $requestDetails['userId'],
            $requestDetails['professionalId'],
            $requestDetails['rating'],
            $requestDetails['details'],
            $accepted,
            $completed
        );

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Error saving data']);
    }

    $conn->close();
}

function rejectRequest()
{
    include 'db_connection.php'; 
    $data = json_decode(file_get_contents('php://input'), true);
    $requestId = $data['requestId'];

    $filePath = 'requests.json';
    if (!file_exists($filePath)) {
        echo json_encode(['success' => false, 'error' => 'File not found']);
        return;
    }

    $requests = json_decode(file_get_contents($filePath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Error decoding JSON']);
        return;
    }

    foreach ($requests as $key => $request) {
        if ($request['requestId'] === $requestId) {
            $sql = "INSERT INTO homie.orders (order_id, user_id, pro_id, details, accepted, completed) VALUES (?, ?, ?, ?, 0, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siss", $request['requestId'], $request['userId'], $request['professionalId'], $request['details']);
            $stmt->execute();
            $stmt->close();

            array_splice($requests, $key, 1);
            break;
        }
    }

    if (file_put_contents($filePath, json_encode($requests, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error saving data']);
    }

    $conn->close();
}

function rejectAll()
{
    include 'db_connection.php';
    $professionalId = $_SESSION['piva'];

    $filePath = 'requests.json';
    if (!file_exists($filePath)) {
        echo json_encode(['success' => false, 'error' => 'File not found']);
        return;
    }

    $requests = json_decode(file_get_contents($filePath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Error decoding JSON']);
        return;
    }

    $sql = "INSERT INTO homie.orders (order_id, user_id, pro_id, details, accepted, completed) VALUES (?, ?, ?, ?, 0, 0)";
    $stmt = $conn->prepare($sql);

    $updatedRequests = [];
    $anyRejected = false;

    foreach ($requests as $request) {
        if ($request['professionalId'] === $professionalId && $request['status'] === 'pending') {
            $stmt->bind_param("siss", $request['requestId'], $request['userId'], $request['professionalId'], $request['details']);
            $stmt->execute();
            $anyRejected = true;
        } else {
            $updatedRequests[] = $request;
        }
    }

    $stmt->close();

    if ($anyRejected) {
        if (file_put_contents($filePath, json_encode($updatedRequests, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error saving data']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No pending requests found for rejection']);
    }

    $conn->close();
}



function getRequests()
{
    if (!isset($_SESSION['piva'])) {
        echo json_encode(['success' => false, 'error' => 'Professional ID not set in session']);
        return;
    }

    $professionalId = $_SESSION['piva'];

    $filePath = 'requests.json';
    if (!file_exists($filePath)) {
        echo json_encode(['success' => false, 'error' => 'Requests file not found']);
        return;
    }

    $allRequests = json_decode(file_get_contents($filePath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Error decoding JSON']);
        return;
    }

    $filteredRequests = array_filter($allRequests, function ($request) use ($professionalId) {
        return $request['professionalId'] === $professionalId;
    });

    echo json_encode(['success' => true, 'requests' => array_values($filteredRequests)]);
}


function getRequestDetails()
{
    $requestId = $_GET['requestId'] ?? '';

    if (!$requestId) {
        echo json_encode(['success' => false, 'error' => 'Request ID is missing']);
        return;
    }

    $filePath = 'requests.json';
    if (!file_exists($filePath)) {
        echo json_encode(['success' => false, 'error' => 'File not found']);
        return;
    }

    $data = json_decode(file_get_contents($filePath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Error decoding JSON']);
        return;
    }

    foreach ($data as $key => $request) {
        if ($request['requestId'] === $requestId) {
            echo json_encode(['success' => true, 'requestDetails' => $request]);
            return;
        }
    }
    echo json_encode(['success' => false, 'error' => 'Request not found']);;
}

function updateLocation()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $filePath = 'locations.json';

    $locations = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    $locations[$data['professionalId']] = $data;  

    if (file_put_contents($filePath, json_encode($locations, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Unable to save location data']);
    }
}

function getProfessionalPosition()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $filePath = 'locations.json';

    if (file_exists($filePath)) {
        $locations = json_decode(file_get_contents($filePath), true);
        $professionalId = $data['professionalId']; // Make sure this ID is being sent from the client

        if (isset($locations[$professionalId])) {
            echo json_encode([
                'success' => true,
                'lat' => $locations[$professionalId]['lat'],
                'lng' => $locations[$professionalId]['lng']
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Location not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Location data file not found']);
    }
}

function getUserRequests()
{

    $userId = $_SESSION['userid'];

    $requestData = json_decode(file_get_contents('requests.json'), true);
    $userRequests = array_filter($requestData, function ($request) use ($userId) {
        return $request['userId'] === $userId;
    });

    if (!empty($userRequests)) {
        echo json_encode(['success' => true, 'requests' => $userRequests]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No active requests found']);
    }
}

function getFavorites() {
    include 'db_connection.php';
    $userId = $_SESSION['userid'];
    header('Content-Type: application/json');

    $query = "SELECT pro_id FROM homie.favorites WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $favorites = [];
        while ($row = $result->fetch_assoc()) {
            $favorites[] = $row['pro_id'];
        }
        echo json_encode(['success' => true, 'favorites' => $favorites]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Errore durante il recupero dei preferiti']);
    }
    
    $stmt->close();
    $conn->close();
}

function addFavorite() {
    include 'db_connection.php';
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['userid'];
    $professionalId = $data['professionalId'];
    $isFavorite = $data['isFavorite'];

    header('Content-Type: application/json');

    if ($isFavorite) {
        $query = "INSERT INTO homie.favorites (user_id, pro_id) VALUES (?, ?)";
    } else {
        $query = "DELETE FROM homie.favorites WHERE user_id = ? AND pro_id = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $professionalId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Errore durante l\'aggiornamento dei preferiti']);
    }
    
    $stmt->close();
    $conn->close();
}

function submitRating()
{
    include 'db_connection.php';
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['requestId']) || !isset($data['rating'])) {
        echo json_encode(['success' => false, 'error' => 'Missing requestId or rating']);
        return;
    }

    $requestId = $data['requestId'];
    $rating = floatval($data['rating']);

    $sql = "UPDATE homie.orders SET rating = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $rating, $requestId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'updatePrice':
            updatePrice();
            break;

        case 'updateActive':
            updateActive();
            break;

        case 'updateAddress':
            updateAddress();
            break;

        case 'addRequest':
            addRequest();
            break;

        case 'cancelRequest':
            cancelRequest();
            break;

        case 'acceptRequest':
            $data = json_decode(file_get_contents('php://input'), true);
            acceptRequest($data['requestId']);
            break;

        case 'endRequest':
            $data = json_decode(file_get_contents('php://input'), true);
            endRequest($data['requestId']);
            break;

        case 'rejectRequest':
            rejectRequest();
            break;

        case 'rejectAllRequests':
            rejectAll();
            break;

        case 'updateLocation':
            updateLocation();
            break;

        case 'getProfessionalPosition':
            getProfessionalPosition();
            break;

        case 'deleteRequest':
            $data = json_decode(file_get_contents('php://input'), true);
            deleteRequest($data['requestId']);
            break;
        
        case 'clearCanceledRequest':
            clearCanceledRequest();
            break;

        case 'addFavorite':
            addFavorite();
            break;
            
        case 'submitRating':
            submitRating();
            break;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'getProfessionals':
            downloadProfessionals();
            break;

        case 'getCoordinates':
            if (isset($_GET['address'])) {
                echo json_encode(getCachedCoords($_GET['address']));
            }
            break;

        case 'getAddress':
            getAddress();
            break;

        case 'getRequests':
            getRequests();
            break;

        case 'getRequestDetails':
            getRequestDetails();
            break;

        case 'getUserRequests':
            getUserRequests();
            break;

        case 'getFavorites':
            getFavorites();
            break;
    }
}

/* function favorite_handler(){
    session_start();
include 'db_connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['userid'];
    $item_id = $conn->real_escape_string($input['item_id']);
    $checked = filter_var($input['checked'], FILTER_VALIDATE_BOOLEAN);

    if ($checked) {
        // Add to favorites
        $query = "INSERT INTO homie.favorites (userid, item_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $item_id);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Aggiunto ai preferiti.";
        } else {
            $response['message'] = 'Errore durante l\'aggiunta ai preferiti: ' . $conn->error;
        }
    } else {
        // Remove from favorites
        $query = "DELETE FROM homie.favorites WHERE userid = ? AND item_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $item_id);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Rimosso dai preferiti.";
        } else {
            $response['message'] = 'Errore durante la rimozione dai preferiti: ' . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);

} */