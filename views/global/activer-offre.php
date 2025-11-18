<main class="container mt-5 pt-5">
    <header class="text-center mb-5">
        <h1 class="display-4 fw-light">Activer une offre</h1>
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

    <?php if (isset($forfait)) { ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title fw-bold text-primary"><?= htmlspecialchars($forfait->libelleforfait) ?></h3>
                        <p class="card-text"><?= htmlspecialchars($forfait->descriptionforfait) ?></p>

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

                        <form method="POST" action="activer-offre.html?idforfait=<?= $forfait->idforfait ?>">
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                                Confirmer l'activation de ce forfait
                            </button>
                        </form>
                        <a class="btn btn-secondary btn-lg w-100 mt-3" href="/forfaits.html">Retour aux forfaits</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="text-center">
            <p class="lead my-3">Aucun forfait sélectionné.</p>
            <a class="btn btn-primary btn-lg mt-4" href="/forfaits.html">Voir les forfaits</a>
        </div>
    <?php } ?>
</main>
