<?php

namespace App\Form;

use App\Entity\VoteTransaction;
use App\Enum\BetCondition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cash', IntegerType::class)
            ->add('condition', ChoiceType::class, [
                'choices' => BetCondition::labels(),
            ])
            ->add('Bet', SubmitType::class)
//            ->add('win')
//            ->add('status')
//            ->add('createdAt')
//            ->add('vote', EntityType::class)
//            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => VoteTransaction::class,
            'method' => 'POST'
        ]);
    }
}
