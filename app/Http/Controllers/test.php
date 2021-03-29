<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PdoGsb;

class test extends Controller
{




    public function modifierMdp(){
        if( session('visiteur') != null)
        {
            return $vue = view('modifierMotDePasse')
                            ->with('erreurs',null)
                            ->with('message', "")
                            ->with('visiteur',session('visiteur'));
        }
        else 
        {
            return view('connexion')->with('erreurs',null);
                                    
        }
        
    }   
    public function verification(Request $request){
        if( session('visiteur') != null)
        {
            $mdp1 = $request['mdp1'];
            $mdp2 = $request['mdp2'];
            $date = $request['date'];
            $ok = PdoGsb::verif($date);
            $id = session('visiteur')['id'];


            if(empty($ok)){
                $erreurs[] = "La date d'embauche n'est pas correcte";
            }
            if($mdp1 != $mdp2){
                $erreurs[] = "Les mots de passe sont différents";
            }
            if(empty($erreurs)){
                PdoGsb::modifMdp($id, $mdp1);
                $message = "Modification effectuée";
                return view('modifierMotDePasse')
                                    ->with('erreurs',null)
                                    ->with('message',$message)
                                    ->with('visiteur',session('visiteur'));
            }
            else{
                return view('modifierMotDePasse')
                                            ->with('erreurs', $erreurs)
                                            ->with('message', "")
                                            ->with('visiteur',session('visiteur'));
            }

            
            
            
        }
        else 
        {
            return view('connexion')->with('erreurs',null);
                                    
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
