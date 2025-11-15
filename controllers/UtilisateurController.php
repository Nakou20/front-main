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

            // Valider et normaliser le format de la date
            $date = \DateTime::createFromFormat('Y-m-d', $dateNaissance);
            if (!$date || $date->format('Y-m-d') !== $dateNaissance) {
                SessionHelpers::setFlashMessage('error', 'Format de date invalide. Utilisez le format JJ/MM/AAAA.');
                $this->redirect('/creer-compte.html');
            }

            if ($password !== $confirmPassword) {
                SessionHelpers::setFlashMessage('error', 'Les mots de passe ne correspondent pas.');
                $this->redirect('/creer-compte.html');
            }

            // Récupérer le PEPPER depuis la configuration
            $config = include("configs.php");
            $pepper = $config['PEPPER'];

            // Création de l'élève dans la base de données (en utilisant le modèle EleveModel).
            $hashed_password = password_hash($password.$pepper, PASSWORD_DEFAULT);
            $success = $this->eleveModel->creer_eleve($nom, $prenom, $email, $hashed_password, $dateNaissance, $numero);

            if ($success) {
                $this->redirect('/');
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
            $email = $_POST['email'] ?? null;

            if (empty($email)) {
                SessionHelpers::setFlashMessage('error', 'Veuillez saisir votre adresse email.');
                $this->redirect('/mot-de-passe-oublie.html');
            }

            // Vérifier si un compte existe avec cet email
            $eleve = $this->eleveModel->getByEmail($email);

            if ($eleve) {
                // Créer une demande de réinitialisation avec un token unique
                $token = $this->demandeReinitialisationModel->createResetRequest($email);

                if ($token) {
                    // Récupérer l'URL de base depuis la configuration
                    $config = include("configs.php");
                    $baseUrl = $config['URL_BASE'];

                    // Construire le lien de réinitialisation
                    $resetLink = $baseUrl . 'reinitialiser-mot-de-passe.html?token=' . $token;

                    // Envoyer l'email de réinitialisation avec le template
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
                        // Log l'erreur en mode debug
                        if ($config['DEBUG']) {
                            error_log("Échec de l'envoi d'email de réinitialisation pour: " . $email);
                        }
                        SessionHelpers::setFlashMessage('error', 'Une erreur est survenue lors de l\'envoi de l\'email. Veuillez réessayer ou contacter l\'administrateur.');
                    }
                } else {
                    SessionHelpers::setFlashMessage('error', 'Une erreur est survenue. Veuillez réessayer.');
                }
            } else {
                // Pour des raisons de sécurité, on affiche le même message même si l'email n'existe pas
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

        // Vérifier la validité du token
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

            // Récupérer le PEPPER depuis la configuration
            $config = include("configs.php");
            $pepper = $config['PEPPER'];

            // Mettre à jour le mot de passe
            $hashedPassword = password_hash($password . $pepper, PASSWORD_DEFAULT);
            $success = $this->eleveModel->updatePasswordByEmail($demande->emaileleve, $hashedPassword);

            if ($success) {
                // Marquer le token comme utilisé
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
