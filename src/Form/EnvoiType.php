<?php

namespace App\Form;

use App\Entity\Envoi;
use App\Entity\Partenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnvoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomenv')
            ->add('Agence', EntityType::class, array(
                'class' => Partenaire::class
            ))
            ->add('numtelenv')
            ->add('piece_env')
            ->add('num_piece_env')
            ->add('nomben')
            ->add('telben')
            ->add('montantenvoi')
            ->add('commission')	
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Envoi::class,
            'csrf_protection' => false
        ]);
    }
}
