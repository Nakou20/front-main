<?php

use utils\SessionHelpers;
?>

<header class="hero-section text-white text-center">
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 70vh;">
        <h1 class="display-3 fw-bold">CDS 49 - Chevrollier Driving School</h1>
        <p class="lead my-3">Votre partenaire de confiance pour un apprentissage de la conduite serein et efficace.</p>
        <?php
        if (SessionHelpers::isLogin()) { ?>
            <a class="btn btn-primary btn-lg mt-5" href="mon-compte/">Accéder à mon espace</a>
        <?php } else { ?>
            <div>
                <a class="btn btn-primary btn-lg me-2" href="creer-compte.html">Créer un compte</a>
                <a class="btn btn-outline-light btn-lg" href="connexion.html">Se connecter</a>
            </div>
        <?php } ?>
    </div>
</header>

<main class="container mt-5 pt-5">
    <section id="presentation" class="py-5 text-center">
        <h2 class="display-5 fw-light mb-4">Bienvenue chez CDS 49 !</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <p class="lead text-muted">L'auto-école Chevrollier Driving School 49 (CDS 49) vous accompagne dans l'apprentissage de la conduite. Nous mettons à votre disposition des moniteurs expérimentés et une pédagogie adaptée à chacun pour vous mener vers la réussite de votre permis de conduire.</p>
                <p class="text-muted">Que vous soyez débutant ou que vous souhaitiez perfectionner votre conduite, nous avons la formule qu'il vous faut. Rejoignez-nous et prenez la route en toute confiance !</p>
            </div>
        </div>
    </section>

    <hr class="my-5">

    <section id="adresse" class="py-5">
        <h2 class="display-5 fw-light text-center mb-5">Où nous trouver ?</h2>
        <div class="row">
            <div class="col-md-6">
                <p class="lead">Notre auto-école est située à l'adresse suivante :</p>
                <address class="fs-5">
                    <strong>CDS 49 - Chevrollier Driving School</strong><br>
                    <i class="fas fa-map-marker-alt me-2"></i>2 Rue Adrien Recouvreur
                    49100 Angers<br>
                    France
                </address>
                <p class="mt-3"><i class="fas fa-phone me-2"></i>02 XX XX XX XX</p>
                <p><i class="fas fa-envelope me-2"></i>contact@cds49.fr</p>
            </div>
            <div class="col-md-6">
                <div id="mapid" style="height: 400px; border-radius: 0.3rem;" class="shadow"></div>
            </div>
        </div>
    </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var mymap = L.map('mapid').setView([47.4548178, -0.5622276], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);

    var marker = L.marker([47.4548178, -0.5622276]).addTo(mymap);
    marker.bindPopup("<b>CDS 49</b><br>2 Rue Adrien Recouvreur, 49100 Angers").openPopup();
</script>