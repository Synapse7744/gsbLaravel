<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PdoGsb;
use MyDate;

class comptableController extends Controller
{
    //PREMIERE option, sélection par date et id et validation de la requete selectionnée//

    function selectionIdMois(){

        if(session('comptable') !=null)
        {
            $lesVisiteurs = PdoGsb::getLesVisiteurs();
            $lesMois = PdoGsb::getLesMois();
 
            return view('selectionIdMois') 
                ->with('erreurs',null)
                ->with('message',"")
                ->with('lesVisiteurs', $lesVisiteurs)
                ->with('lesMois', $lesMois)
                ->with('comptable',session('comptable'));
        }
        else
        {
            return view('connexion')->with('erreurs',null);
        }
    } 
   



    function majQuantiteFrais(Request $request)
    {

        if(session('comptable') !=null)
        {
            $id = $request['id'];
            $mois = $request['mois'];

            $numAnnee = MyDate::extraireAnnee($mois);
		    $numMois = MyDate::extraireMois($mois);

            $lesVisiteurs = PdoGsb::getLesVisiteurs();
            $lesMois = PdoGsb::getLesMois();
            
            $lesFrais = PdoGsb::getLesFraisForfait($id, $mois);
            $etatFrais = PdoGsb::getEtatFrais($id, $mois);

            $nom = PdoGsb::getNomVisiteur($id);
            $prenom = PdoGsb::getPrenomVisiteur($id);

            
            if ($etatFrais[0] == null){
                $erreurs[] = "La fiche de frais du visiteur $id pour la date $numMois / $numAnnee n'existe pas";
            }
            else
            {
                if(empty($lesFrais)){
                    $erreurs[] = "La fiche de frais du visiteur $id pour la date $numMois / $numAnnee est vide";
                }
            }


            if(!empty($erreurs)){
                return view('selectionIdMois')
                ->with('lesVisiteurs', $lesVisiteurs)
                ->with('lesMois', $lesMois)
                ->with('erreurs', $erreurs)
                ->with('message',"")
                ->with('comptable',session('comptable'));
            }

            $total=0;
            $ETP=0;
            $KM=0;
            $NUI=0;
            $REP=0;
            $fraisETP = 0;
            $fraisKM = 0;
            $fraisREP = 0;
            $fraisNUI = 0;


            foreach($lesFrais as $leFrais)
            {
                switch($leFrais['idfrais'])
                {
                    case('ETP') : 
                        $fraisETP = $leFrais['montantFrais'];
                        $ETP = $leFrais['quantite'];
                        break;

                    case('KM') :
                        $fraisKM = $leFrais['montantFrais'];
                        $KM = $leFrais['quantite'];
                        break;

                     case('REP') : 
                        $fraisREP = $leFrais['montantFrais'];
                        $REP = $leFrais['quantite'];
                        break;

                    case('NUI') :
                        $fraisNUI = $leFrais['montantFrais'];
                        $NUI = $leFrais['quantite'];
                        break;
                }

                $total += $leFrais['montantFrais'];
            }
            
            return view('majQuantiteFrais')
                        ->with('comptable',session('comptable'))
                        ->with('ETP', $ETP)
                        ->with('REP', $REP)
                        ->with('KM', $KM)
                        ->with('NUI', $NUI)
                        ->with('id', $id)
                        ->with('mois', $mois)
                        ->with('numMois', $numMois)
                        ->with('numAnnee', $numAnnee)
                        ->with('lesVisiteurs', $lesVisiteurs)
                        ->with('lesMois', $lesMois)
                        ->with('nom', $nom)
                        ->with('prenom', $prenom)
                        ->with('fraisETP', $fraisETP)
                        ->with('fraisNUI', $fraisNUI)
                        ->with('fraisREP', $fraisREP)
                        ->with('fraisKM', $fraisKM)
                        ->with('erreurs',null)
                        ->with('message',"")
                        ->with('total', $total);
        }
        else
        {
        return view('connexion')->with('erreurs',null);
        }
    }
        
        
    function recap(Request $request){
        if(session('comptable') !=null)
        {
            $id = $request['id'];
            $mois = $request['mois'];

            $ETP= $request['ETP'];
            $KM= $request['KM'];
            $NUI= $request['NUI'];
            $REP= $request['REP'];

            $saisieETP= $request['saisieETP'];
            $saisieKM= $request['saisieKM'];
            $saisieNUI= $request['saisieNUI'];
            $saisieREP= $request['saisieREP'];



            $lesVisiteurs = PdoGsb::getLesVisiteurs();
            $lesMois = PdoGsb::getLesMois();

            $numAnnee = MyDate::extraireAnnee($mois);
            $numMois = MyDate::extraireMois($mois);
            
            $nom = PdoGsb::getNomVisiteur($id);
            $prenom = PdoGsb::getPrenomVisiteur($id);

            $lesFrais = PdoGsb::getLesFraisForfait($id, $mois);

            $total=0;

            

            $lesFrais = PdoGsb::getLesFraisForfait($id, $mois);

            foreach($lesFrais as $leFrais)
            {
                switch($leFrais['idfrais']){
                    case('ETP') : 
                        $montantETP = $leFrais['montant'] * $saisieETP;
                        break;

                    case('KM') :
                        $montantKM = $leFrais['montant'] * $saisieKM;
                        break;

                     case('REP') : 
                        $montantREP = $leFrais['montant'] * $saisieREP;
                        break;

                    case('NUI') :
                        $montantNUI = $leFrais['montant'] * $saisieNUI;
                        break;
                }
            }
            $total = $montantETP + $montantKM + $montantREP + $montantNUI;

            return view('recap')
                ->with('saisieETP', $saisieETP)
                ->with('saisieREP', $saisieREP)
                ->with('saisieKM', $saisieKM)
                ->with('saisieNUI', $saisieNUI)
                ->with('total', $total)
                ->with('id', $id)
                ->with('mois', $mois)
                ->with('numMois', $numMois)
                ->with('numAnnee', $numAnnee)
                ->with('lesVisiteurs', $lesVisiteurs)
                ->with('lesMois', $lesMois)
                ->with('nom', $nom)
                ->with('prenom', $prenom)
                ->with('erreurs',null)
                ->with('message',"")
                ->with('comptable',session('comptable'));
        }
        else
        {
        return view('connexion')->with('erreurs',null);
        }
    }    

    function enregistrerValidation(Request $request){

        if(session('comptable') != null)
        {
            $lesVisiteurs = PdoGsb::getLesVisiteurs();
            $lesMois = PdoGsb::getLesMois();

            $id = $request['id'];
            $mois = $request['mois'];   

            $saisieETP = $request['saisieETP'];
            $saisieKM = $request['saisieKM'];
            $saisieNUI = $request['saisieNUI'];
            $saisieREP = $request['saisieREP'];

            $total =  $request['total'];

            $res1 = PdoGsb::majQuantiteFraisForfait($id, $mois, 'ETP', $saisieETP);
            $res2 = PdoGsb::majQuantiteFraisForfait($id, $mois, 'KM', $saisieKM);
            $res3 = PdoGsb::majQuantiteFraisForfait($id, $mois, 'NUI', $saisieNUI);
            $res4 = PdoGsb::majQuantiteFraisForfait($id, $mois, 'REP', $saisieREP);

            $res5 = PdoGsb::modifierEtatFiche($id, $mois);
            $res6 = PdoGsb::modifierDateFiche($id, $mois);
            $res7 = PdoGsb::modifierMontantFiche($id, $mois, $total);


            
            


            if($res1 !=1 ){
                $erreurs[] = "La quantité du frais Forfait Etape n'a pas pu etre mis à jour, veuillez réessayer  plus tard !";
            }
            if($res2 !=1 ){
                $erreurs[] = "La quantité du frais Frais Kilométrique n'a pas pu etre mis à jour, veuillez réessayer  plus tard !";
            }
            if($res3 !=1 ){
                $erreurs[] = "La quantité du frais Nuitée Hôtel n'a pas pu etre mis à jour, veuillez réessayer  plus tard !";
            }
            if($res4 !=1 ){
                $erreurs[] = "La quantité du frais Repas Restaurant n'a pas pu etre mis à jour, veuillez réessayer  plus tard !";
            }
            if($res6 !=1 ){
                $erreurs[] = "La date de la  fiche  n'a pu etre validé, veuillez réessayer  plus tard !";
            }
            if($res7 !=1 ){
                $erreurs[] = "Le  montant de la  fiche  n'a pu etre validé, veuillez réessayer  plus tard !";
            }
            
            if(!empty($error)){
                return view('selectionIdMois') ->with('comptable',session('comptable'))
                                                ->with('lesVisiteurs', $lesVisiteurs)
                                                ->with('erreurs',$errors)
                                                ->with('message',"")
                                                ->with('lesMois', $lesMois);
            }
            else
            {
                $message = "Modification effectuée";
                return view('selectionIdMois') ->with('comptable',session('comptable'))
                                                ->with('lesVisiteurs', $lesVisiteurs)
                                                ->with('erreurs',null)
                                                ->with('message',$message)
                                                ->with('lesMois', $lesMois);
            }
        }
        else 
        {
            return view('connexion')->with('erreurs',null);
        }
    }
    

    function deconnecter(){
            session(['comptable' => null]);
            return redirect()->route('chemin_connexion');
    }
       








  
}