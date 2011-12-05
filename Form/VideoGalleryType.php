<?php

namespace ImiBorbas\VideoGalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class VideoGalleryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('videoServiceName', 'choice', array(
                'choices'  => array('youtube' => 'YouTube'),
                'required' => true
            ))
            ->add('playlistId')
            ->add('name')
            ->add('slug')
        ;
    }

    public function getName()
    {
        return 'imiborbas_videogallerybundle_videogallerytype';
    }
}
