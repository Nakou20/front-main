<?php
/**
 * Vue des détails d'une leçon de conduite
 *
 * @var object $lecon
 * @var bool $canCancel
 * @var array $eleve
 * @var string|null $error
 * @var string|null $success
 */


extract(get_defined_vars(), EXTR_SKIP);
?>
<main class="container pt-4">
    <section id="espace-connecte">
        <h1 class="mb-4">Détails de la leçon</h1>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <?php if (!empty($success)) { ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php } ?>

        <div class="row">
            <?php
            $page_active = 'planning';
            include '_sidebar_compte.php';
            ?>

            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header fw-bold">
                        Informations de la leçon
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Date et heure</h5>
                                <p class="lead">
                                    <i class="bi bi-calendar-event"></i>
                                    <?= date('d/m/Y à H:i', strtotime($lecon->heuredebut)) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5>Durée</h5>
                                <p class="lead">
                                    <i class="bi bi-clock"></i>
                                    1 heure
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Moniteur</h5>
                                <p class="lead">
                                    <i class="bi bi-person"></i>
                                    <?= htmlspecialchars($lecon->moniteur_prenom . ' ' . $lecon->moniteur_nom) ?>
                                </p>
                                <p>
                                    <i class="bi bi-envelope"></i>
                                    <a href="mailto:<?= htmlspecialchars($lecon->moniteur_email) ?>">
                                        <?= htmlspecialchars($lecon->moniteur_email) ?>
                                    </a>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Véhicule</h5>
                                <p class="lead">
                                    <i class="bi bi-car-front"></i>
                                    <?= htmlspecialchars($lecon->vehicule_designation) ?>
                                </p>
                                <p>
                                    <i class="bi bi-credit-card-2-front"></i>
                                    Immatriculation : <?= htmlspecialchars($lecon->vehicule_immatriculation) ?>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Lieu de rendez-vous</h5>
                                <p class="lead">
                                    <i class="bi bi-geo-alt"></i>
                                    <?php
                                    $adresseAffichee = !empty($lecon->lieurdv) ? htmlspecialchars($lecon->lieurdv) : '2 rue Adrian Recouvreur 49100 Angers';
                                    echo $adresseAffichee;
                                    ?>
                                </p>

                                <!-- Carte Leaflet -->
                                <div id="map" style="height: 400px; border-radius: 8px;" class="mb-3"></div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="/mon-compte/planning.html" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour au planning
                            </a>

                            <?php if ($canCancel) { ?>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmCancelModal">
                                    <i class="bi bi-x-circle"></i> Annuler cette leçon
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-secondary" disabled title="Cette leçon ne peut plus être annulée (moins de 48h avant le début)">
                                    <i class="bi bi-x-circle"></i> Annuler cette leçon
                                </button>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i>
                                    Vous ne pouvez annuler une leçon que 48h avant son début.
                                </small>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Modal de confirmation d'annulation -->
<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCancelModalLabel">Confirmer l'annulation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler cette leçon ?</p>
                <p class="text-muted">
                    <small>Un email de confirmation vous sera envoyé.</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder la leçon</button>
                <form method="POST" action="/mon-compte/planning/annuler_lecon.html" style="display: inline;">
                    <input type="hidden" name="lecon_id" value="<?= htmlspecialchars($_GET['lecon_id'] ?? '') ?>">
                    <button type="submit" class="btn btn-danger">Oui, annuler</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const DEFAULT_ADDRESS = '2 rue Adrian Recouvreur 49100 Angers';


        var map = L.map('map').setView([47.4704, -0.5519], 13);


        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);


        var addressFromDB = <?= json_encode($lecon->lieurdv) ?>;
        var address = (addressFromDB && addressFromDB.trim() !== '') ? addressFromDB : DEFAULT_ADDRESS;

        console.log('Adresse à géocoder:', address);


        function displayMarker(lat, lon, addr, isDefault = false) {
            map.setView([lat, lon], 15);
            var popupText = isDefault
                ? '<b>Lieu de rendez-vous (par défaut)</b><br>' + addr
                : '<b>Lieu de rendez-vous</b><br>' + addr;

            L.marker([lat, lon]).addTo(map)
                .bindPopup(popupText)
                .openPopup();

            console.log('Marqueur affiché à:', lat, lon);
        }


        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address))
            .then(response => response.json())
            .then(data => {
                console.log('Résultat du géocodage:', data);

                if (data && data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lon = parseFloat(data[0].lon);
                    displayMarker(lat, lon, address, address === DEFAULT_ADDRESS);
                } else {
                    console.warn('Adresse non trouvée, tentative avec l\'adresse par défaut');


                    if (address !== DEFAULT_ADDRESS) {
                        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(DEFAULT_ADDRESS))
                            .then(response => response.json())
                            .then(defaultData => {
                                if (defaultData && defaultData.length > 0) {
                                    var defaultLat = parseFloat(defaultData[0].lat);
                                    var defaultLon = parseFloat(defaultData[0].lon);
                                    displayMarker(defaultLat, defaultLon, DEFAULT_ADDRESS, true);
                                } else {
                                    console.error('Impossible de géocoder l\'adresse par défaut');
                                }
                            })
                            .catch(error => {
                                console.error('Erreur lors du géocodage de l\'adresse par défaut:', error);
                            });
                    } else {
                        console.error('Impossible de géocoder l\'adresse par défaut');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur de géocodage:', error);
            });
    });
</script>
