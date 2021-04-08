<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PdoGsb;
use MyDate;

class comptableController extends Controller
{
    //PREMIERE option, sélection par date et id et validation de la requete selectionnée//

    function selectionIdMois(){

        if(session('comptable') != null)
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
            if ($etatFrais == null){
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
            


            foreach($lesFrais as $leFrais)
            {
                switch($leFrais['idfrais'])
                {
                    case('ETP') : 
                        $ETP = $leFrais['quantite'];
                        break;

                    case('KM') :
                        $KM = $leFrais['quantite'];
                        break;

                     case('REP') : 
                        $REP = $leFrais['quantite'];
                        break;

                    case('NUI') :
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

            $saisieETP= $request['saisieETP'];
            $saisieKM= $request['saisieKM'];
            $saisieNUI= $request['saisieNUI'];
            $saisieREP= $request['saisieREP'];
            
            PdoGsb::majQuantiteFraisForfait($id, $mois, 'ETP', $saisieETP);
            PdoGsb::majQuantiteFraisForfait($id, $mois, 'KM', $saisieKM);
            PdoGsb::majQuantiteFraisForfait($id, $mois, 'NUI', $saisieNUI);
            PdoGsb::majQuantiteFraisForfait($id, $mois, 'REP', $saisieREP);

            
            $lesVisiteurs = PdoGsb::getLesVisiteurs();
            $lesMois = PdoGsb::getLesMois();
            $numAnnee = MyDate::extraireAnnee($mois);
            $numMois = MyDate::extraireMois($mois);
            $nom = PdoGsb::getNomVisiteur($id);
            $prenom = PdoGsb::getPrenomVisiteur($id);
            $lesFrais = PdoGsb::getLesFraisForfait($id, $mois);

            $total=0;
            $ETP=0;
            $KM=0;
            $NUI=0;
            $REP=0;
            

            foreach($lesFrais as $leFrais)
            {
                switch($leFrais['idfrais'])
                {
                    case('ETP') : 
                        $ETP = $leFrais['quantite'];
                        break;

                    case('KM') :
                        $KM = $leFrais['quantite'];
                        break;

                     case('REP') : 
                        $REP = $leFrais['quantite'];
                        break;

                    case('NUI') :
                        $NUI = $leFrais['quantite'];
                        break;
                }

                $total += $leFrais['montantFrais'];
            }

           
           
            $message = "Modification des quantités effectuée";

                        return view('recap')
                        ->with('ETP', $ETP)
                        ->with('REP', $REP)
                        ->with('KM', $KM)
                        ->with('NUI', $NUI)
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
                        ->with('message',$message)
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


            $total =  $request['total'];
        

            PdoGsb::modifierEtatFiche($id, $mois);
            PdoGsb::modifierDateFiche($id, $mois);
            PdoGsb::modifierMontantFiche($id, $mois, $total);

            
                $message = "Validation de la fiche de frais effectuée";
                return view('selectionIdMois') ->with('comptable',session('comptable'))
                                                ->with('lesVisiteurs', $lesVisiteurs)
                                                ->with('erreurs',null)
                                                ->with('message',$message)
                                                ->with('lesMois', $lesMois);
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