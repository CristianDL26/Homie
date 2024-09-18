<?php
include 'db_connection.php'; 

if (!isset($_SESSION['userid'])) {
    header('Location: login_page.php');
    exit();
}
$username = ucfirst($_SESSION['name']) . " " . ucfirst($_SESSION['cognome']);
$indirizzo = $_SESSION['indirizzo'];
if (isset($_SESSION['professione'])) {
    $professione = $_SESSION['professione'];
} else {
    $professione = false;
}

?>
<div class="header-container header-bg">
    <div class="header fixed-width">
        <div class="header-right">
            <a href="home.php" class="logo-container">
              <img class="logo logo-large" src="img/text-logo.png" alt="Logo">
            <img class="logo logo-small" src="img/logo_new.png" alt="Logo" style="display: none;">
            </a>
        </div>
        
        <div class="header-left">
            <div class="address-container">
                <button class="address-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="11" r="3" />
                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                    </svg>
                    <span class="address" style="font-size: 15px;"></span>
                </button>
                <div class="address-modify-dropdown">
                    <form action="change_address.php" method="POST">
                        <label for="address" class="address-label">Modifica il tuo indirizzo</label>
                        <input type="text" name="address" id="address" class="address-input" placeholder="Inserisci il tuo indirizzo" onkeyup="handleInputB()">
                        <button type="submit" class="address-submit" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                        </button>
                    </form>
                    <ul id="address-entries-container"></ul>
                </div>
            </div>
            <div class="user-menu">
                <button class="user-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="7" r="4" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                    <span class="username" > <?php echo $username?></span>
                </button>
                <div class="dropdown-content">
                    <a href="profile.php">Profilo</a>
                    <a href="<?php echo $professione ? 'pro_profile.php?section=ordini' : 'profile.php?section=ordini'; ?>">Ordini</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) : ?>
                        <a href="admin_dashboard.php">Admin Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 8v-2a4 4 0 0 0 -4 -4h-5v16h5a4 4 0 0 0 4 -4v-2" />
                            <path d="M7 12h14l-3 -3m0 6l3 -3" />
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
            <div class="help-icon-container hidden">
                <div class="help-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#333" id="question-mark">
                        <circle cx="12" cy="12" r="10" fill="#F1FAEE" />
                        <text x="50%" y="50%" text-anchor="middle" fill="#333" font-size="16" font-family="Arial" dy=".3em">?</text>
                    </svg>
                </div>
            </div>
        </div>

    </div>
</div>

