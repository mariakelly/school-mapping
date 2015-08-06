<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', null, array('required' => false))
            ->add('website', null, array('required' => false))
            ->add('isFeatured', null, array('required' => false))
            ->add('isDistrictWide', null, array('required' => false))
            ->add('years')
            ->add('topics')
            ->add('groups', null, array(
                'class' => 'AppBundle:DivisionOrGroup',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                }
            ))
            ->add('people', null, array('required' => false))
            ->add('activities', null, array(
                'class' => 'AppBundle:Activity',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                }
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_project';
    }
}
