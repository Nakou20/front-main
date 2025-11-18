<?php

namespace models;

use models\base\SQL;

/**
 * Champs:
 * - idresultat (int, PK)
 * - ideleve (int)
 * - dateresultat (datetime)
 * - score (bigint)
 * - nbquestions (bigint)
 */
class ResultatModel extends SQL
{
    public function __construct()
    {
        parent::__construct('resultat', 'idresultat');
    }

    /**
     * Sauvegarde le score d'un élève par son ID.
     * @param int $idEleve L'ID de l'élève.
     */
    public function saveScoreById(int $idEleve, int $score, int $nbquestions): bool
    {

        $query = "INSERT INTO resultat (ideleve, dateresultat, score, nbquestions) VALUES (:ideleve, NOW(), :score, :nbquestions)";
        $stmt = $this->getPdo()->prepare($query);

        return $stmt->execute([
            ':ideleve' => $idEleve,
            ':score' => $score,
            ':nbquestions' => $nbquestions
        ]);
    }

    /**
     * Sauvegarde du score par un token d'élève.
     */
    public function saveScoreByToken(string $token, int $score, int $nbquestions): bool
    {

        $eleveModel = new EleveModel();
        $eleve = $eleveModel->getByToken($token);

        if (!$eleve) {
            return false;
        }


        $query = "INSERT INTO resultat (ideleve, dateresultat, score, nbquestions) VALUES (:ideleve, NOW(), :score, :nbquestions)";
        $stmt = $this->getPdo()->prepare($query);

        return $stmt->execute([
            ':ideleve' => $eleve['ideleve'],
            ':score' => $score,
            ':nbquestions' => $nbquestions
        ]);
    }

    public function getResultatsByEleve(int $idEleve, string $orderBy = 'date', string $orderDirection = 'DESC'): array
    {
        $allowedColumns = ['dateresultat' => 'dateresultat', 'score' => 'score', 'date' => 'dateresultat'];
        $allowedDirections = ['ASC', 'DESC'];

        $column = $allowedColumns[$orderBy] ?? 'dateresultat';
        $direction = in_array(strtoupper($orderDirection), $allowedDirections) ? strtoupper($orderDirection) : 'DESC';

        $query = "SELECT idresultat, ideleve, dateresultat, score, nbquestions 
                  FROM resultat 
                  WHERE ideleve = :ideleve 
                  ORDER BY {$column} {$direction}";

        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':ideleve' => $idEleve]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
