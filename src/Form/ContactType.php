<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => ['class' => 'nom-input'],
                'label' => 'Nom'
            ])
            ->add('lastName', TextType::class, [
                'attr' => ['class' => 'prenom-input'],
                'label' => 'Prénom'
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'email-input'],
                'label' => 'Adresse e-mail'
            ])
            ->add('phone', IntegerType::class, [
                'attr' => ['class' => 'phone-input'],
                'label' => 'Numéro de téléphone'
            ])
            ->add('publichedAt', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'date-input'],
                'label' => 'Date souhaitez pour le rendez-vous'
            ])
            ->add('subject', TextType::class, [
                'attr' => ['class' => 'objet-input'],
                'label' => 'Objet',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L’objet ne peut pas être vide.'
                    ]),
                    new Assert\Length([
                        'min' => 5,
                        'minMessage' => 'L’objet doit contenir au moins {{ limit }} caractères.',
                        'max' => 255, // facultatif
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'message-input'],
                'label' => 'Message',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le message ne peut pas être vide.'
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'max' => 2000, // facultatif
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class, // 🔗 liaison avec l'entité
        ]);
    }
}
