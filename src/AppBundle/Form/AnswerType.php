<?php

namespace AppBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', CKEditorType::class,["label" => " ",
                "attr" => ["placeholder" => "Saisir votre texte ici...",
                    "rows" => 4],
                "required" => true])
            ->add('submit',SubmitType::class,
                ["label"=>"Valider",
                    "attr" => ["class" => "btn btn-primary"]
                ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Answer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_answer';
    }


}
