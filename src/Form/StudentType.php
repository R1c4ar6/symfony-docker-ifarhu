<?php

namespace App\Form;

use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identificationNumber', null, [
                'label' => 'Número de identificación',
                'attr' => ['placeholder' => '7-123-4567'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d-\d{3}-\d{3,4}$/',
                        'message' => 'El número de identificación debe tener el formato X-XXX-XXXX',
                    ]),
                ],
            ])
            ->add('firstName', null, [
                'label' => 'Nombre',
                'attr' => ['placeholder' => 'Juan'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[A-ZÁÉÍÓÚÑÜ][a-zA-ZáéíóúñÁÉÍÓÚÑü ]*$/',
                        'message' => 'El nombre debe comenzar con mayúscula y contener solo letras',
                    ]),
                ],
            ])
            ->add('lastName', null, [
                'label' => 'Apellido',
                'attr' => ['placeholder' => 'Rodríguez'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[A-ZÁÉÍÓÚÑÜ][a-zA-ZáéíóúñÁÉÍÓÚÑü ]*$/',
                        'message' => 'El apellido debe comenzar con mayúscula y contener solo letras',
                    ]),
                ],
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