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
}
