<?php

declare(strict_types=1);

namespace App\controller;

use App\model\GestionClientModel;
use App\Exceptions\AppException;
use ReflectionClass;
use Tools\MyTwig;
use Tools\Repository;
use App\Entity\Client;

class GestionClientController {

    private function verificationSaisieClient(array $params): array {
        $params["nomCli"] = htmlspecialchars($params["nomCli"]);
        $params["prenomCli"] = htmlspecialchars($params["prenomCli"]);
        $params["adresseRue1Cli"] = htmlspecialchars($params["adresseRue1Cli"]);
        if ($params["adresseRue2Cli"]) {
            $params["adresseRue2Cli"] = htmlspecialchars($params["adresseRue2Cli"]);
        }
        $params["cpCli"] = filter_var($params["cpCli"], FILTER_SANITIZE_NUMBER_INT);
        $params["villeCli"] = htmlspecialchars($params["villeCli"]);
        $params["telCli"] = filter_var($params["telCli"], FILTER_SANITIZE_NUMBER_INT);
        return $params;
    }

    private function verifieEtPrepareCriteres(array $params): array {
        $args = array(
            'titreCli' => array(
                'filter' => FILTER_VALIDATE_REGEXP | FILTER_SANITIZE_SPECIAL_CHARS,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => array('regexp' => '/^(Monsieur|Madame|Mademoiselle)$/'
                )),
            'cpCli' => array(
                'filter' => FILTER_VALIDATE_REGEXP | FILTER_SANITIZE_SPECIAL_CHARS,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => array('regexp' => "/[0-9]{5}/"
                )),
            'villeCli' => FILTER_SANITIZE_SPECIAL_CHARS
        );
        $retour = filter_var_array($params, $args, false);
        if (isset($retour['titreCli']) || isset($retour['cpCli']) || isset($retour['villeCli'])) {
            // c'est le retour du formulaire de choix de filtre
            $element = "Choisir ... ";
            while (in_array($element, $retour)) {
                unset($retour[array_search($element, $retour)]);
            }
        }
        return $retour;
    }

    public function chercheUn(array $params) {
        // récupération d'un objet ClientRepository
        $repository = Repository::getRepository("App\Entity\Client");
        // on récup tout les id des clients
        $ids = $repository->findIds();
        // on place tout les id trouvés dans le tableau de paramètres à envoyer à la vue
        $params['lesId'] = $ids;
        // on tests si l'id du client à chercher est présent dans l'URL
        if (array_key_exists('id', $params)) {
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $unClient = $repository->find($id);
            if ($unClient) {
                // le client a été trouvé
                $params['unClient'] = $unClient;
            } else {
                // le client n'a pas été trouvé
                $params['message'] = "Client " . $id . " inconnu";
            }
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "\unClient.html.twig";
        MyTwig::afficheVue($vue, $params);
    }

    public function chercheTous() {
        // récupération d'un objet ClientRepository
        $repository = Repository::getRepository("App\Entity\Client");
        $clients = $repository->findAll();
        if ($clients) {
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()) . "\plusieursClients.html.twig";
            MyTwig::afficheVue($vue, array('clients' => $clients));
        } else {
            throw new AppException("Aucun clients");
        }
    }

    public function creerClient(array $params) {
        if (empty($params)) {
            $vue = "GestionClientView\\creerClient.html.twig";
            MyTwig::afficheVue($vue, array());
        } else {
            try {
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

    public function enregistreClient($params) {
        try {
            $client = new Client($params);
            $modele = new GestionClientModel();
            $modele->enregistreClient($client);
        } catch (Exception) {
            throw new AppException("Erreur à l'enregistrement du client");
        }
    }

    public function nbClients(): void {
        $repository = Repository::getRepository("App\Entity\Client");
        $nbClients = $repository->countRows();
        echo "Nombre de client : " . $nbClients;
    }

    private function trieTTStr(array $tableau, string $sousTableau): array {
        usort($tableau, function ($a, $b) use ($sousTableau) {
            return strcmp($a[$sousTableau], $b[$sousTableau]);
        });
        return $tableau;
    }

    private function trieTTIntDesc(array $tableau, string $sousTableau): array {
        usort($tableau, function ($a, $b) use ($sousTableau) {
            return $b[$sousTableau] - $a[$sousTableau];
        });
        return $tableau;
    }

    public function statsClients() {
        // récupération d'un objet ClientRepository
        $repositoryClient = Repository::getRepository("App\Entity\Client");
        $clients = $repositoryClient->statistiquesTousClients();
        $repositoryCommande = Repository::getRepository("App\Entity\Commande");
        $commandes = $repositoryCommande->findAll();
        for ($i = 0; $i < count($clients); $i++) {
            $nbCommande = 0;
            foreach ($commandes as $commande) {
                if ($commande->getIdClient() == $clients[$i]["id"]) {
                    $nbCommande += 1;
                }
            }
            $clients[$i]['nbCommandes'] = $nbCommande;
        }
        $clientsTrie = $this->trieTTIntDesc($this->trieTTStr($clients, "nomCli"), "nbCommandes");
        if ($clientsTrie) {
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()) . "\statsClient.html.twig";
            MyTwig::afficheVue($vue, array('clients' => $clientsTrie));
        } else {
            throw new AppException("Aucun clients");
        }
    }

    public function testFindBy(): void {
        $repository = Repository::getRepository("App\Entity\Client");
        $parametres = array('titreCli' => 'Monsieur', 'villeCli' => 'Toulon');
        $clients = $repository->findBytitreCli_and_villeCli($parametres);
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/plusieursClients.html.twig";
        MyTwig::afficheVue($vue, array('clients' => $clients));
    }

    public function rechercheClients(array $params): void {
        $repository = Repository::getRepository("App\Entity\Client");
        $titres = $repository->findColumnDistinctValues('titreCli');
        $cps = $repository->findColumnDistinctValues('cpCli');
        $villes = $repository->findColumnDistinctValues('villeCli');
        $paramsVue['titres'] = $titres;
        $paramsVue['cps'] = $cps;
        $paramsVue['villes'] = $villes;
        // Gestion du retour du formulaire
        // On va d'abord filtrer et préparer le retour du formulaire avec la fonction verifieEtPrepareCriteres
        $criteresPrepares = $params;
        $criteresPrepares = $this->verifieEtPrepareCriteres($params);
        if (count($criteresPrepares) > 0) {
            $clients = $repository->findBy($params);
            $paramsVue['lesClients'] = $clients;
            $criteres = [];
            foreach ($criteresPrepares as $valeur) {
                if ($valeur != "Choisir...") {
                    $criteres[] = $valeur;
                }
            }
            $paramsVue['criteres'] = $criteres;
            $vue = "GestionClientView\\tousClients.html.twig";
            MyTwig::afficheVue($vue, $paramsVue);
        } else {
            $vue = "GestionClientView\\filtreClients.html.twig";
            MyTwig::afficheVue($vue, $paramsVue);
        }
    }
}
