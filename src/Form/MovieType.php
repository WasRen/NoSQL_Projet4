<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;





class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('movieid', IntegerType::class, [
            //     'required' => false,
            //     'mapped' => false,
            //     'attr' => ['min' =>1, 'max' =>68]])
            ->add('title', SearchType::class, [
                'required' => false,
                'mapped' => false])
            ->add('price', IntegerType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => ['min' => 0, 'max' =>300]])
            ->add('ranking', IntegerType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => ['min' => 0, 'max' =>100]])
            ->add('genre',  ChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'choices' => array_flip($options['genres']),
                'constraints' => [new Length(['min' => 1])],
                'label' => 'Quel genre souhaitez vous ?',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            'genres' => null
        ]);
    }
}
