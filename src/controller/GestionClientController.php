<?php

declare(strict_types=1);

namespace App\controller;
use App\model\GestionClientModel;
use App\Exceptions\AppException;
use ReflectionClass;
use Tools\MyTwig;

class GestionClientController{
    
    public function chercheUn(array $params){
        // appel de la méthode find($id) de la classe model adequate
        $modele = new GestionClientModel();
        // on récup tout les id des clients
        $ids = $modele->findIds();
        // on place tout les id trouvés dans le tableau de paramètres à envoyer à la vue
        $params['lesId'] = $ids;
        // on tests si l'id du client à chercher est présent dans l'URL
        if(array_key_exists('id', $params)){
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $unClient = $modele->find($id);
            if($unClient){
                // le client a été trouvé
                $params['unClient'] = $unClient;
            }else{
                // le client n'a pas été trouvé
               $params['message'] = "Client " . $id . " inconnu";
            }
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "\unClient.html.twig";
        MyTwig::afficheVue($vue, $params);
    }
    
    public function chercheTous(){
        // appel de la méthode findAll() de la classe model adequate
        $modele = new GestionClientModel();
        $clients = $modele->findAll();
        if($clients){
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()) . "\plusieursClients.html.twig";
            MyTwig::afficheVue($vue, array('clients' => $clients));
        }else{
            throw new AppException("Aucun clients");
        }
    }
    
    
    public function creerClient(array $params){
        $vue = "GestionClientView\\creerClient.html.twig";
        MyTwig::afficheVue($vue, array());
    }
    
    
    public function enregistreClient($params){
        try{
            $client = new Client($params);
            $modele = new GestionClientModel();
            $modele->enregistreClient($client);
        } catch (Exception) {
            throw new AppException("Erreur à l'enregistrement du client");
        }
    }
}