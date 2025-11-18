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
                            <i class="fas fa-clock"></i> Demander des heures supplémentaires
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

                        <?php if ($aDemandeEnAttente) { ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Attention :</strong> Vous avez déjà une demande en cours de traitement.
                                Veuillez attendre qu'elle soit traitée avant d'en soumettre une nouvelle.
                            </div>
                            <div class="text-center">
                                <a href="/mon-compte/mes-demandes.html" class="btn btn-primary">
                                    <i class="fas fa-list"></i> Voir mes demandes
                                </a>
                                <a href="/mon-compte/planning.html" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour au planning
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Information :</strong> Utilisez ce formulaire pour demander des heures de conduite supplémentaires.
                                Notre équipe étudiera votre demande et vous recontactera dans les plus brefs délais.
                            </div>

                            <form method="POST" action="/mon-compte/demander-heures-supplementaires.html">
                                <div class="mb-4">
                                    <label for="commentaire" class="form-label">
                                        <i class="fas fa-comment"></i> Votre demande <span class="text-danger">*</span>
                                    </label>
                                    <textarea
                                        class="form-control"
                                        id="commentaire"
                                        name="commentaire"
                                        rows="6"
                                        required
                                        placeholder="Décrivez votre besoin en heures supplémentaires (nombre d'heures souhaité, disponibilités, raisons, etc.)"
                                    ></textarea>
                                    <div class="form-text">
                                        Merci de préciser le nombre d'heures souhaité et vos disponibilités.
                                    </div>
                                </div>

                                <div class="alert alert-secondary">
                                    <strong>À savoir :</strong>
                                    <ul class="mb-0">
                                        <li>Votre demande sera étudiée par un administrateur</li>
                                        <li>Vous recevrez un email de confirmation dès réception de votre demande</li>
                                        <li>Un second email vous informera de la validation ou du refus</li>
                                        <li>Le délai de traitement est généralement de 24 à 48 heures</li>
                                    </ul>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Envoyer la demande
                                    </button>
                                    <a href="/mon-compte/planning.html" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

