<main class="container pt-4">
    <section id="espace-connecte">
        <h1 class="mb-4">Mon Espace</h1>

        <div class="row">
            <?php
            $page_active = 'demandes';
            include '_sidebar_compte.php';
            ?>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-list"></i> Mes demandes d'heures supplémentaires
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php } ?>

                        <?php if (!empty($success)) { ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                            </div>
                        <?php } ?>

                        <div class="mb-3">
                            <a href="/mon-compte/demander-heures-supplementaires.html" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nouvelle demande
                            </a>
                            <a href="/mon-compte/planning.html" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour au planning
                            </a>
                        </div>

                        <?php if (empty($demandes)) { ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Vous n'avez pas encore fait de demande d'heures supplémentaires.
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date de demande</th>
                                            <th>Commentaire</th>
                                            <th>Statut</th>
                                            <th>Traité par</th>
                                            <th>Date de traitement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($demandes as $demande) { ?>
                                            <tr>
                                                <td><?= date('d/m/Y H:i', strtotime($demande['date_creation'])) ?></td>
                                                <td>
                                                    <?php
                                                    $commentaire = htmlspecialchars($demande['commentaire']);
                                                    echo strlen($commentaire) > 50 ? substr($commentaire, 0, 50) . '...' : $commentaire;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $statutClass = '';
                                                    $statutIcon = '';
                                                    $statutTexte = '';

                                                    switch ($demande['statut']) {
                                                        case 'en_attente':
                                                            $statutClass = 'warning';
                                                            $statutIcon = 'fa-clock';
                                                            $statutTexte = 'En attente';
                                                            break;
                                                        case 'validee':
                                                            $statutClass = 'success';
                                                            $statutIcon = 'fa-check-circle';
                                                            $statutTexte = 'Validée';
                                                            break;
                                                        case 'refusee':
                                                            $statutClass = 'danger';
                                                            $statutIcon = 'fa-times-circle';
                                                            $statutTexte = 'Refusée';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $statutClass ?>">
                                                        <i class="fas <?= $statutIcon ?>"></i> <?= $statutTexte ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($demande['nommoniteur']) { ?>
                                                        <?= htmlspecialchars($demande['prenommoniteur'] . ' ' . $demande['nommoniteur']) ?>
                                                    <?php } else { ?>
                                                        <span class="text-muted">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if ($demande['date_traitement']) { ?>
                                                        <?= date('d/m/Y H:i', strtotime($demande['date_traitement'])) ?>
                                                    <?php } else { ?>
                                                        <span class="text-muted">-</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php if (strlen($demande['commentaire']) > 50) { ?>
                                                <tr class="table-light">
                                                    <td colspan="5">
                                                        <small><strong>Commentaire complet :</strong> <?= htmlspecialchars($demande['commentaire']) ?></small>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i>
                                <strong>Légende des statuts :</strong>
                                <ul class="mb-0 mt-2">
                                    <li><span class="badge bg-warning">En attente</span> : Votre demande est en cours d'examen</li>
                                    <li><span class="badge bg-success">Validée</span> : Votre demande a été acceptée</li>
                                    <li><span class="badge bg-danger">Refusée</span> : Votre demande a été refusée</li>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

