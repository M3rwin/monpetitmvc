<?php
namespace App\Exceptions;

use Exception;

/**
 * Classe d'exception spécifique à l'application
 * 
 * @author Egor GUTUTUI
 */
class AppException extends Exception {
    
    // nom de lutilisateur de l'application 
    const NOMUSERCONNECTE = APP_USER;
    // nom de l'application
    const NOMAPPLICATION = APP_NAME;
    
    public function __construct(string $message) {
        parent::__construct("Erreur d'application ". self::NOMAPPLICATION . "<br> user " . self::NOMUSERCONNECTE . 
                "<br> message :" . $message);
    }
}