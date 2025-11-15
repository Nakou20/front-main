<?php


namespace utils;


class SessionHelpers
{
    public function __construct()
    {
        SessionHelpers::init();
    }

    static function setFlashMessage(string $key, mixed $value): void
    {
        $_SESSION['FLASH'][$key] = $value;
    }

    static function getFlashMessage(string $key): mixed
    {
        if (isset($_SESSION['FLASH'][$key])) {
            $value = $_SESSION['FLASH'][$key];
            unset($_SESSION['FLASH'][$key]);
            return $value;
        }

        return null;
    }

    static function init(): void
    {
        session_start();
    }

    static function login(mixed $equipe): void
    {
        $_SESSION['LOGIN'] = $equipe;
    }

    static function logout(): void
    {
        unset($_SESSION['LOGIN']);
    }

    static function getConnected(): mixed
    {
        if (SessionHelpers::isLogin()) {
            return $_SESSION['LOGIN'];
        } else {
            return array();
        }
    }

    static function isLogin(): bool
    {
        return isset($_SESSION['LOGIN']);
    }

    static function saveSelectedForfait(int $idForfait): void
    {
        $_SESSION['SELECTED_FORFAIT'] = $idForfait;
    }

    static function getSelectedForfait(): ?int
    {
        $selectedForfait = $_SESSION['SELECTED_FORFAIT'] ?? null;
        unset($_SESSION['SELECTED_FORFAIT']); // Suppression du forfait sélectionné après récupération
        return $selectedForfait;
    }

    static function hasSelectedForfait(): bool
    {
        return isset($_SESSION['SELECTED_FORFAIT']);
    }
}
