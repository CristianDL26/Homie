<?php
session_start();
include 'db_connection.php'; 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = ucfirst($_SESSION['name']) . " " . ucfirst($_SESSION['cognome']);
$image = strtolower(str_replace(' ', '', $_SESSION['name']) . "-" . str_replace(' ', '', $_SESSION['cognome']) . "-" . $_SESSION['piva'] . ".jpeg");
$pro_id = $_SESSION['piva'];
$category = ucfirst($_SESSION['professione']);
$address = ucfirst($_SESSION['indirizzo']);
$is_active = $_SESSION['is_active'];
$p_orario = $_SESSION['p_orario'];
$p_chiamata = $_SESSION['p_chiamata'];
$rating = $_SESSION['rating'];
?>
<div class="fixed-width">
    <div class="main-professional-container">
        <h2>Dashboard Professionista</h2>
        <div class="professional-container-1">

            <div class="professionals-container professional-container-left">
                <div class="external-professional-details-container">
                    <div class="professional-details-container">
                        <div class="professional-image">
                            <img draggable="false" src="img/professionals/<?php echo $image?>" alt="">
                        </div>
                        <div class="professional-details">
                            <h2><?php echo $username?></h2>
                            <p><?php echo $pro_id?></p>
                            <p><?php echo $category?></p>
                            <p><?php echo $address?></p>
                        </div>
                    </div>
                    <div class="professional-rating-container">
                        <div class="worker-rating">
                            <span class="rating"><?php echo $rating?></span>
                            <svg fill="#E63946" id="star" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                <path id="primary" d="M22,9.81a1,1,0,0,0-.83-.69l-5.7-.78L12.88,3.53a1,1,0,0,0-1.76,0L8.57,8.34l-5.7.78a1,1,0,0,0-.82.69,1,1,0,0,0,.28,1l4.09,3.73-1,5.24A1,1,0,0,0,6.88,20.9L12,18.38l5.12,2.52a1,1,0,0,0,.44.1,1,1,0,0,0,1-1.18l-1-5.24,4.09-3.73A1,1,0,0,0,22,9.81Z" style="fill: #E63946"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="professional-buttons-container">
                    <div class="switch-holder">
                        <div class="switch-label">
                            <i class="fa fa-bluetooth-b"></i><span>Attivo</span>
                        </div>
                        <div class="switch-toggle">
                            <input type="checkbox" id="active-pro" <?php if($is_active) echo "checked"?>>
                            <label for="active-pro"></label>
                        </div>
                    </div>

                    <div class="switch-holder">
                        <div class="switch-label">
                            <i class="fa fa-bluetooth-b"></i><span>Prezzo Chiamata</span>
                        </div>
                        <div class="number-input-container">
                            <input type="number" id="callInput" value="<?php echo $p_chiamata?>" readonly>
                            <div class="inc-dec-container">
                                <button class="increment-button">+</button>
                                <button class="decrement-button">-</button>
                            </div>
                        </div>

                    </div>

                    <div class="switch-holder">
                        <div class="switch-label">
                            <i class="fa fa-bluetooth-b"></i><span>Prezzo Orario</span>
                        </div>
                        <div class="number-input-container">
                            <input type="number" id="hourInput" value="<?php echo $p_orario?>" readonly>
                            <div class="inc-dec-container">
                                <button class="increment-button">+</button>
                                <button class="decrement-button">-</button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="default-button" onclick="rejectAll()">Rifiuta tutto</button>
                    </div>
                </div>
            </div>

            <div class="map professionals-container" id="map">

                <div class="professionals-container-title">
                    <h1>Richieste in attesa</h1>
                </div>

                <ul>
                </ul>
            </div>

            <div class="map ongoing-request-container hidden">

            </div>
        </div>
    </div>
</div>