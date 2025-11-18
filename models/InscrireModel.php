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

    /**
     * Active un forfait pour un élève.
     *
     * @param int $idEleve L'identifiant de l'élève
     * @param int $idForfait L'identifiant du forfait
     * @return bool True si l'activation a réussi, False sinon
     */
    public function activerForfait(int $idEleve, int $idForfait): bool
    {

        $stmt = $this->getPdo()->prepare("SELECT * FROM inscrire WHERE ideleve = :ideleve LIMIT 1;");
        $stmt->execute([':ideleve' => $idEleve]);
        $forfaitExistant = $stmt->fetch();

        if ($forfaitExistant) {

            return false;
        }


        $stmt = $this->getPdo()->prepare("INSERT INTO inscrire (ideleve, idforfait, dateinscription) VALUES (:ideleve, :idforfait, :dateinscription);");
        return $stmt->execute([
            ':ideleve' => $idEleve,
            ':idforfait' => $idForfait,
            ':dateinscription' => date('Y-m-d')
        ]);
    }

    /**
     * Vérifie si un élève a un forfait actif.
     *
     * @param int $idEleve L'identifiant de l'élève
     * @return bool True si l'élève a un forfait actif, False sinon
     */
    public function hasForfaitActif(int $idEleve): bool
    {
        $stmt = $this->getPdo()->prepare("SELECT COUNT(*) as count FROM inscrire WHERE ideleve = :ideleve;");
        $stmt->execute([':ideleve' => $idEleve]);
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $result->count > 0;
    }
}
