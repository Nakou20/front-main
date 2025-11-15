<?php

namespace routes\base;

use Exception;
use ReflectionFunction;
use utils\CliUtils;
use ReflectionMethod;

class Route
{
    /**
     * @var array> Liste des méthodes accessible dans l'application
     */
    static array $routes = array();

    static function Add($path, $callback): void
    {
        /**
         * Remplace les routes avec un format {nomParametre} (type Laravel) pour les transformer dans un format « classique » d'expression
         * régulière type (?<nomParam>.*?).
         */
        $path = preg_replace('/\/{(.*?)}/', '/(?<$1>.*?)', $path);

        Route::$routes[$path] = $callback;
    }

    static function isActivePath($path, $returnPositive = true, $returnNegative = false): string
    {
        $target = Route::GetCurrentPath();

        if (strlen($target) == 1) {
            return $path === $target ? $returnPositive : $returnNegative;
        } else if (strlen($path) > 1) {
            return str_starts_with($target, $path) ? $returnPositive : $returnNegative;
        }

        return $returnNegative;
    }

    static private function GetCurrentPath(): string
    {
        if (isset($_GET['path'])) {
            $target = $_GET['path'] == '' ? '/' : $_GET['path'];
        } else {
            if (PHP_MAJOR_VERSION >= 8 && PHP_MINOR_VERSION >= 4) {
                /* Gestion des sous dossiers comme bath path */
                if (dirname($_SERVER['SCRIPT_NAME']) != "/") {
                    $target = str_replace(dirname($_SERVER['SCRIPT_NAME']), "", $_SERVER['REQUEST_URI']);
                } else {
                    $target = $_SERVER["REQUEST_URI"];
                }
            } else {

                $target = $_SERVER['REQUEST_URI'];
            }

            $target = parse_url($target, PHP_URL_PATH);
        }


        if (!str_starts_with($target, "/")) {
            $target = "/" . $target;
        }

        return htmlspecialchars($target, ENT_QUOTES, 'UTF-8');
    }

    private function GetCommands()
    {
        global $argv, $argc;
        if ($argc > 1) {
            return $argv[1];
        } else {
            return "";
        }
    }

    private function GetArgs(): array
    {
        global $argv, $argc;
        if ($argc > 2) {
            return array_slice($argv, 2);
        } else {
            return [];
        }
    }

    private function searchForMatchingRoute($target): array
    {
        /**
         * Cette méthode recherche dans la liste des routes (clefs).
         * La correspondance est basée sur une expression régulière.
         * Cela permet de gérer des routes avec un paramètre dynamique type :
         * - /api/sample/{id}
         * ou des routes plus classique type
         * - /about
         */

        $matches = [];

        foreach (array_keys(Route::$routes) as $route) {
            if (preg_match_all('#^' . $route . '$#', $target, $matches)) {
                return array($route, array_slice($matches, 1));
            }
        }

        return [];
    }

    function LoadRequestedPath(): void
    {
        $isBrowser = CliUtils::isBrowser();

        $target = $isBrowser ? Route::GetCurrentPath() : Route::GetCommands();

        $args = $isBrowser ? array_merge($_GET, $_POST) : Route::getArgs();
        unset($args["path"]);

        $matches = self::searchForMatchingRoute($target);

        if ($matches) {

            $match = $matches[0];

            foreach (array_keys($matches[1]) as $inPathParameters) {
                if (is_string($inPathParameters)) {
                    $args[$inPathParameters] = $matches[1][$inPathParameters][0];
                }
            }

            try {
                $refMeth = null;
                switch (gettype(Route::$routes[$match])) {
                    case 'array':
                        $refMeth = ReflectionMethod::createFromMethodName(get_class(Route::$routes[$match][0]) . '::' . Route::$routes[$match][1]);
                        break;
                    case 'object':
                        $refMeth = new ReflectionFunction(Route::$routes[$match]);
                        break;
                    default:
                        throw new Exception("Unsupported method in router.");
                }
            } catch (Exception $e) {
                die($e);
            }

            $callArgs = [];
            foreach ($refMeth->getParameters() as $methParams) {

                if ($isBrowser) {
                    $callArgs[$methParams->getName()] = array_key_exists($methParams->getName(), $args) ? $args[$methParams->getName()] : null;
                } else {
                    $callArgs[$methParams->getName()] = $args[$methParams->getPosition()] ?? null;
                }

                if ($methParams->isOptional() && $callArgs[$methParams->getName()] == null) {
                    unset($callArgs[$methParams->getName()]);
                }
            }

            echo call_user_func_array(Route::$routes[$match], $callArgs);
        } else if ($isBrowser) {

            http_response_code(404);
            include('views/common/404.php');
        } else {
            print "Unknown command.\r\n";
        }
    }
}
