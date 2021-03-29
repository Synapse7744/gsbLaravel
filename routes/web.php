<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

        /*-------------------- Use case connexion---------------------------*/

Route::get('/',[
        'as' => 'chemin_connexion',
        'uses' => 'connexionController@connecter'
]);

Route::post('/',[
        'as'=>'chemin_valider',
        'uses'=>'connexionController@valider'
]);
// PREMIERE OPTION//
Route :: get('selectionIdMois', [ //vers la selection id mois
        'as'=>'chemin_selectionIdMois',
        'uses'=>'comptableController@selectionIdMois'
]);
Route :: post('majQuantiteFrais', [ //vers la maj des quantités
        'as'=>'chemin_majQuantiteFrais',
        'uses'=>'comptableController@majQuantiteFrais'
]);
Route :: post('recap',  [  //vers la récap des nouvelles quantités
        'as'=>'chemin_recap',
        'uses'=>'comptableController@recap'
]);
Route :: post('enregistrerValidation',[ //on enregistre les nouvelles quantités, le nouveau total, la date et le statut de la requete dans la base de donnée
        'as'=>'chemin_enregistrerValidation',
        'uses'=>'comptableController@enregistrerValidation'
]);
// Route :: get('test/{id}/{mois}',[
//         'as'=>'chemin_test',
//         'uses'=>'comptableController@test'
// ]);
Route :: get('deconnexion', [
        'as'=>'chemin_deconnexion',
        'uses'=>'connexionController@deconnecter'
]);

         /*-------------------- Use case état des frais---------------------------*/
Route::get('selectionMois',[
        'as'=>'chemin_selectionMois',
        'uses'=>'etatFraisController@selectionnerMois'
]);

Route::post('listeFrais',[
        'as'=>'chemin_listeFrais',
        'uses'=>'etatFraisController@voirFrais'
]);

        /*-------------------- Use case gérer les frais---------------------------*/

Route::get('gererFrais',[
        'as'=>'chemin_gestionFrais',
        'uses'=>'gererFraisController@saisirFrais'
]);
Route::get('modifierMdp',[
        'as'=>'chemin_modifierMdp',
        'uses'=>'test@modifierMdp'
]);
Route::post('verification',[
        'as'=>'chemin_verification',
        'uses'=>'test@verification'
]);

Route::post('sauvegarderFrais',[
        'as'=>'chemin_sauvegardeFrais',
        'uses'=>'gererFraisController@sauvegarderFrais'
]);

