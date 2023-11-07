<?php


namespace App\model;

use PDO;
use Tools\Connexion;
use Exception;
use App\Exceptions\AppException;
use App\Entity\Commande;

class GestionCommandeModel {
    public function findCommande($id): Commande {
        try {
            $unObjetPdo = Connexion::getConnexion();
            $sql = "select * from COMMANDE where id=:id";
            $ligne = $unObjetPdo->prepare($sql);
            $ligne->bindValue(':id', $id, PDO::PARAM_INT);
            $ligne->execute();
            return $ligne->fetchObject(Commande::class);
        } catch (Exception) {
            throw new AppException("Erreur technique non attendue");
        }
    }
    
    public function findAll() : array {
        $unObjetPdo = Connexion::getConnexion();
        $sql = "select * from COMMANDE";
        $lignes = $unObjetPdo->query($sql);
        return $lignes->fetchAll(PDO::FETCH_CLASS, Commande::class);
    }

}