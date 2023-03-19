<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Musique;
use App\Entity\Style;
use App\Repository\ArtistRepository;
use App\Repository\StyleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MusiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'years' => range(date('Y') - 60, date('Y')),
                'label' => 'Année de sortie '
            ])
            // ->add('artist')
            ->add('style', EntityType::class, [
                'class' => Style::class,
                'query_builder' => function(StyleRepository $er)
                    {
                        return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                    },
                'choice_label' => 'name',
                'label' => 'Genre de musique ',
                'placeholder' => 'Genre de la musique'
                ])
            ->add('img', FileType::class, [
                'label' => 'Votre image (png, jpeg, webp)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Seul les formats png, jpg ou encore webp sont acceptés',
                    ])
                        
                    
                ],
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musique::class,
        ]);
    }
}
