<?php
/**
 * @var array $resultats
 * @var array $eleve
 * @var string $tri
 * @var string $ordre
 * @var string|null $error
 * @var string|null $success
 */
?>
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
            $page_active = 'resultats';
            include '_sidebar_compte.php';
            ?>

            <div class="col-md-9">
                <div class="tab-content">
                    <div class="card">
                        <div class="card-header fw-bold">
                            Mes Résultats
                        </div>
                        <div class="card-body">
                            <?php if (empty($resultats)) { ?>
                                <div class="alert alert-info" role="alert">
                                    Vous n'avez pas encore de résultats de quiz.
                                </div>
                            <?php } else { ?>
                                <p class="mb-3">Voici l'historique de vos résultats de quiz :</p>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <a href="?tri=date&ordre=<?= ($tri === 'date' && $ordre === 'DESC') ? 'ASC' : 'DESC' ?>"
                                                       class="text-decoration-none text-dark">
                                                        Date
                                                        <?php if ($tri === 'date') { ?>
                                                            <i class="fas fa-sort-<?= $ordre === 'DESC' ? 'down' : 'up' ?>"></i>
                                                        <?php } else { ?>
                                                            <i class="fas fa-sort text-muted"></i>
                                                        <?php } ?>
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="?tri=score&ordre=<?= ($tri === 'score' && $ordre === 'DESC') ? 'ASC' : 'DESC' ?>"
                                                       class="text-decoration-none text-dark">
                                                        Score
                                                        <?php if ($tri === 'score') { ?>
                                                            <i class="fas fa-sort-<?= $ordre === 'DESC' ? 'down' : 'up' ?>"></i>
                                                        <?php } else { ?>
                                                            <i class="fas fa-sort text-muted"></i>
                                                        <?php } ?>
                                                    </a>
                                                </th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($resultats as $resultat) {
                                                $pourcentage = round(($resultat['score'] / $resultat['nbquestions']) * 100, 2);
                                                $badgeClass = $pourcentage >= 80 ? 'bg-success' : ($pourcentage >= 50 ? 'bg-warning' : 'bg-danger');
                                            ?>
                                                <tr>
                                                    <td><?= date('d/m/Y à H:i', strtotime($resultat['dateresultat'])) ?></td>
                                                    <td><?= $resultat['score'] ?> / <?= $resultat['nbquestions'] ?></td>
                                                    <td>
                                                        <span class="badge <?= $badgeClass ?>">
                                                            <?= $pourcentage ?>%
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <p class="text-muted small">
                                        <i class="fas fa-info-circle"></i>
                                        Cliquez sur les en-têtes de colonnes pour trier les résultats.
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

