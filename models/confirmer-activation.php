<?php
session_start();
use models\InscrireModel;
use utils\SessionHelpers;

if (!SessionHelpers::isLogin()) {
    header('Location: connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idforfait'])) {
    $idforfait = (int) $_POST['idforfait'];

    $inscrireModel = new InscrireModel();
    $success = $inscrireModel->activateForfait($idforfait);

    if ($success) {
        // Redirection vers une page de succès ou le compte utilisateur
        header('Location: compte.php?message=Forfait activé avec succès');
        exit;
    } else {
        // Erreur
        header('Location: activer-offre.php?error=Erreur lors de l\'activation du forfait');
        exit;
    }
} else {
    header('Location: forfaits.php');
    exit;
}
?>
