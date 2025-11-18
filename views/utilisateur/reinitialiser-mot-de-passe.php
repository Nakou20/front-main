<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Réinitialiser mon mot de passe</h3>
                </div>
                <div class="card-body">
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

                    <p class="text-muted">Veuillez saisir votre nouveau mot de passe.</p>

                    <form method="POST" action="/reinitialiser-mot-de-passe.html?token=<?= htmlspecialchars($token) ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Afficher/Masquer le mot de passe">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                Le mot de passe doit contenir au moins :
                                <ul class="mb-0">
                                    <li>8 caractères</li>
                                    <li>1 chiffre</li>
                                    <li>1 caractère spécial</li>
                                </ul>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" title="Afficher/Masquer le mot de passe">
                                    <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Réinitialiser mon mot de passe</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/connexion.html">Retour à la connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        const confirmPasswordField = document.getElementById('confirm_password');
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
