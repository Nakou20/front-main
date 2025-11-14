<?php

namespace routes;

use controllers\CompteController;
use utils\Template;
use routes\base\Route;
use utils\SessionHelpers;
use controllers\PublicWebController;
use controllers\UtilisateurController;

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
        Route::Add('/equipe.html', [$public, 'equipe']);

        // Gestion utilisateur
        Route::Add('/creer-compte.html', [$utilisateur, 'creerCompte']);
        Route::Add('/connexion.html', [$utilisateur, 'connexion']);
        Route::Add('/mot-de-passe-oublie.html', [$utilisateur, 'motDePasseOublie']);
        Route::Add('/reinitialiser-mot-de-passe.html', [$utilisateur, 'reinitialiserMotDePasse']);

        // Gestion de l'offre
        Route::Add('/activer-offre.html', [$utilisateur, 'activerOffre']);

        // Documentation API
        Route::Add('/documentation-api.html', function () {
            return Template::render('views/global/documentation-api.php');
        });

        // Si l'utilisateur est connecté, ajoute les routes de déconnexion et de compte.
        if (SessionHelpers::isLogin()) {
            Route::Add('/deconnexion.html', [$compte, 'deconnexion']);
            Route::Add('/mon-compte/planning.html', [$compte, 'planning']);
            Route::Add('/mon-compte/planning.json', [$compte, 'getPlanningJson']);
            Route::Add('/mon-compte/planning/details_lecon.html', [$compte, 'detailsLecon']);
            Route::Add('/mon-compte/planning/annuler_lecon.html', [$compte, 'annulerLecon']);
            Route::Add('/mon-compte/profil.html', [$compte, 'mesInformations']);
            Route::Add('/mon-compte/', [$compte, 'monCompte']);
        }

        // Appel la fonction inline dans le routeur.
        // Utile pour du code très simple, où un tes, l'utilisation d'un contrôleur est préférable.
        /* Route::Add('/about', function () {
            return Template::render('views/global/about.php');
        }); */
    }
}
