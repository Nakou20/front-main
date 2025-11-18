<?php

namespace models;

use models\base\SQL;

/**
 * Modèle pour gérer les demandes d'heures supplémentaires
 *
 * Champs de la table demandes:
 * - id (int, PK)
 * - ideleve (int, FK)
 * - type_demande (varchar) - ex: "heures_supplementaires"
 * - commentaire (text)
 * - statut (enum) - "en_attente", "validee", "refusee"
 * - date_creation (datetime)
 * - date_traitement (datetime)
 * - traite_par (int, FK vers moniteur)
 */
class DemandeModel extends SQL
{
    public function __construct()
    {
        parent::__construct('demandes', 'id');
    }

    /**
     * Créer une nouvelle demande d'heures supplémentaires
     * @param int $ideleve ID de l'élève
     * @param string $commentaire Commentaire de la demande
     * @return bool|int ID de la demande créée ou false
     */
    public function creerDemandeHeuresSupplementaires(int $ideleve, string $commentaire)
    {
        try {
            $query = "INSERT INTO demandes (ideleve, type_demande, commentaire, statut, date_creation) 
                      VALUES (:ideleve, 'heures_supplementaires', :commentaire, 'en_attente', NOW())";

            $stmt = $this->getPdo()->prepare($query);
            $success = $stmt->execute([
                ':ideleve' => $ideleve,
                ':commentaire' => $commentaire
            ]);

            if ($success) {
                return $this->getPdo()->lastInsertId();
            }

            return false;
        } catch (\Exception $e) {
            error_log("Erreur lors de la création de la demande : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer toutes les demandes d'un élève
     * @param int $ideleve ID de l'élève
     * @return array Liste des demandes
     */
    public function getDemandesEleve(int $ideleve): array
    {
        $query = "SELECT d.*, m.nommoniteur, m.prenommoniteur 
                  FROM demandes d
                  LEFT JOIN moniteur m ON d.traite_par = m.idmoniteur
                  WHERE d.ideleve = :ideleve
                  ORDER BY d.date_creation DESC";

        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':ideleve' => $ideleve]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer toutes les demandes en attente (pour admin)
     * @return array Liste des demandes
     */
    public function getDemandesEnAttente(): array
    {
        $query = "SELECT d.*, e.nomeleve, e.prenomeleve, e.emaileleve
                  FROM demandes d
                  INNER JOIN eleve e ON d.ideleve = e.ideleve
                  WHERE d.statut = 'en_attente'
                  ORDER BY d.date_creation ASC";

        $stmt = $this->getPdo()->query($query);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une demande par son ID
     * @param int $id ID de la demande
     * @return object|false
     */
    public function getDemandeById(int $id)
    {
        $query = "SELECT d.*, e.nomeleve, e.prenomeleve, e.emaileleve
                  FROM demandes d
                  INNER JOIN eleve e ON d.ideleve = e.ideleve
                  WHERE d.id = :id
                  LIMIT 1";

        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Valider une demande
     * @param int $id ID de la demande
     * @param int $idMoniteur ID du moniteur qui traite
     * @return bool
     */
    public function validerDemande(int $id, int $idMoniteur): bool
    {
        $query = "UPDATE demandes 
                  SET statut = 'validee', date_traitement = NOW(), traite_par = :idmoniteur
                  WHERE id = :id";

        $stmt = $this->getPdo()->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':idmoniteur' => $idMoniteur
        ]);
    }

    /**
     * Refuser une demande
     * @param int $id ID de la demande
     * @param int $idMoniteur ID du moniteur qui traite
     * @return bool
     */
    public function refuserDemande(int $id, int $idMoniteur): bool
    {
        $query = "UPDATE demandes 
                  SET statut = 'refusee', date_traitement = NOW(), traite_par = :idmoniteur
                  WHERE id = :id";

        $stmt = $this->getPdo()->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':idmoniteur' => $idMoniteur
        ]);
    }

    /**
     * Vérifier si un élève a déjà une demande en attente
     * @param int $ideleve ID de l'élève
     * @return bool
     */
    public function aDemandeEnAttente(int $ideleve): bool
    {
        $query = "SELECT COUNT(*) as count FROM demandes 
                  WHERE ideleve = :ideleve AND statut = 'en_attente'";

        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':ideleve' => $ideleve]);
        $result = $stmt->fetch(\PDO::FETCH_OBJ);

        return $result->count > 0;
    }
}

