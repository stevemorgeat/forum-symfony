<?php

namespace AppBundle\Form;

use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class,
                ["label" => "Prénom :",
                    "required" => true,
                    "attr" => ["placeholder" => "ici votre Prénom"]
                ])
            ->add('name', TextType::class,
                ["label" => "Nom :",
                    "required" => true,
                    "attr" => ["placeholder" => "ici votre nom"]
                ])
            ->add('email', EmailType::class,
                ["label" => "Email :",
                    "required" => true,
                    "attr" => ["placeholder" => "ici votre email"]
                ])
            ->add('plainPassword', RepeatedType::class,
                ["type" => PasswordType::class,
                    "first_options" => ["label" => "Mot de Passe",
                        "required" => true,
                        "attr" => ["placeholder" => "ici votre mot de passe"]],
                    "second_options" => ["label" => "Confirmer votre mot de Passe",
                        "attr" => ["placeholder" => "Confirmer votre mot de passe"]]

                ])
            ->add("submit",SubmitType::class,["label" => "Valider"]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Author'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_author';
    }


}
