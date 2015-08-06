<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ActivityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('years')
            ->add('project', null, array(
                'class' => 'AppBundle:Project',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                }
            ))
            ->add('isDistrictWide', null, array('required' => false))
            ->add('school')
            ->add('activityCategory')
            ->add('topics')
            ->add('groups', null, array(
                'class' => 'AppBundle:DivisionOrGroup',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                }
            ))
            ->add('people', null, array('required' => false))
            ->add('website')
            ->add('details', null, array('required' => false))
            ->add('isFeatured', null, array('required' => false))
            ->add('shortDescription', null, array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Activity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_activity';
    }
}
