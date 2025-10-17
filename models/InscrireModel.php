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
            return null; // Si l'ID de l'élève n'est pas défini, retourner null
        }

        $stmt = $this->getPdo()->prepare("SELECT * FROM inscrire LEFT JOIN forfait ON inscrire.idforfait = forfait.idforfait WHERE inscrire.ideleve = :ideleve;");
        $stmt->execute([':ideleve' => $idEleve]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Active un forfait pour l'élève connecté.
     *
     * @param int $idforfait L'identifiant du forfait à activer.
     * @return bool True si l'activation a réussi, false sinon.
     */
    public function activateForfait(int $idforfait): bool
    {
        $idEleve = SessionHelpers::getConnected()['ideleve'] ?? null;

        if (!$idEleve) {
            return false; // Si l'ID de l'élève n'est pas défini, retourner false
        }

        $stmt = $this->getPdo()->prepare("INSERT INTO inscrire (ideleve, idforfait, dateinscription) VALUES (:ideleve, :idforfait, date('now'))");
        $stmt->bindParam(':ideleve', $idEleve, \PDO::PARAM_INT);
        $stmt->bindParam(':idforfait', $idforfait, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
