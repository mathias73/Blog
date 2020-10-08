<?php

namespace App\Model;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
    public function twig(string $view, array $filter) : void
    {
        $loader = new FilesystemLoader('src/View');
        $twig = new Environment($loader, [
            'cache' => false//'src/tmp',
        ]);

        echo $twig->render($view, $filter);
    }
}
