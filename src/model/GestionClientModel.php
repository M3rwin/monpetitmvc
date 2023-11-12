<?php

declare(strict_types = 1);

namespace App\model;

use PDO;
use App\entity\Client;
use Tools\Connexion;
use Exception;
use App\Exceptions\AppException;
use App\Entity\Commande;

class GestionClientModel {

    public function find(int $id): Client {
        try {
            $unObjetPdo = Connexion::getConnexion();
            $sql = "select * from CLIENT where id=:id";
            $ligne = $unObjetPdo->prepare($sql);
            $ligne->bindValue(':id', $id, PDO::PARAM_INT);
            $ligne->execute();
            return $ligne->fetchObject(Client::class);
        } catch (Exception) {
            throw new AppException("Erreur technique non attendue");
        }
    }

    public function findAll(): array {
        $unObjetPdo = Connexion::getConnexion();
        $sql = "select * from CLIENT";
        $lignes = $unObjetPdo->query($sql);
        return $lignes->fetchAll(PDO::FETCH_CLASS, Client::class);
    }


    public function enregistreClient (Client $client) {
        try{
            $unObjetPdo = Connexion::getConnexion ();
            $sql = "insert into client (titreCli, nomCli, prenomCli, adresseRuelCli, adresseRue2Cli, cpCli, villeCli, telcli) "
            . "values (:titreCli, :nomCli, :prenomCli, :adresseRuelCli, :adresseRue2Cli, :cpCli, : villeCli, :telCli)";
            $s = $unObjetPdo->prepare ($sql);
            $s->bindValue(':titreCli', $client->getTitreCli(), PDO::PARAM_STR);
            $s->bindValue(':nomCli', $client->getNomCli(), PDO::PARAM_STR);
            $s->bindValue (':prenomCli', $client->getPrenomCli(), PDO::PARAM_STR);
            $s->bindValue(':adresseRue1Cli', $client->getAdresseRue1Cli (), PDO::PARAM_STR);
            $s->bindValue (':adresseRue2Cli', ($client->getAdresseRue2Cli() == "") ? (null) : ($client->getAdresseRue2Cli()), PDO::PARAM_STR);
            $s->bindValue(':cpCli', $client->getCpCli(), PDO::PARAM_STR);
            $s->bindValue(':villeCli', $client->getVilleCli (), PDO::PARAM_STR);
            $s->bindValue(':telCli', $client->getTelCli(), PDO::PARAM_STR);
            $s->execute ();
        } catch (PDOException) {
            throw new AppException ("Erreur technique inattendue");
        }
    }

}
