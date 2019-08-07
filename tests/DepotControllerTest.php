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
            "utilisateur":15,
            "montant":800000,
            "date_depot":"02-05-2019",
            "compte":2
         }'
        );
        $rep = $client->getResponse();
        $this->assertSame(201, $client->getResponse()->getStatusCode());
    }
}
