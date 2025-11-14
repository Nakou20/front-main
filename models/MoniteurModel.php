<?php

namespace models;

use models\base\SQL;

/**
 * Champs:
 * - idmoniteur (int, PK)
 * - nommoniteur (varchar)
 * - prenommoniteur (varchar)
 * - emailmoniteur (varchar)
 */
class MoniteurModel extends SQL
{
    public function __construct()
    {
        parent::__construct('moniteur', 'idmoniteur');
    }

    /**
     * Récupère tous les moniteurs
     * @return array
     */
    public function getAllMoniteurs(): array
    {
        $stmt = $this->getPdo()->prepare("SELECT * FROM moniteur ORDER BY nommoniteur, prenommoniteur");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
