<?php

namespace controllers;

use utils\Template;
use models\EleveModel;
use models\InscrireModel;
use utils\SessionHelpers;
use controllers\base\WebController;
use models\ConduireModel;
use models\ResultatModel;
use models\DemandeModel;
use utils\EmailUtils;

class CompteController extends WebController
{
    private EleveModel $eleveModel;
    private InscrireModel $inscrireModel;
    private ConduireModel $conduireModel;
    private ResultatModel $resultatModel;
    private DemandeModel $demandeModel;

    public function __construct()
    {
        $this->eleveModel = new EleveModel();
        $this->inscrireModel = new InscrireModel();
        $this->conduireModel = new ConduireModel();
        $this->resultatModel = new ResultatModel();
        $this->demandeModel = new DemandeModel();
    }

    /**
     * Affiche le compte utilisateur.
     *
     * @return void
     */
    public function monCompte(): void
    {
        $this->redirect('/mon-compte/planning.html');
    }

    public function mesInformations(): string
    {
        if ($this->isPost()) {
            $nom = $_POST['nom'] ?? null;
            $prenom = $_POST['prenom'] ?? null;
            $email = $_POST['email'] ?? null;
            $dateNaissance = $_POST['datenaissance'] ?? null;
            $numeroeleve = $_POST['numeroeleve'] ?? null;
            if (empty($nom) || empty($prenom) || empty($email) || empty($dateNaissance)) {
                SessionHelpers::setFlashMessage('error', 'Tous les champs sont requis.');
                $this->redirect('/mon-compte/profil.html');
            }

            $success = $this->eleveModel->update(
                SessionHelpers::getConnected()['ideleve'],
                $nom,
                $prenom,
                $email,
                $dateNaissance,
                null,
                $numeroeleve
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
            ] + $this->eleveModel->getMe()
        );
    }

    /**
     * Affiche le planning de l'utilisateur connecté.
     */
    public function planning(): string
    {

        $forfait = $this->inscrireModel->getForfaitEleveConnecte();


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


        $planning = $this->conduireModel->getLessonsByEleve();


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

        $leconId = $_GET['lecon_id'] ?? null;

        if (!$leconId) {
            SessionHelpers::setFlashMessage('error', 'Leçon introuvable.');
            $this->redirect('/mon-compte/planning.html');
        }


        $parts = explode('-', $leconId);
        if (count($parts) !== 4) {
            SessionHelpers::setFlashMessage('error', 'Identifiant de leçon invalide.');
            $this->redirect('/mon-compte/planning.html');
        }

        list($idEleve, $idVehicule, $idMoniteur, $timestamp) = $parts;
        $heureDebut = date('Y-m-d H:i:s', (int)$timestamp);


        $eleveConnecte = SessionHelpers::getConnected();
        if ($eleveConnecte['ideleve'] != $idEleve) {
            SessionHelpers::setFlashMessage('error', 'Vous n\'avez pas accès à cette leçon.');
            $this->redirect('/mon-compte/planning.html');
        }


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
    public function annulerLecon(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/mon-compte/planning.html');
        }

        $leconId = $_POST['lecon_id'] ?? null;

        if (!$leconId) {
            SessionHelpers::setFlashMessage('error', 'Leçon introuvable.');
            $this->redirect('/mon-compte/planning.html');
        }


        $parts = explode('-', $leconId);
        if (count($parts) !== 4) {
            SessionHelpers::setFlashMessage('error', 'Identifiant de leçon invalide.');
            $this->redirect('/mon-compte/planning.html');
        }

        list($idEleve, $idVehicule, $idMoniteur, $timestamp) = $parts;
        $heureDebut = date('Y-m-d H:i:s', (int)$timestamp);


        $eleveConnecte = SessionHelpers::getConnected();
        if ($eleveConnecte['ideleve'] != $idEleve) {
            SessionHelpers::setFlashMessage('error', 'Vous n\'avez pas accès à cette leçon.');
            $this->redirect('/mon-compte/planning.html');
        }


        if (!$this->conduireModel->canCancelLesson($heureDebut)) {
            SessionHelpers::setFlashMessage('error', 'Cette leçon ne peut plus être annulée (moins de 48h avant le début).');
            $this->redirect('/mon-compte/planning.html');
        }


        $success = $this->conduireModel->cancelLesson(
            (int)$idEleve,
            (int)$idMoniteur,
            (int)$idVehicule,
            $heureDebut
        );

        if ($success) {

            $lecon = $this->conduireModel->getLeconDetails(
                (int)$idEleve,
                (int)$idMoniteur,
                (int)$idVehicule,
                $heureDebut
            );




            SessionHelpers::setFlashMessage('success', 'Votre leçon a été annulée avec succès. Un email de confirmation vous a été envoyé.');
        } else {
            SessionHelpers::setFlashMessage('error', 'Une erreur est survenue lors de l\'annulation de la leçon.');
        }

        $this->redirect('/mon-compte/planning.html');
    }

    public function mesResultats(): string
    {
        $eleveConnecte = SessionHelpers::getConnected();
        $idEleve = $eleveConnecte['ideleve'];

        $orderBy = $_GET['tri'] ?? 'date';
        $orderDirection = $_GET['ordre'] ?? 'DESC';

        $resultats = $this->resultatModel->getResultatsByEleve($idEleve, $orderBy, $orderDirection);

        return Template::render(
            "views/utilisateur/compte/mes-resultats.php",
            [
                'titre' => 'Mes Résultats',
                'resultats' => $resultats,
                'eleve' => $eleveConnecte,
                'tri' => $orderBy,
                'ordre' => $orderDirection,
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }

    /**
     * Demande des heures supplémentaires
     */
    public function demanderHeuresSupplementaires(): string
    {
        $eleveConnecte = SessionHelpers::getConnected();
        $idEleve = $eleveConnecte['ideleve'];

        $aDemandeEnAttente = $this->demandeModel->aDemandeEnAttente($idEleve);

        if ($this->isPost()) {
            $commentaire = $_POST['commentaire'] ?? '';

            if (empty($commentaire)) {
                SessionHelpers::setFlashMessage('error', 'Veuillez remplir le commentaire pour expliquer votre besoin.');
                $this->redirect('/mon-compte/demander-heures-supplementaires.html');
            }

            if ($aDemandeEnAttente) {
                SessionHelpers::setFlashMessage('error', 'Vous avez déjà une demande en attente de traitement.');
                $this->redirect('/mon-compte/demander-heures-supplementaires.html');
            }

            // Créer la demande
            $demandeId = $this->demandeModel->creerDemandeHeuresSupplementaires($idEleve, $commentaire);

            if ($demandeId) {
                // Envoyer un email de confirmation à l'élève
                $eleve = $this->eleveModel->getMe();

                SessionHelpers::setFlashMessage('success', 'Votre demande d\'heures supplémentaires a été envoyée avec succès. Un email de confirmation vous a été envoyé.');
                $this->redirect('/mon-compte/planning.html');
            } else {
                SessionHelpers::setFlashMessage('error', 'Une erreur est survenue lors de l\'envoi de votre demande.');
                $this->redirect('/mon-compte/demander-heures-supplementaires.html');
            }
        }

        return Template::render(
            "views/utilisateur/compte/demander-heures-supplementaires.php",
            [
                'titre' => 'Demander des heures supplémentaires',
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success'),
                'aDemandeEnAttente' => $aDemandeEnAttente
            ]
        );
    }

    /**
     * Affiche l'historique des demandes d'heures supplémentaires
     */
    public function mesDemandes(): string
    {
        $eleveConnecte = SessionHelpers::getConnected();
        $idEleve = $eleveConnecte['ideleve'];

        $demandes = $this->demandeModel->getDemandesEleve($idEleve);

        return Template::render(
            "views/utilisateur/compte/mes-demandes.php",
            [
                'titre' => 'Mes demandes',
                'demandes' => $demandes,
                'eleve' => $eleveConnecte,
                'error' => SessionHelpers::getFlashMessage('error'),
                'success' => SessionHelpers::getFlashMessage('success')
            ]
        );
    }
}
