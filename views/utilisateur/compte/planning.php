<main class="container pt-4">
    <section id="espace-connecte">
        <h1 class="mb-4">Mon Espace</h1>

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
            // Inclusion de la sidebar pour l'espace compte utilisateur (menu de navigation)
            $page_active = 'planning';
            include '_sidebar_compte.php';
            ?>

            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Section Forfait Actif -->
                    <div class="card mb-4">
                        <div class="card-header fw-bold">
                            Mon Forfait Actif
                        </div>
                        <div class="card-body">
                            <?php if ($forfait) { ?>
                                <!-- Cas 1: Forfait actif -->
                                <div id="forfait-actif-info">
                                    <h5 class="card-title" id="nom-forfait-actif"><?= htmlspecialchars($forfait->libelleforfait) ?></h5>
                                    <?php if ($forfait->nbheures != null) { ?>
                                        <p class="card-text">Il vous reste <strong id="heures-restantes"><?= $planning['nbLeconsRestantes'] ?> heures</strong> de conduite.</p>
                                    <?php } ?>

                                    <?php if ($planning['prochainRdv']) { ?>
                                        <p class="card-text">Prochain RDV pédagogique le : <span id="rdv-pedagogique"><?= date('d/m/Y à H:i', strtotime($planning['prochainRdv'])) ?></span></p>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <!-- Cas 2: Aucun forfait -->
                                <div id="aucun-forfait-info">
                                    <p class="card-text">Vous n'avez actuellement aucun forfait actif.</p>
                                    <div>
                                        <a href="/forfaits.html" class="btn btn-primary">Choisir un forfait</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Fin Section Forfait Actif -->

                    <?php if ($forfait) { ?>
                        <div>
                            <h2 class="display-5 mb-3">Planning des passages</h2>
                            <p>Voici vos heures de conduite planifiées :</p>
                            <!-- Conteneur pour FullCalendar -->
                            <div id='calendar'></div>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-info" role="alert">
                            Vous devez d'abord choisir un forfait pour accéder à votre planning de passages.
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            height: 850, // Hauteur du calendrier,
            expandRows: true, // Permet d'étendre les lignes pour afficher tous les événements
            initialView: 'timeGridWeek', // Vue agenda hebdomadaire
            slotMinTime: '08:00:00', // Heure de début
            slotMaxTime: '20:00:00', // Heure de fin
            slotDuration: '01:00:00', // Durée des créneaux
            allDaySlot: false, // Masquer le créneau "toute la journée"
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek'
            },
            events: [
                <?php foreach ($planning['planning'] as $lecon) { ?> {
                        title: '<?= htmlspecialchars($lecon['title']) ?>',
                        start: '<?= date('Y-m-d\TH:i:s', strtotime($lecon['start'])) ?>',
                        end: '<?= date('Y-m-d\TH:i:s', strtotime($lecon['end'])) ?>'
                    },
                <?php } ?>
            ],
            locale: 'fr',
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour',
                list: 'Liste'
            }
        });
        calendar.render();
    });
</script>