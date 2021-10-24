<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => "Name",
                'required' => true
            ])

            ->add('type', ChoiceType::class,
            [
                'label' => 'Department',
                'required' => true,
                'choices' => [
                    "IT" => "IT",
                    "Marketing" => "Marketing",
                    "Desgin" => "Desgin"
                ] 
            ])

            ->add('description', TextType::class,
            [
                'label' => 'Description',
                'required' => false
            ])

            ->add('classrooms', EntityType::class,
            [
                'label' => "Classrooms",
                'class' => Classroom::class,
                'required' => true,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
