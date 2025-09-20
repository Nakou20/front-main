<main class="container mt-5 pt-5 mb-5">
    <section id="mot-de-passe-oublie-form" class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center mb-4">
                    <img src="/public/images/logo_cds49.jpeg" alt="Logo CDS 49" style="max-width: 150px; border-radius: 10px;">
                </div>
                <h2 class="display-5 fw-light text-center mb-4">Mot de passe oublié</h2>
                <form method="POST" action="mot-de-passe-oublie.html">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Entrez votre adresse email">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Réinitialiser le mot de passe</button>
                    </div>
                </form>
                <p class="text-center mt-4">
                    Vous vous souvenez de votre mot de passe ? <a href="connexion.html">Connectez-vous ici</a>.
                </p>
                <p class="text-center mt-3">
                    Pas encore de compte ? <a href="creer-compte.html">Inscrivez-vous</a>.
                </p>
            </div>
        </div>
    </section>
</main>