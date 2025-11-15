<?php

namespace models;

use models\base\SQL;
use utils\EmailUtils;
use utils\SessionHelpers;

/**
 * Champs:
 * - ideleve (int, PK)
 * - nomeleve (varchar)
 * - prenomeleve (varchar)
 * - emaileleve (varchar)
 * - motpasseeleve (varchar)
 * - datenaissanceeleve (date)
 */
class EleveModel extends SQL
{
    public function __construct()
    {
        parent::__construct('eleve', 'ideleve');
    }

    /**
     * Récupère les informations de l'élève connecté.
     * Si l'utilisateur n'est pas connecté, retourne null.
     * @return array|null
     */
    public function getMe()
    {
        // Récupérer l'élève connecté
        if (!SessionHelpers::isLogin()) {
            return null; // Si l'utilisateur n'est pas connecté, retourner null
        }

        $eleve = SessionHelpers::getConnected();
        $ideleve = $eleve["ideleve"];

        // Préparer la requête pour récupérer les informations de l'élève
        $query = "SELECT * FROM eleve WHERE ideleve = :ideleve LIMIT 1";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':ideleve' => $ideleve]);

        return $stmt->fetch();
    }

    /**
     * Méthode pour créer un nouvel élève.
     * Vérifie si l'email existe déjà avant de créer un compte.
     * 
     * @param string $nom
     * @param string $prenom
     */
    public function creer_eleve(string $nom, string $prenom, string $email, string $motDePasse, string $dateNaissance, string $numero): bool
    {
        // $pdo est l'instance PDO pour interagir avec la base de données.
        $pdo = $this->getPdo();

        // Vérifier si l'email existe déjà
        $query = "SELECT * FROM eleve WHERE emaileleve = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $existingEleve = $stmt->fetch();

        if ($existingEleve) {
            // L'email existe déjà, retourner false
            return false;
        }

        // Préparer la requête d'insertion
        $query = "INSERT INTO eleve (nomeleve, prenomeleve, emaileleve, motpasseeleve, datenaissanceeleve, numero) 
                  VALUES (:nom, :prenom, :email, :motDePasse, :dateNaissance, :numero)"; 
        $stmt = $pdo->prepare($query);
        $params = [
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':motDePasse' => $motDePasse, // Note: Mot de passe en clair, à sécuriser (avec password_hash($motDePasse, PASSWORD_DEFAULT) par exemple)
            ':dateNaissance' => $dateNaissance,
            ':numero' => $numero ?? null 
        ];

        // Exécuter la requête
        if ($stmt->execute($params)) {
            // Envoyer un email de confirmation (EmailUtils)
            EmailUtils::sendEmail(
                $email,
                "Bienvenue chez CDS 49",
                "confirmation_creation_compte", // Modèle d'email à utiliser (présent dans views/emails)
                [
                    'nomeleve' => $nom,
                    'prenomeleve' => $prenom
                ]
            );

            return true;
        } else {
            // En cas d'erreur lors de l'insertion, retourner false
            return false;
        }
    }

    /**
     * Méthode pour vérifier si un élève existe avec l'email et le mot de passe donnés.
     *
     * @param string $email
     * @param string $motDePasse
     * @param string $token (optionnel) Token à générer et sauvegarder dans la base de données (pour utilisattion via mobile)
     * @return array
     */
    public function connexion(string $email, string $motDePasse, string $token = ""): array
    {
        $query = "SELECT * FROM eleve WHERE emaileleve = :email  LIMIT 1";
        $params = [
            ':email' => $email,
        ];

        $result = $this->getPdo()->prepare($query);
        $result->execute($params);
        $eleve = $result->fetch();

        

        if ($eleve && password_verify($motDePasse, $eleve['motpasseeleve'])) {
            // Si l'élève existe, sauvegarder les informations dans la session
            SessionHelpers::login($eleve);
        } else 
        {
            // Si l'élève n'existe pas, détruire la session
            SessionHelpers::logout();
        }

        // Si le token est demandé, on le génère et on le sauvegarde dans la base de données
        if ($token && !empty($eleve)) {
            $query = "INSERT INTO token (ideleve, token, date_creation) VALUES (:ideleve, :token, NOW())";
            $stmt = $this->getPdo()->prepare($query);
            $params = [
                ':ideleve' => $eleve['ideleve'],
                ':token' => $token
            ];
            $stmt->execute($params);
        }


        return SessionHelpers::getConnected();
    }

    /**
     * Méthode pour mettre à jour les informations d'un élève.
     * @param int $ideleve
     * @param string $nom
     * @param string $prenom
     * @param string $email
     * @param string $motDePasse (optionnel) Mot de passe à mettre à jour, si fourni.
     * @return bool
     */
    public function update(string $ideleve, string $nom, string $prenom, string $email, string $datenaissanceeleve, ?string $motDePasse = null, ?string $numero = null): bool
    {
        
        $pdo = $this->getPdo();

        // Vérifier si l'email existe déjà pour un autre élève
        $query = "SELECT * FROM eleve WHERE emaileleve = :email AND ideleve != :ideleve LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email, ':ideleve' => $ideleve]);
        $existingEleve = $stmt->fetch();

        if ($existingEleve) {
            // L'email existe déjà pour un autre élève, retourner false
            return false;
        }

        // Préparer la requête de mise à jour
        $query = "UPDATE eleve SET nomeleve = :nom, prenomeleve = :prenom, emaileleve = :email, datenaissanceeleve = :datenaissanceeleve, numero = :numero";

        if ($motDePasse !== null) {
            $query .= ", motpasseeleve = :motDePasse"; // Ajouter le mot de passe uniquement s'il est fourni
        }

        $query .= " WHERE ideleve = :ideleve;";

        $params = [
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':datenaissanceeleve' => $datenaissanceeleve,
            ':ideleve' => $ideleve,
            ':numero' => $numero
        ];

        if ($motDePasse !== null) {
            $params[':motDePasse'] = $motDePasse; // Ajouter le mot de passe aux paramètres si fourni
        }

        // Exécuter la requête
        print_r($motDePasse);
        print_r($query);
        $stmt = $pdo->prepare($query);

        $result = $stmt->execute($params);

        if ($result) {
            // Mettre à jour les informations de l'élève dans la session
            SessionHelpers::login([
                'ideleve' => $ideleve,
                'nomeleve' => $nom,
                'prenomeleve' => $prenom,
                'emaileleve' => $email,
                'datenaissanceeleve' => $datenaissanceeleve,
                'numero' => $numero
            ]);

            return true;
        } else {
            // En cas d'erreur lors de la mise à jour, retourner false
            return false;
        }
    }

    public function getByToken(string $token)
    {
        // Récupérer l'ID de l'élève à partir du token
        $query = "SELECT ideleve FROM token WHERE token = :token LIMIT 1";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':token' => $token]);
        $result = $stmt->fetch();

        if (!$result) {
            return null; // Si le token n'est pas valide, retourner null
        }

        // Récupérer les informations de l'élève
        return $result;
    }

    public function updateByToken(string $token, string $nom, string $prenom, string $email, string $datenaissanceeleve, ?string $motDePasse = null, ?string $numero = null): bool
    {
        // Récupérer l'ID de l'élève à partir du token
        $result = $this->getByToken($token);

        // Mettre à jour les informations de l'élève
        return $this->update($result['ideleve'], $nom, $prenom, $email, $datenaissanceeleve, $motDePasse, $numero);
    }

    /**
     * Méthode pour anonymiser les données d'un élève (soft delete).
     * Un soft delete consiste à supprimer les données de l'élève sans les supprimer physiquement de la base de données.
     * @param int $ideleve
     * @return bool
     */
    public function deleteMe(): bool
    {
        $pdo = $this->getPdo();

        if (!SessionHelpers::isLogin()) {
            // Si l'utilisateur n'est pas connecté, retourner false
            return false;
        }

        $eleve = SessionHelpers::getConnected();
        $ideleve = $eleve->ideleve;

        // Préparer la requête de mise à jour pour anonymiser les données
        $query = "UPDATE eleve SET nomeleve = 'Anonyme', prenomeleve = 'Anonyme',
                    emaileleve = NULL, motpasseeleve = NULL, datenaissanceeleve = NULL, numero = NULL
                    WHERE ideleve = :ideleve";

        $stmt = $pdo->prepare($query);
        $params = [':ideleve' => $ideleve];

        if ($stmt->execute($params)) {
            // Envoi d'un email de confirmation d'anonymisation
            EmailUtils::sendEmail(
                $eleve->emaileleve,
                "Confirmation de la suppression de votre compte",
                "confirmation_anonymisation_compte", // Modèle d'email à utiliser (présent dans views/emails)
                [
                    'nomeleve' => $eleve->nomeleve,
                    'prenomeleve' => $eleve->prenomeleve
                ]
            );

            // Détruire la session après anonymisation
            SessionHelpers::logout();
            return true;
        } else {
            // En cas d'erreur lors de l'anonymisation, retourner false
            return false;
        }
    }
    
    /**
     * Récupère un élève par son email
     * @param string $email
     * @return object|false
     */
    public function getByEmail(string $email)
    {
        $query = "SELECT * FROM eleve WHERE emaileleve = :email LIMIT 1";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Met à jour le mot de passe d'un élève par son email
     * @param string $email
     * @param string $newPassword Le mot de passe déjà hashé
     * @return bool
     */
    public function updatePasswordByEmail(string $email, string $newPassword): bool
    {
        $query = "UPDATE eleve SET motpasseeleve = :password WHERE emaileleve = :email";
        $stmt = $this->getPdo()->prepare($query);
        return $stmt->execute([
            ':password' => $newPassword,
            ':email' => $email
        ]);
    }
}
