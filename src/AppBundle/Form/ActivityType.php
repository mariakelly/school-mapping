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
            ->add('shortDescription', null, array('required' => false))
            ->add('project', null, array(
                'class' => 'AppBundle:Project',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                }
            ))
            ->add('website', null, array(
                'label' => 'Project/Activity Website'
            ))
            ->add('years')
            // ->add('isDistrictWide', null, array(
            //     'label' => 'Is this activity district-wide?'
            //     'required' => false
            // ))
            ->add('school', null, array(
                'class' => 'AppBundle:School',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                }
            ))
            ->add('activityCategories')
            // ->add('topics')
            ->add('groups', null, array(
                'class' => 'AppBundle:DivisionOrGroup',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                }
            ))
            ->add('people', null, array(
                'class' => 'AppBundle:Individual',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'required' => false
            ))
            ->add('details', null, array(
                'label' => 'Details (ADMIN ONLY)',
                'required' => false,
            ))
            // ->add('isFeatured', null, array('required' => false))
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
