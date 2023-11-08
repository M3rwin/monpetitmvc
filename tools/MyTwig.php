<?php

namespace Tools;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;

abstract class MyTwig {
    
    private static function getLoader(){
        
        $loader = new FilesystemLoader(PATH_VIEW);
        $environnementTwig = new Environment($loader, [
            'cache' => false,
            'debug' => true
        ]);
        $environnementTwig->addExtension(new DebugExtension());
        return $environnementTwig;
    }
    
    
    public static function afficheVue($vue, $params){
        $twig = self::getLoader();
        $template = $twig->load($vue);
        echo $template->render($params);
    }
    
    
}