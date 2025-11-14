<?php

namespace models;

use models\base\SQL;

/**
 * Champs:
 * - idvehicule (int, PK)
 * - nbpassagers (int)
 * - immatriculation (varchar)
 * - designation (varchar)
 * - manuel (tinyint)
 */
class VehiculeModel extends SQL
{
    public function __construct()
    {
        parent::__construct('vehicule', 'idvehicule');
    }

    /**
     * Récupère tous les véhicules
     * @return array
     */
    public function getAllVehicules(): array
    {
        $stmt = $this->getPdo()->prepare("SELECT * FROM vehicule ORDER BY designation");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
