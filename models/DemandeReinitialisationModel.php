<?php

namespace models;

use models\base\SQL;

/**
 * Champs:
 * - id (int, PK)
 * - emaileleve (varchar)
 * - token (varchar)
 * - date_creation (datetime)
 * - date_expiration (datetime)
 * - utilise (tinyint)
 */
class DemandeReinitialisationModel extends SQL
{
    public function __construct()
    {
        parent::__construct('demande_reinitialisation', 'id');
    }

    /**
     * Crée une demande de réinitialisation de mot de passe
     * @param string $email Email de l'élève
     * @return string|false Token de réinitialisation ou false en cas d'erreur
     */
    public function createResetRequest(string $email)
    {
        try {
            $token = bin2hex(random_bytes(32));
            $dateCreation = date('Y-m-d H:i:s');
            $dateExpiration = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $query = "INSERT INTO demande_reinitialisation (emaileleve, token, date_creation, date_expiration, utilise) 
                      VALUES (:emaileleve, :token, :date_creation, :date_expiration, 0)";

            $stmt = $this->getPdo()->prepare($query);
            $success = $stmt->execute([
                ':emaileleve' => $email,
                ':token' => $token,
                ':date_creation' => $dateCreation,
                ':date_expiration' => $dateExpiration
            ]);

            return $success ? $token : false;
        } catch (\Exception $e) {
            error_log("Erreur lors de la création de la demande de réinitialisation : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valide un token de réinitialisation
     * @param string $token Token à valider
     * @return object|false Données de la demande ou false si invalide
     */
    public function validateToken(string $token)
    {
        $query = "SELECT * FROM demande_reinitialisation 
                  WHERE token = :token 
                  AND utilise = 0 
                  AND date_expiration > NOW() 
                  LIMIT 1";

        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':token' => $token]);

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Marque un token comme utilisé
     * @param string $token Token à marquer comme utilisé
     * @return bool Succès de l'opération
     */
    public function markTokenAsUsed(string $token): bool
    {
        $query = "UPDATE demande_reinitialisation 
                  SET utilise = 1 
                  WHERE token = :token";

        $stmt = $this->getPdo()->prepare($query);
        return $stmt->execute([':token' => $token]);
    }

    /**
     * Supprime les demandes expirées (nettoyage)
     * @return bool Succès de l'opération
     */
    public function cleanExpiredRequests(): bool
    {
        $query = "DELETE FROM demande_reinitialisation 
                  WHERE date_expiration < NOW() 
                  OR (utilise = 1 AND date_creation < DATE_SUB(NOW(), INTERVAL 7 DAY))";

        $stmt = $this->getPdo()->prepare($query);
        return $stmt->execute();
    }
}

