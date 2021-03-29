<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PdoGsb;

class EtatFraisTest extends TestCase
{

    /**
     * @test
     */

    public function testSelectionMois()
    {
        $visiteur = ['id' => 'A131', 'nom'=>'Villechalane', 'prenom'=>'Louis'];
        session(['visiteur' => $visiteur]);
        $response=$this->get('selectionMois');
        $response->assertStatus(200);
        $response->assertSessionHas('visiteur');
        $response->assertSeeText('Mois :');
        $response->assertSeeText('01/2021');
    }
    public function testListeFrais()
    {
        $visiteur = ['id' => 'A131', 'nom'=>'Villechalane', 'prenom'=>'Louis'];
        session(['visiteur' => $visiteur]);
        $response=$this->post('listeFrais', ['lstMois' => '202101']);
        $response->assertStatus(200);
        $response->assertSessionHas('visiteur');
        $response->assertSeeText('Montant validé : 0.00');
    }
}
