<main class="container mt-5 pt-5 mb-5">
    <section id="creer-compte-form" class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center mb-4">
                    <img src="/public/images/logo_cds49.jpeg" alt="Logo CDS 49" style="max-width: 150px; border-radius: 10px;">
                </div>
                <h2 class="display-5 fw-light text-center mb-4">Inscription</h2>

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

                <form method="POST" action="creer-compte.html">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="numeroeleve" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="numeroeleve" name="numeroeleve" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Afficher/Masquer le mot de passe">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">
                            Minimum 8 caractères, 1 chiffre et 1 caractère spécial requis.
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirmer le mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" title="Afficher/Masquer le mot de passe">
                                <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                    </div>
                    <div class="mb-3">
                        <label for="idforfait" class="form-label">Sélectionner un forfait (optionnel)</label>
                        <select class="form-control" id="idforfait" name="idforfait">
                            <option value="">-- Aucun forfait pour le moment --</option>
                            <?php if (isset($forfaits) && !empty($forfaits)) { ?>
                                <?php foreach ($forfaits as $forfait) { ?>
                                    <option value="<?= $forfait['idforfait']; ?>">
                                        <?= htmlspecialchars($forfait['libelleforfait']); ?> -
                                        <?php
                                        if ($forfait['prixforfait']) {
                                            echo $forfait['prixforfait'] . " €";
                                        } elseif ($forfait['prixhoraire']) {
                                            echo $forfait['prixhoraire'] . " € / heure";
                                        } else {
                                            echo "Prix sur demande";
                                        }
                                        ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <small class="form-text text-muted">
                            Vous pouvez sélectionner un forfait maintenant ou le faire plus tard depuis votre espace personnel.
                        </small>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Créer mon compte</button>
                    </div>
                </form>
                <p class="text-center mt-4">
                    Déjà un compte ? <a href="connexion.html">Connectez-vous ici</a>.
                </p>
            </div>
        </div>
    </section>
</main>

<script>
    // Gestion de l'affichage/masquage du mot de passe
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });

    // Gestion de l'affichage/masquage du mot de passe de confirmation
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const confirmPasswordField = document.getElementById('confirm-password');
        const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');

        if (confirmPasswordField.type === 'password') {
            confirmPasswordField.type = 'text';
            toggleConfirmIcon.classList.remove('fa-eye');
            toggleConfirmIcon.classList.add('fa-eye-slash');
        } else {
            confirmPasswordField.type = 'password';
            toggleConfirmIcon.classList.remove('fa-eye-slash');
            toggleConfirmIcon.classList.add('fa-eye');
        }
    });
</script>
