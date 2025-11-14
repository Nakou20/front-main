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
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <small class="form-text text-muted">Le mot de passe doit contenir au moins 8 caractères.</small>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
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

