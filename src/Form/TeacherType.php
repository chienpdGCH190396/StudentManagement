<?php

namespace App\Form;

use App\Entity\Teacher;
use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => "Teacher Name",
                'required' => true
            ])
            ->add('dob', DateType::class,
            [
                'label' => "Birthdate",
                'required' => true
            ])
            ->add('email', TextType::class,
            [
                'label' => "Email",
                'required' => true
            ])
            ->add('image', FileType::class,
            [
                'label' => "Teacher Image",
                'data_class' => null,
                'required' => is_null($builder->getData()->getImage())
            ])
            ->add('classroom', EntityType::class,
            [
                'label' => "Classroom",
                'class' => Classroom::class,
                'choice_label' => "name",
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
