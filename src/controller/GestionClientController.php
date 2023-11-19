<?php

declare(strict_types=1);

namespace App\controller;
use App\model\GestionClientModel;
use App\Exceptions\AppException;
use ReflectionClass;
use Tools\MyTwig;
use Tools\Repository;
use App\Entity\Client;

class GestionClientController{
    
    
    function verificationSaisieClient(array $params) : array {
        $params["nomCli"] = htmlspecialchars($params["nomCli"]);
        $params["prenomCli"] = htmlspecialchars($params["prenomCli"]);
        $params["adresseRue1Cli"] = htmlspecialchars($params["adresseRue1Cli"]);
        if($params["adresseRue2Cli"]){
            $params["adresseRue2Cli"] = htmlspecialchars($params["adresseRue2Cli"]);
        }
        $params["cpCli"] = filter_var($params["cpCli"], FILTER_SANITIZE_NUMBER_INT);
        $params["villeCli"] = htmlspecialchars($params["villeCli"]);
        $params["telCli"] = filter_var($params["telCli"], FILTER_SANITIZE_NUMBER_INT);
        return $params;
    }
    
    public function chercheUn(array $params){
        // récupération d'un objet ClientRepository
        $repository = Repository::getRepository("App\Entity\Client");
        // on récup tout les id des clients
        $ids = $repository->findIds();
        // on place tout les id trouvés dans le tableau de paramètres à envoyer à la vue
        $params['lesId'] = $ids;
        // on tests si l'id du client à chercher est présent dans l'URL
        if(array_key_exists('id', $params)){
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $unClient = $repository->find($id);
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
        // récupération d'un objet ClientRepository
        $repository = Repository::getRepository("App\Entity\Client");
        $clients = $repository->findAll();
        if($clients){
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()) . "\plusieursClients.html.twig";
            MyTwig::afficheVue($vue, array('clients' => $clients));
        }else{
            throw new AppException("Aucun clients");
        }
    }
    
    
    public function creerClient(array $params){
        if(empty($params)){
            $vue = "GestionClientView\\creerClient.html.twig";
            MyTwig::afficheVue($vue, array());
        }else{
            try{
                $params = $this->verificationSaisieClient($params);
                // Création de l'objet client à partir des données du formulaire
                $client = new Client($params);
                $repository = Repository::getRepository("App\Entity\Client");
                $repository->insert($client);
                $this->chercheTous();
            } catch (Exception) {
                throw new AppException("Erreur à la création du client");
            }
        }
        
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
    
    public function nbClients() : void {
        $repository = Repository::getRepository("App\Entity\Client");
        $nbClients = $repository->countRows();
        echo "Nombre de client : " . $nbClients;
    }
}