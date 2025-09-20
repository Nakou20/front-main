<?php

namespace controllers\base;

class WebController implements IBase
{
    function redirect($to)
    {
        header("Location: $to");
        die();
    }

    /**
     * Vérifie si la méthode de la requête est POST.
     * @return bool
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
