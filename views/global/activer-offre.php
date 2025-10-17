<?php
use models\ForfaitModel;
use utils\SessionHelpers;

$model = new ForfaitModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idforfait'])) {
    $idforfait = (int) $_POST['idforfait'];
    $forfait = $model->getById($idforfait);

    if ($forfait) {
        $_SESSION['selected_forfait'] = $forfait;
        ?>
        <main class="container mt-5 pt-5">
            <header class="text-center mb-5">
                <h1 class="display-4 fw-light">Forfait Sélectionné</h1>
                <p class="lead text-muted">Confirmez votre choix pour activer ce forfait.</p>
            </header>

            <div class="card shadow-sm mx-auto" style="max-width: 600px;">
                <div class="card-body">
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

                    <?php if (!SessionHelpers::isLogin()) { ?>
                        <div class="alert alert-info text-center">
                            <p>Pour activer ce forfait, vous devez vous connecter ou créer un compte. Votre choix sera mémorisé et le forfait sera activé après votre authentification.</p>
                            <a href="connexion.html" class="btn btn-primary me-2">Se connecter</a>
                            <a href="creer-compte.html" class="btn btn-secondary">Créer un compte</a>
                        </div>
                    <?php } else { ?>
                        <form method="post" action="/confirmer-activation.html">
                            <input type="hidden" name="idforfait" value="<?= $forfait->idforfait; ?>">
                            <button type="submit" class="btn btn-success w-100">Activer ce forfait</button>
                        </form>
                    <?php } ?>

                    <a href="forfaits.html" class="btn btn-secondary w-100 mt-2">Retour aux forfaits</a>
                </div>
            </div>
        </main>
        <?php
    } else {
        echo "<p class='text-center mt-5'>Forfait introuvable.</p>";
    }
} else {
    header('Location: forfaits.php');
    exit;
}
?>
