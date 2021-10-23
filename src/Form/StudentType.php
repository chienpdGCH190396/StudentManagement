<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Student Name',
                'required' => true
            ])
            ->add('dob', DateType::class, [
                'label' => 'Student Date of Birth',
                'required' => true
            ])
            ->add('email', TextType::class, [
                'label' => 'Student Email',
                'required' => true
            ])
            ->add('image', FileType::class, [
                'label' => 'Student Avatar',
                'required' => is_null($builder->getData()->getImage())
            ])
            ->add('classroom', EntityType::class, [
                'label' => 'Classroom',
                'class' => Classroom::class,
                'choice_label' => "name",
                'multiple' => false,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
