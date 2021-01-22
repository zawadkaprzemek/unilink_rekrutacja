<?php

namespace App\Form;

use App\Entity\Label;
use App\Entity\TodoList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var TodoList $list */
        $list=$options['data'];
        $builder
            ->add('name',TextType::class,array('label' =>'Nazwa'))
            ->add('priority',ChoiceType::class,array('label' =>'Ważność zadania',
                'choices'=>[
                  'Nie ważne'=>1,
                  'Mało ważne'=>2,
                  'Średnio ważne'=>3,
                  'Ważne'=>4,
                  'Bardzo ważne'=>5
                ],
                'data'=>(is_null($list->getPriority())? 3: $list->getPriority())
            ))
            ->add('description',TextareaType::class,array('label' =>'Opis'))
            ->add('labels',EntityType::class, array(
                    'label' =>'Etykiety',
                    'class' => Label::class,
                    'choice_label' =>'name',
                    'multiple' =>true
                )
            )
            ->add('submit',SubmitType::class, array('label' =>'Zapisz'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TodoList::class,
        ]);
    }
}
