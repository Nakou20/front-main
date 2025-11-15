<?php

namespace controllers;

use utils\Template;
use models\EleveModel;
use models\InscrireModel;
use utils\SessionHelpers;
use controllers\base\WebController;
use models\ForfaitModel;
use models\DemandeReinitialisationModel;
use utils\EmailUtils;

class UtilisateurController extends WebController
{
    private EleveModel $eleveModel;
    private ForfaitModel $forfaitModel;
    private DemandeReinitialisationModel $demandeReinitialisationModel;

    function __construct()
    {
        $this->eleveModel = new EleveModel();
        $this->forfaitModel = new ForfaitModel();
        $this->demandeReinitialisationModel = new DemandeReinitialisationModel();
    }

    /**
     * Affiche le formulaire de création de compte et gère la soumission du formulaire.
     *
     * @return string
     */
    public function creerCompte(): string
    {
        if (SessionHelpers::isLogin()) {
            $this->redirect('/home');
        }


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

            if (!empty($numero)) {
                if (!preg_match('/^\d{8}$/', $numero)) {
                    SessionHelpers::setFlashMessage('error', 'Le numéro de téléphone doit contenir exactement 8 chiffres.');
                    $this->redirect('/creer-compte.html');
                }
            }

            $date = \DateTime::createFromFormat('Y-m-d', $dateNaissance);
            if (!$date || $date->format('Y-m-d') !== $dateNaissance) {
                SessionHelpers::setFlashMessage('error', 'Format de date invalide. Utilisez le format JJ/MM/AAAA.');
                $this->redirect('/creer-compte.html');
            }

            if (strlen($password) < 8) {
                SessionHelpers::setFlashMessage('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                $this->redirect('/creer-compte.html');
            }

            if (!preg_match('/[0-9]/', $password)) {
                SessionHelpers::setFlashMessage('error', 'Le mot de passe doit contenir au moins 1 chiffre.');
                $this->redirect('/creer-compte.html');
            }

            if (!preg_match('/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\\\/~`]/', $password)) {
                SessionHelpers::setFlashMessage('error', 'Le mot de passe doit contenir au moins 1 caractère spécial.');
                $this->redirect('/creer-compte.html');
            }

            if ($password !== $confirmPassword) {
                SessionHelpers::setFlashMessage('error', 'Les mots de passe ne correspondent pas.');
                $this->redirect('/creer-compte.html');
            }
            $config = include("configs.php");
            $pepper = $config['PEPPER'];

            $hashed_password = password_hash($password.$pepper, PASSWORD_DEFAULT);
            $success = $this->eleveModel->creer_eleve($nom, $prenom, $email, $hashed_password, $dateNaissance, $numero);

            if ($success) {
                $this->redirect('/home');
            } else {
                SessionHelpers::setFlashMessage('error', "L'adresse email est déjà utilisée ou une erreur est survenue.");
                $this->redirect('/creer-compte.html');
            }
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
        if (SessionHelpers::isLogin()) {
            $this->redirect('/mon-compte/');
        }

        if ($this->isPost()) {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (empty($email) || empty($password)) {
                SessionHelpers::setFlashMessage('error', 'Tous les champs sont requis.');
                $this->redirect('/connexion.html');
            }

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
            $email = $_POST['email'] ?? null;

            if (empty($email)) {
                SessionHelpers::setFlashMessage('error', 'Veuillez saisir votre adresse email.');
                $this->redirect('/mot-de-passe-oublie.html');
            }

            $eleve = $this->eleveModel->getByEmail($email);

            if ($eleve) {
                $token = $this->demandeReinitialisationModel->createResetRequest($email);

                if ($token) {
                    $config = include("configs.php");
                    $baseUrl = $config['URL_BASE'];

                    $resetLink = $baseUrl . 'reinitialiser-mot-de-passe.html?token=' . $token;

                    $emailSent = EmailUtils::sendEmail(
                        $email,
                        'Réinitialisation de votre mot de passe',
                        'reinitialisation_mot_de_passe',
                        [
                            'resetLink' => $resetLink
                        ]
                    );

                    if ($emailSent) {
                        SessionHelpers::setFlashMessage('success', 'Un email de réinitialisation a été envoyé à votre adresse.');
                    } else {
                        if ($config['DEBUG']) {
                            error_log("Échec de l'envoi d'email de réinitialisation pour: " . $email);
                        }
                        SessionHelpers::setFlashMessage('error', 'Une erreur est survenue lors de l\'envoi de l\'email. Veuillez réessayer ou contacter l\'administrateur.');
                    }
                } else {
                    SessionHelpers::setFlashMessage('error', 'Une erreur est survenue. Veuillez réessayer.');
                }
            } else {
                SessionHelpers::setFlashMessage('success', 'Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.');
            }

            $this->redirect('/mot-de-passe-oublie.html');
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
     * Affiche le formulaire de réinitialisation du mot de passe
     */
    public function reinitialiserMotDePasse(): string
    {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            SessionHelpers::setFlashMessage('error', 'Token de réinitialisation manquant.');
            $this->redirect('/connexion.html');
        }

        $demande = $this->demandeReinitialisationModel->validateToken($token);

        if (!$demande) {
            SessionHelpers::setFlashMessage('error', 'Le lien de réinitialisation est invalide ou a expiré.');
            $this->redirect('/mot-de-passe-oublie.html');
        }

        if ($this->isPost()) {
            $password = $_POST['password'] ?? null;
            $confirmPassword = $_POST['confirm_password'] ?? null;

            if (empty($password) || empty($confirmPassword)) {
                SessionHelpers::setFlashMessage('error', 'Tous les champs sont requis.');
                $this->redirect('/reinitialiser-mot-de-passe.html?token=' . $token);
            }

            if ($password !== $confirmPassword) {
                SessionHelpers::setFlashMessage('error', 'Les mots de passe ne correspondent pas.');
                $this->redirect('/reinitialiser-mot-de-passe.html?token=' . $token);
            }

            $config = include("configs.php");
            $pepper = $config['PEPPER'];

            $hashedPassword = password_hash($password . $pepper, PASSWORD_DEFAULT);
            $success = $this->eleveModel->updatePasswordByEmail($demande->emaileleve, $hashedPassword);

            if ($success) {
                $this->demandeReinitialisationModel->markTokenAsUsed($token);

                SessionHelpers::setFlashMessage('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
                $this->redirect('/connexion.html');
            } else {
                SessionHelpers::setFlashMessage('error', 'Une erreur est survenue lors de la réinitialisation du mot de passe.');
                $this->redirect('/reinitialiser-mot-de-passe.html?token=' . $token);
            }
        }

        return Template::render(
            "views/utilisateur/reinitialiser-mot-de-passe.php",
            [
                'titre' => 'Réinitialiser mon mot de passe',
                'token' => $token,
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }

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
