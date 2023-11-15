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
            if($reponse){
                return $reponse;
            }
            else{
                return null;
            }
        } catch (Exception) {
            throw new \App\Exceptions\AppException("Erreur technique innatendue");
        }
    }
}
