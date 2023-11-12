<?php
declare (strict_types=1);

namespace App\Controller;
use App\Model\GestionCommandeModel;
use ReflectionClass;
use App\Exceptions\AppException;

/**
 * Description of GestionCommandeController
 *
 * @author egor_
 */
class GestionCommandeController {
    
    public function chercheUne(array $params) {
        //Appel de la méthode find($id)de la classe Model adequate
        $modele = new GestionCommandeModel();
        $id = filter_var(intval($params["id"]), FILTER_VALIDATE_INT);
        $uneCommande = $modele->find($id);
        if($uneCommande){
            $r = new ReflectionClass($this);
            include_once PATH_VIEW . str_replace('Controller', 'View', $r->getShortName()) . "/uneCommande.php";
        }else{
            throw new AppException("Commande ". $id. " inconnu");
        }
    }
    
    public function chercheToutes(){
        // Appel la methode findAll() de la classe Model adequate
        $modele = new GestionCommandeModel();
        $clients = $modele->findAll();
        if($clients){
            $r = new ReflectionClass($this);
            include_once PATH_VIEW . str_replace('Controller', 'View', $r->getShortName()). "/plusieursCommandes.php";
        }else{
            throw new AppException("Aucun Commande à afficher");
        }
    }
}
