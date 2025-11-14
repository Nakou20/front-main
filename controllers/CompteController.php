<?php

namespace controllers;

use utils\Template;
use models\EleveModel;
use models\InscrireModel;
use utils\SessionHelpers;
use controllers\base\WebController;
use models\ConduireModel;

class CompteController extends WebController
{
    private EleveModel $eleveModel;
    private InscrireModel $inscrireModel;
    private ConduireModel $conduireModel;

    public function __construct()
    {
        $this->eleveModel = new EleveModel();
        $this->inscrireModel = new InscrireModel();
        $this->conduireModel = new ConduireModel();
    }

    /**
     * Affiche le compte utilisateur.
     *
     * @return string
     */
    public function monCompte(): string
    {
        $this->redirect('/mon-compte/planning.html');
    }

    public function mesInformations(): string
    {
        if ($this->isPost()) {
            // Traitement de la mise à jour des informations de l'utilisateur
            $nom = $_POST['nom'] ?? null;
            $prenom = $_POST['prenom'] ?? null;
            $email = $_POST['email'] ?? null;
            $dateNaissance = $_POST['datenaissance'] ?? null;
            $numero = $_POST['numeroeleve'] ?? null;
            if (empty($nom) || empty($prenom) || empty($email) || empty($dateNaissance)) {
                SessionHelpers::setFlashMessage('error', 'Tous les champs sont requis.');
                $this->redirect('/mon-compte/profil.html');
            }

            // Mise à jour des informations de l'utilisateur dans la base de données
            $success = $this->eleveModel->update(
                SessionHelpers::getConnected()['ideleve'],
                $nom,
                $prenom,
                $email,
                $dateNaissance,
                null,
                $numero
            );

            if ($success) {
                SessionHelpers::setFlashMessage('success', 'Vos informations ont été mises à jour avec succès.');
            } else {
                SessionHelpers::setFlashMessage('error', "Une erreur est survenue lors de la mise à jour de vos informations.");
            }
        }

        return template::render(
            "views/utilisateur/compte/mes-informations.php",
            [
                'titre' => 'Mes informations',
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success'),
            ] + $this->eleveModel->getMe() // Ajoute l'ensemble des informations de l'utilisateur connecté (concatène les données de l'utilisateur connecté + les données de la vue, résultat un tableau associatif)
        );
    }

    /**
     * Affiche le planning de l'utilisateur connecté.
     */
    public function planning(): string
    {
        // Récupération du forfait de l'utilisateur connecté
        $forfait = $this->inscrireModel->getForfaitEleveConnecte();

        // Récupération du planning de l'utilisateur connecté (modèle conduire)
        $planning = $this->conduireModel->getLessonsByEleve();

        return Template::render(
            "views/utilisateur/compte/planning.php",
            [
                'titre' => 'Mon planning',
                'forfait' => $forfait,
                'planning' => $planning,
                'eleve' => SessionHelpers::getConnected(),
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }

    /**
     * Déconnecte l'utilisateur.
     *
     * @return void
     */
    public function deconnexion(): void
    {
        SessionHelpers::logout();
        $this->redirect('/');
    }

    /**
     * Retourne le planning de l'utilisateur au format JSON pour FullCalendar.
     *
     * @return string JSON
     */
    public function getPlanningJson(): string
    {
        header('Content-Type: application/json');

        // Récupération du planning de l'utilisateur connecté
        $planning = $this->conduireModel->getLessonsByEleve();

        // Formater les événements pour FullCalendar
        $events = [];
        foreach ($planning['planning'] as $lecon) {
            $events[] = [
                'id' => $lecon['id'],
                'title' => $lecon['title'],
                'start' => date('Y-m-d\TH:i:s', strtotime($lecon['start'])),
                'end' => date('Y-m-d\TH:i:s', strtotime($lecon['end']))
            ];
        }

        return json_encode($events);
    }

    /**
     * Affiche les détails d'une leçon
     */
    public function detailsLecon(): string
    {
        // Récupération des paramètres
        $leconId = $_GET['lecon_id'] ?? null;

        if (!$leconId) {
            SessionHelpers::setFlashMessage('error', 'Leçon introuvable.');
            $this->redirect('/mon-compte/planning.html');
        }

        // Décoder l'ID de la leçon (format: ideleve-idvehicule-idmoniteur-timestamp)
        $parts = explode('-', $leconId);
        if (count($parts) !== 4) {
            SessionHelpers::setFlashMessage('error', 'Identifiant de leçon invalide.');
            $this->redirect('/mon-compte/planning.html');
        }

        list($idEleve, $idVehicule, $idMoniteur, $timestamp) = $parts;
        $heureDebut = date('Y-m-d H:i:s', (int)$timestamp);

        // Vérifier que la leçon appartient bien à l'utilisateur connecté
        $eleveConnecte = SessionHelpers::getConnected();
        if ($eleveConnecte['ideleve'] != $idEleve) {
            SessionHelpers::setFlashMessage('error', 'Vous n\'avez pas accès à cette leçon.');
            $this->redirect('/mon-compte/planning.html');
        }

        // Récupération des détails de la leçon
        $lecon = $this->conduireModel->getLeconDetails(
            (int)$idEleve,
            (int)$idMoniteur,
            (int)$idVehicule,
            $heureDebut
        );

        if (!$lecon) {
            SessionHelpers::setFlashMessage('error', 'Leçon introuvable.');
            $this->redirect('/mon-compte/planning.html');
        }

        // Vérifier si la leçon peut être annulée
        $canCancel = $this->conduireModel->canCancelLesson($lecon->heuredebut);

        return Template::render(
            "views/utilisateur/compte/details_lecon.php",
            [
                'titre' => 'Détails de la leçon',
                'lecon' => $lecon,
                'canCancel' => $canCancel,
                'eleve' => $eleveConnecte,
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }

    /**
     * Annule une leçon
     */
    public function annulerLecon(): string
    {
        if (!$this->isPost()) {
            $this->redirect('/mon-compte/planning.html');
        }

        $leconId = $_POST['lecon_id'] ?? null;

        if (!$leconId) {
            SessionHelpers::setFlashMessage('error', 'Leçon introuvable.');
            $this->redirect('/mon-compte/planning.html');
        }

        // Décoder l'ID de la leçon
        $parts = explode('-', $leconId);
        if (count($parts) !== 4) {
            SessionHelpers::setFlashMessage('error', 'Identifiant de leçon invalide.');
            $this->redirect('/mon-compte/planning.html');
        }

        list($idEleve, $idVehicule, $idMoniteur, $timestamp) = $parts;
        $heureDebut = date('Y-m-d H:i:s', (int)$timestamp);

        // Vérifier que la leçon appartient bien à l'utilisateur connecté
        $eleveConnecte = SessionHelpers::getConnected();
        if ($eleveConnecte['ideleve'] != $idEleve) {
            SessionHelpers::setFlashMessage('error', 'Vous n\'avez pas accès à cette leçon.');
            $this->redirect('/mon-compte/planning.html');
        }

        // Vérifier si la leçon peut être annulée
        if (!$this->conduireModel->canCancelLesson($heureDebut)) {
            SessionHelpers::setFlashMessage('error', 'Cette leçon ne peut plus être annulée (moins de 48h avant le début).');
            $this->redirect('/mon-compte/planning.html');
        }

        // Annuler la leçon
        $success = $this->conduireModel->cancelLesson(
            (int)$idEleve,
            (int)$idMoniteur,
            (int)$idVehicule,
            $heureDebut
        );

        if ($success) {
            // Envoyer un email de confirmation
            $lecon = $this->conduireModel->getLeconDetails(
                (int)$idEleve,
                (int)$idMoniteur,
                (int)$idVehicule,
                $heureDebut
            );

            // TODO: Envoyer l'email de confirmation d'annulation
            // EmailUtils::sendEmail(...);

            SessionHelpers::setFlashMessage('success', 'Votre leçon a été annulée avec succès. Un email de confirmation vous a été envoyé.');
        } else {
            SessionHelpers::setFlashMessage('error', 'Une erreur est survenue lors de l\'annulation de la leçon.');
        }

        $this->redirect('/mon-compte/planning.html');
    }
}
