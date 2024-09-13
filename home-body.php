<div class="fixed-width">
    <div class="external-categories-container">
        <div class="categories-container">

            <div class="category">
                <button class="category-button" data-category="idraulico">
                    <img draggable="false" src="img/idraulico.png" alt="idraulico">
                </button>
                <span class="category-name">Idraulico</span>
            </div>
            <div class="category">
                <button class="category-button" data-category="pittore">
                    <img draggable="false" src="img/pittore.png" alt="pittore">
                </button>
                <span class="category-name">Pittore</span>
            </div>
            <div class="category">
                <button class="category-button" data-category="elettricista">
                    <img draggable="false" src="img/elettricista.png" alt="elettricista">
                </button>
                <span class="category-name">Elettricista</span>
            </div>
            <div class="category">
                <button class="category-button" data-category="fabbro">
                    <img draggable="false" src="img/fabbro.png" alt="fabbro">
                </button>
                <span class="category-name">Fabbro</span>
            </div>
            <div class="category">
                <button class="category-button" data-category="colf">
                    <img draggable="false" src="img/colf.png" alt="colf">
                </button>
                <span class="category-name">Colf</span>
            </div>
            <div class="category">
                <button class="category-button" data-category="tuttofare">
                    <img draggable="false" src="img/tuttofare.png" alt="tuttofare">
                </button>
                <span class="category-name">Tuttofare</span>
            </div>

        </div>
    </div>

    <div class="body-container">
        <div class="professionals-container">
            <div class="professionals-container-title">
                <h1>Professionisti</h1>
                <div class="favorites-toggle">
                    <button class="favorites-toggle-button">
                        <label class="favorites-toggle-container">
                            <input type="checkbox" id="favoriteCheckbox" data-item-id="123">
                            <svg id="Layer_1" version="1.0" viewBox="0 0 24 24" xml:space="preserve"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <path
                                    d="M16.4,4C14.6,4,13,4.9,12,6.3C11,4.9,9.4,4,7.6,4C4.5,4,2,6.5,2,9.6C2,14,12,22,12,22s10-8,10-12.4C22,6.5,19.5,4,16.4,4z">
                                </path>
                            </svg>
                        </label>
                    </button>
                </div>

            </div>
            <ul>
                <div id="loader-container">
                    <div class="loader">
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                    </div>
                </div>
            </ul>

        </div>

        <div class="map" id="map">
            <div id="loader-container">
                <div class="loader">
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <div id="professional-details" data-id="" data-name="" data-callprice="" data-hourprice="" class="hidden">
            <div class="container-4">
                <div class="container-2">
                    <div class="container-1">
                        <img draggable="false" id="professional-image" src="" alt="">
                        <div id="professional-internal-details">
                            <h2 id="professional-name"></h2>
                            <p id="professional-category"></p>
                            <div>
                                <p id="professional-address"></p>
                            </div>
                            <div class="worker-price">
                                <div class="call-price">
                                    <svg fill="#457B9D" width="800px" height="800px" viewBox="0 0 36 36" version="1.1"
                                        preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>phone-handset-solid</title>
                                        <path class="clr-i-solid clr-i-solid-path-1"
                                            d="M15.22,20.64a20.37,20.37,0,0,0,7.4,4.79l3.77-3a.67.67,0,0,1,.76,0l7,4.51a2,2,0,0,1,.33,3.18l-3.28,3.24a4,4,0,0,1-3.63,1.07,35.09,35.09,0,0,1-17.15-9A33.79,33.79,0,0,1,1.15,8.6a3.78,3.78,0,0,1,1.1-3.55l3.4-3.28a2,2,0,0,1,3.12.32L13.43,9a.63.63,0,0,1,0,.75l-3.07,3.69A19.75,19.75,0,0,0,15.22,20.64Z">
                                        </path>
                                        <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
                                    </svg>
                                    <p id="professional-call-price"></p>
                                </div>

                                <div class="hour-price-open">
                                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="9" stroke="#457B9D" stroke-width="2" />
                                        <path d="M16.5 12H12.25C12.1119 12 12 11.8881 12 11.75V8.5" stroke="#457B9D"
                                            stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    <p id="professional-hour-price"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-3">
                        <div class="is-active">
                            <div class="pulsing-circle"></div>
                            <p>Disponibile</p>
                        </div>
                    </div>
                </div>
                <button class="close-button" onclick="chiudiDettagli()">
                    X
                </button>
            </div>
            <div class="request-box">
                <h3>Richiedi un intervento</h3>
                <p>Descrivi il tuo problema, ogni dettaglio è importante! Non dimenticare Nulla!</p>
                <div class="text-area-container">
                    <textarea name="request" id="request" cols="30" rows="7"
                        placeholder="Descrivi il tuo problema"></textarea>
                </div>
                <div class="buttons-container">
                    <button class="send-request-button">
                        Invia
                        <div class="icon">
                            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </button>
                </div>

            </div>


        </div>
        <div class="request-pending-container hidden">
            <div class="request-pending">
                <div class="internal-container-2">
                    <div class="internal-container-1">
                        <h2>Richiesta inviata con successo!</h2>
                        <p>Siamo in attesa che <span class="professional-name-post"></span> accetti la tua richiesta.
                        </p>
                    </div>
                    <div class="gif-container" style="width:100%;height:50%;position:relative">
                        <img draggable="false"
                            src="https://media3.giphy.com/media/v1.Y2lkPTc5MGI3NjExN3hsZ24waW83eHhiam41bXdlem1jb2pob3NrbzYwNmNwd3gwNG92dSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/BDQmMy3ZM8sgRNFkhe/giphy.gif"
                            width="100%" height="100%" style="position:absolute;border-radius: 15px;" frameBorder="0"
                            class="giphy-embed"></img>
                    </div>
                    <button class="default-button" id="cancel-button">
                        Annulla
                    </button>
                </div>
            </div>


        </div>
        <div class="request-accepted-container hidden">
            <div class="banner-container">
                <div class="banner">
                    <div class="banner-internal-container">
                        <img draggable="false" src="" alt="">
                        <div class="i-container">
                            <h2>Richiesta accettata!</h2>
                            <p><span class="professional-name-banner"></span> è in arrivo!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="map" id="tracking-map"></div>
        </div>
        <div id="rating-modal" class="modal hidden">
            <div class="modal-content">
                <h2>Valuta il professionista</h2>
                <p>Per favore, lascia una valutazione per il professionista.</p>
                <div class="star-rating">
                    <input type="radio" id="5-stars" name="rating" value="5" />
                    <label for="5-stars" class="star">&#9733;</label>
                    <input type="radio" id="4-stars" name="rating" value="4" />
                    <label for="4-stars" class="star">&#9733;</label>
                    <input type="radio" id="3-stars" name="rating" value="3" />
                    <label for="3-stars" class="star">&#9733;</label>
                    <input type="radio" id="2-stars" name="rating" value="2" />
                    <label for="2-stars" class="star">&#9733;</label>
                    <input type="radio" id="1-star" name="rating" value="1" />
                    <label for="1-star" class="star">&#9733;</label>
                </div>
                <button class="default-button" id="submit-rating">Invia valutazione</button>
            </div>
        </div>

        <div class="request-rejected-container hidden">
            <div class="request-rejected">
                <div class="internal-container-2">
                    <div class="internal-rejected-container-4">
                        <h2>Richiesta rifiutata!</h2>
                        <p>Ci dispiace ma <span class="professional-name-rejected"></span> non ha accettato la tua
                            richiesta.</p>
                        <p>Riprova più tardi.</p>
                    </div>
                    <div class="gif-container" style="width:100%;height:50%;position:relative;">
                        <img draggable="false"
                            src="https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExaHB1YWlxeDhqdDRibnIyZ3d6a2RjdmU3OWhjMXBtcmZrMndkZThzNCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/ISOckXUybVfQ4/giphy.gif"
                            width="100%" height="100%" style="position:absolute;border-radius: 15px;" frameBorder="0"
                            class="giphy-embed"></img>
                    </div>
                    <button class="default-button" id="close-rejected-button">
                        Chiudi
                    </button>
                </div>
            </div>


        </div>
    </div>
</div>