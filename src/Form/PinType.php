<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // ajouter des contraintes uniquement pour l'édition du form - récupération de la methode utilisée
        // $imageFileConstraints = [];
        // $isEdit = $options['method'] === 'PUT';
        // if($isEdit){
        //     $imageFileConstraints[] = 
        // }

        // method 2 - récupération de l'id
        // $pin = $options['data'];
        // $isEdit = $pin && $pin->getId();   

        $builder
            ->add('imageFile', VichImageType::class, [
                'label'=> 'Image(JPG or PNG file)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image',
                'download_uri' => false,
                'imagine_pattern' => 'square_thumbnail_small'
                // 'constraints'=>[
                //     new Image(['maxSize'=>'1M'])
                // ]
            ])
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
