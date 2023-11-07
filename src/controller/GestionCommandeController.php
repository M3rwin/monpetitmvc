<?php

namespace App\controller;
use App\model\GestionCommandeModel;
use App\Exceptions\AppException;


class GestionCommandeController {
    
    public function chercheUne(array $params){
        // appel de la méthode findCommande($id)
        $modele = new GestionCommandeModel();
        $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
        $uneCommande = $modele->findCommande($id);
        if($uneCommande){
            include_once PATH_VIEW . "GestionCommandeView\uneCommande.php";
        }else{
            throw new AppException("Commande " . $id . " inconnue");
        }
    }
    
    public function chercheToutes(){
        // appel de la méthode findAll() de la classe model adequate
        $modele = new GestionCommandeModel();
        $commandes = $modele->findAll();
        if($commandes){
            include_once PATH_VIEW . "GestionCommandeView\plusieursCommandes.php";
        }else{
            throw new AppException("Aucune commande");
        }
    }
    
}