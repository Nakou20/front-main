<?php

use utils\SessionHelpers;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= @$titre ?> CDS 49 - Auto-école Chevrollier Driving School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/public/style/main.css">
    <link rel="icon" href="/public/images/icon.png" type="image/png">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-car-side me-2"></i>CDS 49
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/forfaits.html">Nos Forfaits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/a_propos.html">À Propos</a>
                    </li>
                    <?php if (SessionHelpers::isLogin() == false) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/#presentation">Présentation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/#adresse">Nous trouver</a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="/creer-compte.html">Créer un compte</a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="/connexion.html">Se connecter</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/mon-compte/">Mon Espace</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/deconnexion.html">Déconnexion</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>