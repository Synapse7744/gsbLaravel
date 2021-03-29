<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MyDate;

class MyDateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExtraireMois()
    {
        $mois = MyDate::extraireMois("202102");
        $this->assertEquals("02",$mois);
    }
    public function testExtraireAnnee()
    {
        $annee = MyDate::extraireAnnee("202102");
        $this->assertEquals("2021",$annee);
    }
    public function testGetAnneeMois()
    {
        $m = MyDate::getAnneeMoisCourant();
        $this->assertEquals("202102",$m['mois']);
        $this->assertEquals("2021",$m['numAnnee']);
        $this->assertEquals("02",$m['numMois']);
    }
}
