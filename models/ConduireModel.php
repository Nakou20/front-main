<?php

namespace models;

use PDO;
use models\base\SQL;
use models\InscrireModel;
use utils\SessionHelpers;

/**
 * Champs:
 * - ideleve (int, PK)
 * - idvehicule (int, PK)
 * - idmoniteur (int, PK)
 * - heuredebut (datetime)
 * - lieurdv (varchar)
 */
class ConduireModel extends SQL
{
    public function __construct()
    {
        parent::__construct('conduire', 'ideleve');
    }

    /**
     * Retourne les leçons de conduite pour un élève spécifique.
     * Au format: 
     * [
     *  planning => [{start: "2023-10-01 10:00", end: "2023-10-01 11:00", title: "Leçon de conduite"}],
     *  nbLeconsRestantes => 5,
     *  prochainRdv => "2023-10-05 14:00"
     * ]
     */
    public function getLessonsByEleve(): array
    {
        $idEleve = SessionHelpers::getConnected()['ideleve'] ?? null;

        $stmt = $this->getPdo()->prepare("SELECT * FROM conduire WHERE ideleve = :ideleve ORDER BY heuredebut DESC");
        $stmt->execute([':ideleve' => $idEleve]);
        $lessons = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $planning = [];

        // On Compte le nombre de leçons plannifiées
        foreach ($lessons as $lesson) {
            $planning[] = [
                'start' => $lesson->heuredebut,
                'end' => date('Y-m-d H:i', strtotime($lesson->heuredebut) + 3600), // Durée de 1 heure
                'title' => "Leçon de conduite"
            ];
        }

        // Le prochain rendez-vous est le plus proche dans le futur par rapport à la date actuelle
        $prochainRdv = null;
        $stmt = $this->getPdo()->prepare("SELECT heuredebut FROM conduire WHERE ideleve = :ideleve AND heuredebut > NOW() ORDER BY heuredebut ASC LIMIT 1");
        $stmt->execute([':ideleve' => $idEleve]);
        $nextLesson = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($nextLesson) {
            $prochainRdv = $nextLesson->heuredebut;
        }

        // On récupère le forfait de l'élève pour savoir combien de leçons il lui reste
        $inscrireModel = new InscrireModel();
        $forfaitEleve = $inscrireModel->getForfaitEleveConnecte();

        $nbLeconsRestantes = 0;
        if ($forfaitEleve) {
            // On suppose que le forfait contient un nombre de leçons
            $nbLeconsRestantes = $forfaitEleve->nbheures - count($lessons);
        }

        // Créer la structure de retour (planning, nbLeconsRestantes, prochainRdv)
        return [
            'planning' => $planning,
            'nbLeconsRestantes' => $nbLeconsRestantes,
            'prochainRdv' => $prochainRdv
        ];
    }
}
