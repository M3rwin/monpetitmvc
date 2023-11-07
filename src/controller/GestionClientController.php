<?php

declare(strict_types=1);

namespace App\controller;
use App\model\GestionClientModel;
use App\Exceptions\AppException;


class GestionClientController{
    
    public function chercheUn(array $params){
        // appel de la méthode find($id) de la classe model adequate
        $modele = new GestionClientModel();
        $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
        $unClient = $modele->find($id);
        if($unClient){
            include_once PATH_VIEW . "GestionClientView\unClient.php";
        }else{
            throw new AppException("Client " . $id . " inconnu");
        }
    }
    
    public function chercheTous(){
        // appel de la méthode findAll() de la classe model adequate
        $modele = new GestionClientModel();
        $clients = $modele->findAll();
        if($clients){
            include_once PATH_VIEW . "GestionClientView\plusieursClients.php";
        }else{
            throw new AppException("Aucun clients");
        }
    }
    
    
}