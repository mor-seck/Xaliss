<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Utilisateur;

class AdminSupFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setUsername('morseck00');
        $utilisateur->setRoles(['ROLE_ADMIN_SUP']);
        $password = $this->encoder->encodePassword($utilisateur, 'eleves00');
        $utilisateur->setPassword($password);
        $utilisateur->setPrenom('Mor');
        $utilisateur->setNom('SECK');
        $utilisateur->setAdresse('Pikine');
        $utilisateur->setTelephone('773280348');
        $utilisateur->setEmail('morseck00@gmail.com');

        $manager->persist($utilisateur);
        $manager->flush();
    }
}
