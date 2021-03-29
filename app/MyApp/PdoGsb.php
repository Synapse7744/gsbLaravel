<?php
namespace App\MyApp;
use PDO;
use Illuminate\Support\Facades\Config;
class PdoGsb{
        private static $serveur;
        private static $bdd;
        private static $user;
        private static $mdp;
        private  $monPdo;
	
/**
 * crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	public function __construct(){
        
        self::$serveur='mysql:host=' . Config::get('database.connections.mysql.host');
        self::$bdd='dbname=' . Config::get('database.connections.mysql.database');
        self::$user=Config::get('database.connections.mysql.username') ;
        self::$mdp=Config::get('database.connections.mysql.password');	  
        $this->monPdo = new PDO(self::$serveur.';'.self::$bdd, self::$user, self::$mdp); 
  		$this->monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		$this->monPdo =null;
	}
	

/**
 * Retourne les informations 
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	// On prends les infos sur le visiteur avec le login et le mdp dans la bdd, l'id, le nom et le prenom
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom from visiteur 
        where visiteur.login='" . $login . "' and visiteur.mdp='" . $mdp ."'";
    	$rs = $this->monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

	// On prends les infos sur le comptable avec le login et le mdp dans la bdd, l'id, le nom et le prenom
	public function getInfosComptable($login, $mdp){
		$req = "select nom, prenom from comptable 
        where comptable.login='" . $login . "' and comptable.mdp='" . $mdp ."'";
    	$rs = $this->monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

	//On prends tous les visiteurs qui ont une fiche qui a le statut état de 'CR'(avec un distinct pour n'avoir qu'une sele fois chaque mois)
	public function getLesVisiteurs(){
		$req = "select distinct fichefrais.idVisiteur as id from fichefrais where idEtat = 'CR'";
		$res = $this->monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}

	//On prends tous les mois qui correspondent à une fiche qui a le statut état de 'CR'(avec un distinct pour n'avoir qu'une sele fois chaque mois)
	
	//$lesMois à l'indice['mois'] donne un tableau avec les données découpées au dessus ($mois, $numAnnee, $numMois)
	public function getLesMois(){
		$req = "select distinct fichefrais.mois as mois from  fichefrais where idEtat = 'CR' order by fichefrais.mois desc";
		$res = $this->monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{		//Ici on arpente le resultat ($res) ligne par ligne
			$mois = $laLigne['mois'];		//d'abord on découpe la ligne avec substr pour isoler l'année et le mois
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois['$mois']=array(		//ensuite on charge le tableau $lesMois avec un autre tableau (l'indice pour accéder au second tableau sera ['mois']). la plupart du temps on arpentera le premier tableau avec un foreach $lesMois as $mois, ensuite on prendra $mois['mois'] pour obtenir la valeur 201001 par exemple)
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}

	//On récupere le nom du visiteur dans la bdd
	public function getNomVisiteur($id){
		$req ="SELECT nom FROM visiteur WHERE id='" . $id . "'";
		$res = $this->monPdo->query($req);
		$nom = $res->fetch();
		return $nom;
	}

	//On récupere le prénom du visiteur dans la bdd
	public function getPrenomVisiteur($id){
		$req ="SELECT prenom FROM visiteur WHERE id='" . $id . "'";
		$res = $this->monPdo->query($req);
		$prenom = $res->fetch();
		return $prenom;
	}

	// On récupere l'état d'une fiche de frais avec l'id et le mois
	public function getEtatFrais($id, $mois){
		$req = "select idEtat from fichefrais where  idVisiteur = '" .  $id  . "'  and  mois =  '" . $mois . "'";
		$res = $this->monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}

	//On mets a jour les quantités d'une fiche de frais 
	public function majQuantiteFraisForfait($idVisiteur, $mois, $idFrais, $quantite){
		$req = "update lignefraisforfait set lignefraisforfait.quantite = '" . $quantite . "'
		where lignefraisforfait.idvisiteur = '" . $idVisiteur  .  "' and lignefraisforfait.mois = '" .  $mois .  "'
		and lignefraisforfait.idfraisforfait = '" . $idFrais . "'";
		$this->monPdo->exec($req);
	}

	//On mets à jour l'état de la fiche de frais
	public function modifierEtatFiche($id, $mois){
		$req ="update fichefrais set idEtat = 'VA' where idVisiteur ='" . $id . "' and mois ='" . $mois .  "'";
		$res = $this->monPdo->exec($req);
		return $res;
	}

	//On mets à jour la date de la fiche de frais avec DATE(NOW())
	public function modifierDateFiche($id, $mois){
		$req ="update fichefrais set dateModif = DATE(NOW()) where idVisiteur ='" . $id . "' and mois ='" . $mois .  "'";
		$res = $this->monPdo->exec($req);
		return $res;
	}

	//On mets à jour le total de la fiche de frais
	public function modifierMontantFiche($id, $mois,  $total){
		$req ="update fichefrais set montantValide = '" .  $total . "' where idVisiteur ='" . $id . "' and mois ='" . $mois .  "'";
		$res = $this->monPdo->exec($req);
		return $res;
	}
	
	
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
// permet d'obtenir l'idfrais, libelle, montant, quantite et le prix du frais (montant * quantite)
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, fraisforfait.montant as montant,
		lignefraisforfait.quantite as quantite, quantite * montant as montantFrais from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = $this->monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}

	
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = $this->monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			$this->monPdo->exec($req);
		}
	}

/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = $this->monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = $this->monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		$this->monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			$this->monPdo->exec($req);
		 }
	}


/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = $this->monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = $this->monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$this->monPdo->exec($req);
	}

	public function getLesPrixFrais(){
		$req = "select fraisforfait.id as idFrais,fraisforfait.montant as montant from fraisforfait ";
		$res = $this->monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
	public function verif($date){
		$req = "select * from  visiteur where dateEmbauche = '" . $date . "'";
		$res = $this->monPdo->query($req);
		$ligne = $res->fetch();
		return $ligne; 
	}
	public function modifMdp($id, $mdp){
		$req = "update visiteur set visiteur.mdp = '" . $mdp . "' where visiteur.id = '" . $id . "'";
		$res = $this->monPdo->exec($req);
		return $res;
	}





}
