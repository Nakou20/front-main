<?php

namespace models;

use models\base\SQL;

/**
 * Champs:
 * - idquestion (int, PK)
 * - libellequestion (varchar)
 * - imagequestion (varchar)
 */
class QuestionModel extends SQL
{
    public function __construct()
    {
        parent::__construct('question', 'idquestion');
    }

    /**
     * Retourne N questions aléatoires.
     */
    public function getRandomQuestions(int $count = 10): array
    {
        $stmt = $this->getPdo()->prepare("SELECT * FROM question ORDER BY RAND() LIMIT :count");
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getQuestionsByCategory(string $category, int $count = 10): array
    {
        $stmt = $this->getPdo()->prepare("SELECT * FROM question WHERE category = :category ORDER BY RAND() LIMIT :count");
        $stmt->bindValue(':category', $category, \PDO::PARAM_STR);
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
