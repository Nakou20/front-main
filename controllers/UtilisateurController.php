<?php

namespace controllers;

use utils\Template;
use models\EleveModel;
use models\InscrireModel;
use utils\SessionHelpers;
use controllers\base\WebController;
use models\ForfaitModel;

class UtilisateurController extends WebController
{
    private EleveModel $eleveModel;
    private ForfaitModel $forfaitModel;

    function __construct()
    {
        $this->eleveModel = new EleveModel();
        $this->forfaitModel = new ForfaitModel();
    }

    /**
     * Affiche le formulaire de création de compte et gère la soumission du formulaire.
     *
     * @return string
     */
    public function creerCompte(): string
    {
        // Si l'utilisateur est déjà connecté, redirige vers la page de compte.
        if (SessionHelpers::isLogin()) {
            $this->redirect('/mon-compte/');
        }

        // Si la requête est de type POST, traite la soumission du formulaire.
        if ($this->isPost()) {
            $nom = $_POST['nom'] ?? null;
            $prenom = $_POST['prenom'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $confirmPassword = $_POST['confirm_password'] ?? null;
            $dateNaissance = $_POST['date_naissance'] ?? null;
            $numero = $_POST['numeroeleve'] ?? null;

            if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirmPassword) || empty($dateNaissance)) 
            {
                SessionHelpers::setFlashMessage('error', 'Tous les champs sont requis.');
                $this->redirect('/creer-compte.html');
            }

            if ($password !== $confirmPassword) {
                SessionHelpers::setFlashMessage('error', 'Les mots de passe ne correspondent pas.');
            } else {
                // Création de l'élève dans la base de données (en utilisant le modèle EleveModel).

                $hashed_password = password_hash($password.$_ENV['PEPPER'], PASSWORD_DEFAULT);
                $success = $this->eleveModel->creer_eleve($nom, $prenom, $email, $hashed_password, $dateNaissance, $numero);

                if ($success) {
                    $this->redirect('/mon-compte/');
                } else {
                    SessionHelpers::setFlashMessage('error', "L'adresse email est déjà utilisée ou une erreur est survenue.");
                }
            }
            // Rediriger vers la page de création de compte pour afficher le message d'erreur ou si la création a échoué
            $this->redirect('/creer-compte.html');
        }

        return Template::render(
            "views/utilisateur/creer-compte.php",
            [
                'titre' => 'Créer un compte',
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }

    /**
     * Affiche le formulaire de connexion.
     *
     * @return string
     */
    public function connexion(): string
    {
        // Si l'utilisateur est déjà connecté, redirige vers la page de compte.
        if (SessionHelpers::isLogin()) {
            $this->redirect('/mon-compte/');
        }

        // Si la requête est de type POST, traite la soumission du formulaire.
        if ($this->isPost()) {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (empty($email) || empty($password)) {
                SessionHelpers::setFlashMessage('error', 'Tous les champs sont requis.');
                $this->redirect('/connexion.html');
            }

            // Vérification des identifiants de l'utilisateur (en utilisant le modèle EleveModel).
            $eleve = $this->eleveModel->connexion($email, $password.$_ENV['PEPPER']);

            if ($eleve) {
                $this->redirect('/mon-compte/');
            } else {
                SessionHelpers::setFlashMessage('error', 'Identifiants incorrects.');
                $this->redirect('/connexion.html');
            }
        }

        return Template::render("views/utilisateur/connexion.php", [
            'titre' => 'Connexion',
            'error' => SessionHelpers::getFlashMessage('error'),
            'success' => SessionHelpers::getFlashMessage('success')
        ]);
    }

    /**
     * Affiche le formulaire de mot de passe oublié.
     *
     * @return string
     */
    public function motDePasseOublie(): string
    {
        if ($this->isPost()) {
            // TODO: Implémenter la logique de réinitialisation du mot de passe.
            // La réinitialisation va se dérouler en plusieurs étapes :
            // 1. L'utilisateur entre son adresse email.
            // 2. Un email de réinitialisation est envoyé avec un lien unique. (à implémenter, implique la création d'un token de réinitialisation à stocker en base de données. Un token est un identifiant UUID associé à l'utilisateur, avec une date d'expiration.)
            // 3. L'utilisateur clique sur le lien et est redirigé vers un formulaire pour entrer un nouveau mot de passe. (à implémenter, création d'une page + récupération du token de réinitialisation)
            // 4. Le mot de passe est mis à jour dans la base de données. (à implémenter)
        }

        return Template::render(
            "views/utilisateur/mot-de-passe-oublie.php",
            [
                'titre' => 'Mot de passe oublié',
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }

    /**
     * Code d'activation d'une offre.
     * Cette méthode permet à l'utilisateur d'activer l'offre choisi (passage de paramètre dans l'URL).
     */
    public function activerOffre(): string
    {
        $idForfait = $_GET['idforfait'] ?? null;

        $forfait = $this->forfaitModel->getById($idForfait);

        if (!$forfait) {
            $this->redirect('/forfaits.html');
        }

        return Template::render(
            "views/utilisateur/activer-offre.php",
            [
                'titre' => 'Activer une offre',
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }
}
