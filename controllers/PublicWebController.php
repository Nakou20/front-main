<?php

namespace controllers;

use utils\Template;
use models\ForfaitModel;
use controllers\base\WebController;

class PublicWebController extends WebController
{
    private ForfaitModel $forfaitModel;

    public function __construct()
    {
        $this->forfaitModel = new ForfaitModel();
    }

    function home(): string
    {
        return Template::render("views/global/home.php");
    }
    function aPropos(): string
    {
        return Template::render("views/global/a_propos.php");
    }
    function equipe(): string
    {
        return Template::render("views/global/equipe.php");
    }

    function forfait(): string
    {
        // Récupération des forfaits depuis la base de données
        $forfaits = $this->forfaitModel->getByPrice();

        return Template::render("views/global/forfaits.php", [
            'forfaits' => $forfaits
        ]);
    }
}

