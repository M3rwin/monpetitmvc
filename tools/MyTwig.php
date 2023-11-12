<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tools;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;

/**
 *  Classe d'affichage de vues
 *  
 *  egor.gututui
 */
abstract class MyTwig {
    
    private static function getLoader(){
        
        $loader = new FilesystemLoader(PATH_VIEW);
        $environementTwig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        $environementTwig->addExtension(new DebugExtension());
        return $environementTwig;
    }
    
    public static function afficheVue($vue, $params) {
        $twig = self::getLoader();
        $template = $twig->load($vue);
        echo $template->render($params);
    }
}
