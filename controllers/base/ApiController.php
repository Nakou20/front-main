<?php

namespace controllers\base;

class ApiController implements IBase
{
    function redirect($to)
    {
        header("Location: $to");
        exit();
    }

    /**
     * Autorise les requêtes CORS.
     * Une requête CORS (Cross-Origin Resource Sharing) est une requête HTTP
     * qui permet à un site web d'accéder à des ressources d'un autre domaine.
     * Cette fonction permet d'autoriser ces requêtes en ajoutant les en-têtes appropriés.
     *
     * De base, les navigateurs bloquent les requêtes CORS pour des raisons de sécurité. ici, nous les autorisons.
     * 
     */
    function allowCORS()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    /**
     * Extrait le token d'authentification de l'en-tête de la requête.
     */
    protected function getAuthToken(): ?string
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            return str_replace('Bearer ', '', $headers['Authorization']);
        } elseif (isset($headers['authorization'])) {
            return str_replace('Bearer ', '', $headers['authorization']);
        }
        return null;
    }

    /**
     * Extrait les données JSON du corps de la requête.
     */
    protected function getJsonData(): ?array
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    /**
     * Vérifie si la méthode de la requête est POST.
     * @return bool
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Retourne une réponse JSON de succès.
     *
     * @param string $message
     * @param array $data
     * @return array
     */
    protected function successResponse(string $message, array $data = []): string
    {
        $this->allowCORS();

        return json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Retourne une réponse JSON d'erreur.
     *
     * @param string $message
     * @param int $code
     * @return array
     */
    protected function errorResponse(string $message, int $code = 400): string
    {
        $this->allowCORS();

        http_response_code($code);
        return json_encode([
            'status' => 'error',
            'message' => $message
        ]);
    }
}
