<?php

declare(strict_types=1);

namespace Tools;

use PDO;

/**
 * Description of Repository
 *
 * @author marco.clin
 */
class Repository {

    private string $classeNameLong;
    private string $classeNamespace;
    private string $table;
    private PDO $connexion;

    private function __construct(string $entity) {
        $tablo = explode("\\", $entity);
        $this->table = array_pop($tablo);
        $this->classeNamespace = implode("\\", $tablo);
        $this->classeNameLong = $entity;
        $this->connexion = Connexion::getConnexion();
    }

    public static function getRepository(string $entity): Repository {
        $repositoryName = str_replace('Entity', 'Repository', $entity) . 'Repository';
        $repository = new $repositoryName($entity);
        return $repository;
    }

    public function findAll(): array {
        $sql = "select * from " . $this->table;
        $lignes = $this->connexion->query($sql);
        $lignes->setFetchMode(PDO::FETCH_CLASS, $this->classeNameLong, null);
        return $lignes->fetchAll();
    }

    public function findIds(): array {
        $sql = "select id from " . $this->table;
        $lignes = $this->connexion->query($sql);
        return $lignes->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?object {
        try {
            $sql = "select * from " . $this->table . " where id=:id";
            $ligne = $this->connexion->prepare($sql);
            $ligne->bindValue(':id', $id, PDO::PARAM_INT);
            $ligne->execute();
            $reponse = $ligne->fetchObject($this->classeNameLong);
            if ($reponse) {
                return $reponse;
            } else {
                return null;
            }
        } catch (Exception) {
            throw new \App\Exceptions\AppException("Erreur technique innatendue");
        }
    }

    public function insert(object $object): void {
        // conversion d'un objet en tableau
        $attributs = (array) $object;
        array_shift($attributs);
        $colonnes = "(";
        $colonnesParams = "(";
        $parametres = array();
        foreach ($attributs as $cle => $valeur) {
            $cle = str_replace("\0", "", $cle);
            $c = str_replace($this->classeNameLong, "", $cle);
            if ($c != "id") {
                $colonnes .= $c . " ,";
                $colonnesParams .= " ? ,";
                $parametres[] = $valeur;
            }
        }
        $cols = substr($colonnes, 0, -1);
        $colsParams = substr($colonnesParams, 0, -1);
        $sql = "insert into " . $this->table . " " . $cols . ") values " . $colsParams . ") ";
        $unObjetPDO = Connexion::getConnexion();
        $req = $unObjetPDO->prepare($sql);
        $req->execute($parametres);
    }
    
    public function countRows() : int {
        $sql = "select count(*) from " . $this->table;
        $nbLignes = $this->connexion->query($sql);
        return (int)$nbLignes->fetch(PDO::FETCH_NUM)[0];
    }
    
}
