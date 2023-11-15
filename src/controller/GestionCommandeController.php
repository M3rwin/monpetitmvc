<?php

namespace App\controller;
use App\model\GestionCommandeModel;
use App\Exceptions\AppException;
use ReflectionClass;
use Tools\Repository;
use Tools\MyTwig;


class GestionCommandeController {
    
    public function chercheUne(array $params){
        // récupération d'un objet ClientRepository
        $repository = Repository::getRepository("App\Entity\Commande");
        // on récup tout les id des commandes
        $ids = $repository->findIds();
        // on place tout les id trouvés dans le tableau de paramètres à envoyer à la vue
        $params['lesId'] = $ids;
        // on tests si l'id de la commande à chercher est présent dans l'URL
        if(array_key_exists('id', $params)){
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $uneCommande = $repository->find($id);
            if($uneCommande){
                // la commande a été trouvé
                $params['uneCommande'] = $uneCommande;
            }else{
                // la commande n'a pas été trouvé
                $params['message'] = "Commande " . $id . " inconnu";
            }
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "\uneCommande.html.twig";
        MyTwig::afficheVue($vue, $params);
    }
    
    public function chercheToutes(){
        // appel de la méthode findAll() de la classe model adequate
        $modele = new GestionCommandeModel();
        $commandes = $modele->findAll();
        if($commandes){
            $r = new ReflectionClass($this);
            include_once PATH_VIEW . str_replace('Controller', 'View', $r->getShortName()) . "\plusieursCommandes.php";
        }else{
            throw new AppException("Aucune commande");
        }
    }
    
}