<main class="container mt-5 pt-5">
    <header class="text-center mb-5">
        <h1 class="display-4 fw-light">Découvrez Nos Forfaits</h1>
        <p class="lead text-muted">Des solutions adaptées à chaque besoin pour obtenir votre permis.</p>
    </header>

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

    <?php if (!empty($warning)) { ?>
        <div class="alert alert-warning" role="alert">
            <?= htmlspecialchars($warning) ?>
        </div>
    <?php } ?>

    <section id="nos-forfaits" class="py-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
            
            <?php if (isset($forfaits) && !empty($forfaits)) { ?>
                <?php foreach ($forfaits as $forfait) { ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($forfait->libelleforfait); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($forfait->descriptionforfait); ?></p>

                                <?php if (!empty($forfait->contenuforfait)) { ?>
                                    <ul class="list-unstyled mt-3 mb-4">
                                        <?php
                                        $contenuDetails = explode(';', $forfait->contenuforfait);
                                        foreach ($contenuDetails as $detail) { ?>
                                            <li><i class="fas fa-check text-success me-2"></i><?= htmlspecialchars(trim($detail)); ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>

                                <h3 class="card-price text-center fw-bold my-3">
                                    <?php

                                    if ($forfait->prixforfait) {
                                        echo $forfait->prixforfait . " €";
                                    } elseif ($forfait->prixhoraire) {
                                        echo $forfait->prixhoraire . " € / heure";
                                    } else {
                                        echo "Prix sur demande";
                                    }

                                    ?>
                                </h3>
                                <a href="activer-offre.html?idforfait=<?= $forfait->idforfait; ?>"
                                    class="btn <?= $forfait->prixhoraire ? 'btn-outline-primary' : 'btn-primary'; ?> mt-auto">
                                    Choisir ce forfait
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else {  ?>
                <div class="col-12">
                    <p class="text-center">Aucun forfait disponible pour le moment.</p>
                </div>
            <?php } ?>

        </div>
    </section>
</main>