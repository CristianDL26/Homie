<div class="container" id="landing">
    <div class="text-container main-container">
        <div class="fixed-width">
            <h2 id="main-title" class="section-title dark_text ">
                Benvenuto in <span style="color: #E63946;">Homie!</span>
            </h2>
            <h3 class="introduction-text roboto-regular dark_text">
                Qui i tuoi problemi domestici trovano soluzione in pochi click. <br>
                Homie porta a te il professionista giusto, esattamente quando ne hai bisogno
            </h3>

            <section class="how-it-works">
                <h3 class="section-title dark_text">Come funziona?</h3>
                <div class="steps-container">
                    <div class="step">
                        <h4 class="step-title roboto-regular">Problemi in casa?</h4>
                        <img draggable="false" src="img/problems.png" alt="Step 1">
                        <a class="step-description">Un rubinetto che perde? Una serratura da cambiare? Apri Homie e dicci cosa ti serve.</a>
                    </div>
                    <div class="step">
                        <h4 class="step-title roboto-regular">Apri Homie</h4>
                        <img draggable="false" src="img/call.png" alt="Step 2">
                        <a class="step-description">Visualizza i professionisti disponibili vicino a te e scegli quello che preferisci in base a recensioni e tariffe.</a>
                    </div>
                    <div class="step">
                        <h4 class="step-title roboto-regular">Fatto!</h4>
                        <img draggable="false" src="img/solution.png" alt="Step 3">
                        <a class="step-description">Conferma il servizio e il tuo professionista arriverà in un istante.</a>
                    </div>
                </div>
            </section>
            <section class="workers-list">
                <h3 class="section-title dark_text">I professionisti</h3>
                <div class="workers-container">
                    <div class="col">
                        <div class="worker">
                            <img draggable="false" src="img/idraulico.png" alt="Plumber">
                            <a class="worker-title roboto-regular">Idraulico</a>
                        </div>
                        <div class="worker">
                            <img draggable="false" src="img/elettricista.png" alt="Electrician">
                            <a class="worker-title roboto-regular">Elettricista</a>
                        </div>
                        <div class="worker">
                            <img draggable="false" src="img/fabbro.png" alt="Blacksmith">
                            <a class="worker-title roboto-regular">Fabbro</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="worker">
                            <img draggable="false" src="img/pittore.png" alt="Painter">
                            <a class="worker-title roboto-regular">Pittore</a>
                        </div>
                        <div class="worker">
                            <img draggable="false" src="img/colf.png" alt="Housekeeper">
                            <a class="worker-title roboto-regular">Colf</a>
                        </div>
                        <div class="worker">
                            <img draggable="false" src="img/tuttofare.png" alt="Handyman">
                            <a class="worker-title roboto-regular">Tuttofare</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-container">

                    <div class="carousel">
                        <div class="carousel-item">
                            <div class="worker">
                                <img draggable="false" src="img/idraulico.png" alt="Plumber">
                                <a class="worker-title roboto-regular">Idraulico</a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="worker">
                                <img draggable="false" src="img/elettricista.png" alt="Electrician">
                                <a class="worker-title roboto-regular">Elettricista</a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="worker">
                                <img draggable="false" src="img/fabbro.png" alt="Blacksmith">
                                <a class="worker-title roboto-regular">Fabbro</a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="worker">
                                <img draggable="false" src="img/pittore.png" alt="Painter">
                                <a class="worker-title roboto-regular">Pittore</a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="worker">
                                <img draggable="false" src="img/colf.png" alt="Housekeeper">
                                <a class="worker-title roboto-regular">Colf</a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="worker">
                                <img draggable="false" src="img/tuttofare.png" alt="Handyman">
                                <a class="worker-title roboto-regular">Tuttofare</a>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-button prev">&lt;</button>
                    <button class="carousel-button next">&gt;</button>
                </div>
            </section>
            <section class="live-tracking">
                <div class="live-tracking-container">
                    <div class="live-tracking-content">
                        <img draggable="false" src="img/tracking.png" alt="Live tracking">
                        <div class="text-wrapper">
                            <a class="roboto-bold dark_text" style="font-size: 30px;">Non sai quando arriverà il tuo professionista?</a>
                            <img class="live-traking-img-small" draggable="false" src="img/tracking.png" alt="Live tracking" style="display: none;">
                            <a class="description dark_text">Con Homie puoi seguirlo in tempo reale e sapere esattamente quando arriverà.</a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

</div>
</div>
<script>
    function initializeCarousel() {
        const carousel = document.querySelector('.carousel');
        const prevButton = document.querySelector('.carousel-button.prev');
        const nextButton = document.querySelector('.carousel-button.next');
        let index = 0;

        function updateCarousel() {
            const items = document.querySelectorAll('.carousel-item');
            const totalItems = items.length;
            const visibleItems = 1; // Only 1 item visible on screens smaller than 425px

            if (index < 0) {
                index = totalItems - visibleItems;
            } else if (index >= totalItems) {
                index = 0;
            }

            const translateX = -index * 100; // Translate based on 1 visible item
            carousel.style.transform = `translateX(${translateX}%)`;
        }

        prevButton.addEventListener('click', () => {
            index--;
            updateCarousel();
        });

        nextButton.addEventListener('click', () => {
            index++;
            updateCarousel();
        });

        function handleTouchStart(event) {
            startX = event.touches[0].clientX;
        }

        function handleTouchMove(event) {
            if (!startX) return;

            currentX = event.touches[0].clientX;
        }

        function handleTouchEnd() {
            if (!startX || !currentX) return;

            const diffX = startX - currentX;

            if (diffX > 50) {
                // Swipe left
                index++;
            } else if (diffX < -50) {
                // Swipe right
                index--;
            }

            updateCarousel();

            // Reset values
            startX = null;
            currentX = null;
        }

        carousel.addEventListener('touchstart', handleTouchStart);
        carousel.addEventListener('touchmove', handleTouchMove);
        carousel.addEventListener('touchend', handleTouchEnd);

        window.addEventListener('resize', () => {
            if (window.innerWidth <= 425) {
                updateCarousel();
            } else {
                carousel.style.transform = 'translateX(0)';
            }
        });

        // Initialize carousel on load if the screen is small enough
        if (window.innerWidth <= 425) {
            updateCarousel();
        }
    }

    document.addEventListener('DOMContentLoaded', initializeCarousel);
</script>