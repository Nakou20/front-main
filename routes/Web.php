<?php

namespace routes;

use controllers\CompteController;
use utils\Template;
use routes\base\Route;
use utils\SessionHelpers;
use controllers\PublicWebController;
use controllers\UtilisateurController;
use models\MoniteurModel;

class Web
{
    function __construct()
    {
        $public = new PublicWebController();
        $utilisateur = new UtilisateurController();
        $compte = new CompteController();

        // Appel la méthode « home » dans le contrôleur $main.
        Route::Add('/', [$public, 'home']);
        Route::Add('/forfaits.html', [$public, 'forfait']);
        Route::Add('/a_propos.html', [$public, 'aPropos']);

        // Gestion utilisateur
        Route::Add('/creer-compte.html', [$utilisateur, 'creerCompte']);
        Route::Add('/connexion.html', [$utilisateur, 'connexion']);
        Route::Add('/mot-de-passe-oublie.html', [$utilisateur, 'motDePasseOublie']);

        // Gestion de l'offre
        Route::Add('/activer-offre.html', function () {
            return Template::render('views/global/activer-offre.php');
        });

        // Documentation API
        Route::Add('/documentation-api.html', function () {
            return Template::render('views/global/documentation-api.php');
        });

        // Si l'utilisateur est connecté, ajoute les routes de déconnexion et de compte.
        if (SessionHelpers::isLogin()) {
            Route::Add('/deconnexion.html', [$compte, 'deconnexion']);
            Route::Add('/mon-compte/planning.html', [$compte, 'planning']);
            Route::Add('/mon-compte/profil.html', [$compte, 'mesInformations']);
            Route::Add('/mon-compte/', [$compte, 'monCompte']);
        }

        // Appel la fonction inline dans le routeur.
        // Utile pour du code très simple, où un tes, l'utilisation d'un contrôleur est préférable.
        /* Route::Add('/about', function () {
            return Template::render('views/global/about.php');
        }); */
        Route:: Add('/equipe.html', function () {
            $moniteurs = (new \models\MoniteurModel())->getAll();
            $vehicules = (new \models\VehiculeModel())->getAll();

            return Template::render('views/global/equipe.php', [
                'teamMembers' => $moniteurs,
                'vehicles' => $vehicules
            ]);

        });
    }
}
