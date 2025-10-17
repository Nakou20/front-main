<?php

namespace models;

use models\base\SQL;

/**
 * Champs:
 * - idforfait (int, PK)
 * - libelleforfait (varchar)
 * - descriptionforfait (text)
 * - contenuforfait (text)
 * - prixforfait (decimal)
 * - nbheures (bigint)
 * - prixhoraire (decimal)
 */
class ForfaitModel extends SQL
{
    public function __construct()
    {
        parent::__construct('forfait', 'idforfait');
    }

    /**
     * Récupération du forfait par son ID.
     * 
     * @param int $id L'identifiant du forfait.
     */
    public function getById(int $id): object|null
    {
        $stmt = SQL::getPdo()->prepare("SELECT * FROM {$this->tableName} WHERE idforfait = :id;");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Récupère les forfaits triés par prix croissant.
     *
     * @return array|null
     */
    public function getByPrice(): array|null
    {
        $stmt = SQL::getPdo()->prepare("SELECT * FROM {$this->tableName} ORDER BY prixforfait DESC LIMIT 3;");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Active un forfait pour un élève en insérant une inscription.
     * 
     * @param int $ideleve L'identifiant de l'élève.
     * @param int $idforfait L'identifiant du forfait.
     * @return bool True si l'activation a réussi, false sinon.
     */
    public function activateForfait(int $ideleve, int $idforfait): bool
    {
        $stmt = SQL::getPdo()->prepare("INSERT INTO inscrire (ideleve, idforfait, dateinscription) VALUES (:ideleve, :idforfait, CURDATE())");
        $stmt->bindParam(':ideleve', $ideleve, \PDO::PARAM_INT);
        $stmt->bindParam(':idforfait', $idforfait, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
