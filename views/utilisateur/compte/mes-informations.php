<main class="container pt-4">
    <section id="espace-connecte">
        <h1 class="mb-4">Mon Espace</h1>

        <div class="row">
            <?php

            $page_active = 'profil';
            include '_sidebar_compte.php';
            ?>

            <div class="col-md-9">
                <div class="tab-content">

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

                    <form method="POST" action="/mon-compte/profil.html">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars(isset($nomeleve) ? $nomeleve : ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars(isset($prenomeleve) ? $prenomeleve : ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="numeroeleve" class="form-label">Numéro de téléphone</label>
                            <input type="text" class="form-control" id="numeroeleve" name="numeroeleve" value="<?= htmlspecialchars(isset($numeroeleve) ? $numeroeleve : ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars(isset($emaileleve) ? $emaileleve : ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="datenaissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="datenaissance" name="datenaissance" value="<?= htmlspecialchars(isset($datenaissanceeleve) ? $datenaissanceeleve : ''); ?>">
                        </div>
                        <div class="text-center pt-3">
                            <button type="submit" class="btn btn-primary">Mettre à jour le profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>