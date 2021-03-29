<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConnexionTest extends TestCase
{


    /** 
     * @test
     */
    public function retourneFormulaireConnexion()
    {
        $reponse = $this->get('/');
        $reponse->assertStatus(200);
        $reponse->assertSeeText('Login*');
        $reponse->assertSeeText('Mot de passe*');
    }

    /** 
     * @test
     */
    public function valideLaConnexionConforme()
    {
        $data = ['login'=>'toto', 'mdp'=>'titi'];
        $response = $this->post('/', $data);
        $response->assertStatus(200);
        $response->assertSessionHas('visiteur');
        $response->assertSeeText('Villechalane Louis');
    }
    /** 
     * @test
     */
    public function echecIdentificationDeConnexion()
    {
        $data = ['login'=>'toto', 'mdp'=>'titi'];
        $response = $this->post('/', $data);
        $response->assertStatus(200);
        $response->assertSession('visiteur');
        $response->assertSeeText('Villechalane Louis');
    }
}
