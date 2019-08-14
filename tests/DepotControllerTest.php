<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DepotControllerTest extends WebTestCase
{
    public function testdepot()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'morseck00',
            'PHP_AUTH_PW'   => 'eleves00'
        ]);
        $client->request(
            'POST', '/ajouter_depot',[],[],['CONTENT_TYPE' => "application/json"],

        '{
            "utilisateur":4,
            "montant":75000,
            "date_depot":"10-08-2019",
            "compte":1
         }'
        );
        $rep = $client->getResponse();
        $this->assertSame(201, $client->getResponse()->getStatusCode());
    }
}
