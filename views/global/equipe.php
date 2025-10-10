<h1>Notre Équipe</h1>
<div class="container">
    <h1 class="mb-4 text-center">Liste des Moniteurs et des Véhicules</h1>
    <?php
    use models\MoniteurModel;
    use models\VehiculeModel;
    $moniteurModel = new \models\MoniteurModel();
    $vehiculeModel = new \models\VehiculeModel();
    $moniteurs = $moniteurModel->getAllMoniteurs();
    $vehicules = $vehiculeModel->getAllVehicules();
    ?>
    <div class="row">
        <div class="col-md-6">
            <h3>Moniteurs</h3>
            <?php if (!empty($moniteurs)): ?>
                <ul class="list-group">
                    <?php foreach ($moniteurs as $m): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($m->prenommoniteur . ' ' . $m->nommoniteur) ?></strong><br>
                            📧 <?= htmlspecialchars($m->emailmoniteur) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Aucun moniteur disponible.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h3>Véhicules</h3>
            <?php if (!empty($vehicules)): ?>
                <ul class="list-group">
                    <?php foreach ($vehicules as $v): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($v->designation) ?></strong><br>
                            Immatriculation: <?= htmlspecialchars($v->immatriculation) ?><br>
                            Passagers: <?= htmlspecialchars($v->nbpassagers) ?><br>
                            Manuel: <?= $v->manuel ? 'Oui' : 'Non' ?><br>
                            <img src="<?=$v->lien_image?>" alt="<?= htmlspecialchars($v->designation) ?>" style="max-width: 220px; max-height: 220px;">
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Aucun véhicule disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</div>