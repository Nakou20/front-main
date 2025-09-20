<?php

namespace models;

use models\base\SQL;

/**
 * Champs:
 * - idquestion (int, PK)
 * - numreponse (int, PK)
 * - libellereponse (varchar)
 * - valide (tinyint)
 */
class ReponseModel extends SQL
{
    public function __construct()
    {
        parent::__construct('reponse', 'idquestion');
    }

    /**
     * Récupère les réponses pour une question spécifique.
     *
     * @param int $idQuestion
     * @return array|null
     */
    public function getByQuestion(int $idQuestion): array|null
    {
        $stmt = $this->getPdo()->prepare("SELECT * FROM reponse WHERE idquestion = :idquestion ORDER BY numreponse;");
        $stmt->execute([':idquestion' => $idQuestion]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
