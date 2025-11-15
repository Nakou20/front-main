<?php

namespace models;

use models\base\SQL;
use utils\SessionHelpers;

/**
 * Champs:
 * - ideleve (int, PK)
 * - idforfait (int, PK)
 * - dateinscription (date)
 */
class InscrireModel extends SQL
{
    public function __construct()
    {
        parent::__construct('inscrire', 'ideleve');
    }

    /**
     * Récupère les informations d'inscription d'un élève pour un forfait spécifique.
     */
    public function getForfaitEleveConnecte(): object | null | bool
    {
        $idEleve = SessionHelpers::getConnected()['ideleve'] ?? null;

        if (!$idEleve) {
            return null;
        }

        $stmt = $this->getPdo()->prepare("SELECT * FROM inscrire LEFT JOIN forfait ON inscrire.idforfait = forfait.idforfait WHERE inscrire.ideleve = :ideleve;");
        $stmt->execute([':ideleve' => $idEleve]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
