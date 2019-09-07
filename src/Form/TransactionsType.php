<?php

namespace App\Form;

use App\Entity\Transactions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('montant')
            ->add('nomCompleEnv')
            ->add('nomCompleBen')
            ->add('telenv')
            ->add('teleben')
            ->add('numpieceenv')
            ->add('typepieceenv')
            ->add('numpieceben')
            ->add('typepieceben')
            ->add('dateenv')
            ->add('dateretrait')
            ->add('commissionSystem')
            ->add('commissionagentenv')
            ->add('commissionagentretrait')
            ->add('commissionetat')
            ->add('code')
            ->add('frais')
            ->add('agentenv')
            ->add('agentretrait')
            ->add('compte');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transactions::class,
            'csrf_protection' => false
        ]);
    }
}