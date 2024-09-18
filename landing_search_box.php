<div class="landing-search center-vertical">
    <h1 class="landing-title roboto-regular dark_text">Dal web alla porta di casa, è subito, è <span style="color: #E63946; font-weight: 700;">Homie!</span></h1>
    <p class="subtitle roboto-regular">Scopri i servizi vicino a te!</p>

    <div class="search-box-container center-vertical">
        <div class="mini-maps-background">
            <div class="mini-maps-container">
                <div class="mini-maps-wrapper">
                    <div id="roma-mini-map" class="single-mini-map-container">
                        <img class="mini-map" src="img/map/roma.png" alt="mini map">
                        <p class="mini-map-text light_text" title="Roma, RM, Italia">Roma</p>
                    </div>
                    <div id="milano-mini-map" class="single-mini-map-container">
                        <img class="mini-map" src="img/map/milano.png" alt="mini map">
                        <p class="mini-map-text light_text" title="Milano, MI, Italia">Milano</p>
                    </div>
                    <div id="torino-mini-map" class="single-mini-map-container">
                        <img class="mini-map" src="img/map/torino.png" alt="mini map">
                        <p class="mini-map-text light_text" title="Torino, TO, Italia">Torino</p>
                    </div>
                    <div id="napoli-mini-map" class="single-mini-map-container">
                        <img class="mini-map" src="img/map/napoli.png" alt="mini map">
                        <p class="mini-map-text light_text" title="Napoli, NA, Italia">Napoli</p>
                    </div>
                </div>

            </div>
        </div>
        <div class="searchBox" id="landing-search-box">
            <div class="search-wrapper">
                <div class="search-line-container">
                    <input class="searchInput" id="bigSearchInput" type="text" name="" placeholder="Inserisci la tua località..." onkeyup="handleInputMain()">
                    <button class="searchButton big-search-button" id="search-button-landing">
                        <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                            <g clip-path="url(#clip0_2_17)">
                                <g filter="url(#filter0_d_2_17)">
                                    <path d="M23.7953 23.9182L19.0585 19.1814M19.0585 19.1814C19.8188 18.4211 20.4219 17.5185 20.8333 16.5251C21.2448 15.5318 21.4566 14.4671 21.4566 13.3919C21.4566 12.3167 21.2448 11.252 20.8333 10.2587C20.4219 9.2653 19.8188 8.36271 19.0585 7.60242C18.2982 6.84214 17.3956 6.23905 16.4022 5.82759C15.4089 5.41612 14.3442 5.20435 13.269 5.20435C12.1938 5.20435 11.1291 5.41612 10.1358 5.82759C9.1424 6.23905 8.23981 6.84214 7.47953 7.60242C5.94407 9.13789 5.08145 11.2204 5.08145 13.3919C5.08145 15.5634 5.94407 17.6459 7.47953 19.1814C9.01499 20.7168 11.0975 21.5794 13.269 21.5794C15.4405 21.5794 17.523 20.7168 19.0585 19.1814Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" shape-rendering="crispEdges"></path>
                                </g>
                            </g>
                            <defs>
                                <filter id="filter0_d_2_17" x="-0.418549" y="3.70435" width="29.7139" height="29.7139" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                    <feOffset dy="4"></feOffset>
                                    <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                    <feComposite in2="hardAlpha" operator="out"></feComposite>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix>
                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2_17"></feBlend>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2_17" result="shape"></feBlend>
                                </filter>
                                <clipPath id="clip0_2_17">
                                    <rect width="28.0702" height="28.0702" fill="white" transform="translate(0.403503 0.526367)"></rect>
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
            </div>
            <ul id="resultsContainer"></ul>
        </div>
    </div>

    <div class="scroll-action container">
        <a href="#landing" class="scroll-to-landing">
            <svg width="50" height="80" viewBox="0 0 50 80" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                <style>
                    .scroll-to-landing {
                        cursor: pointer;
                        transition: opacity 0.3s;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        width: 100%;
                        height: 100%;
                        transition: cubic-bezier(0, 1.15, 0.65, 0.96) 5s;
                    }

                    .scroll-to-landing:hover {
                        width: 90%;
                        height: 90%;
                        transition: cubic-bezier(0, 1.15, 0.65, 0.96) 2s;
                        opacity: 0.7;
                    }

                    .scroll-arrow {
                        animation: scroll-animation 3s infinite;
                    }

                    @keyframes scroll-animation {

                        0%,
                        20%,
                        50%,
                        80%,
                        100% {
                            transform: translateY(0);
                        }

                        40% {
                            transform: translateY(5px);
                        }

                        60% {
                            transform: translateY(3px);
                        }
                    }
                </style>
                <rect x="10" y="10" fill="none" rx="15" ry="15" width="30" height="50" stroke="#1D3557" stroke-width="2"></rect>
                <circle class="scroll-arrow" cx="25" cy="25" r="3" stroke="#F1FAEE" fill="#1D3557"></circle>
            </svg>
        </a>

    </div>

</div>
<script type="text/javascript">
    document.getElementById('search-button-landing').addEventListener('click', function() {
        var searchValue = document.getElementById('bigSearchInput').value;
        localStorage.setItem('insert-address', searchValue);
        window.location.href = 'register_page.php'; 
    });
</script>