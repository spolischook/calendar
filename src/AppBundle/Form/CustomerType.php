<?php

namespace AppBundle\Form;

use AppBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'form.name',
            ])
            ->add('phone', null, [
                'label' => 'form.phone',
            ])
            ->add('country', CountryType::class, [
                'attr' => [
                    'style' => 'max-width: 200px',
                    'label' => 'form.country',
                ]
            ])
            ->add('city', null, [
                'label' => 'form.city',
            ])
            ->add('np', null, [
                'label' => 'form.np',
            ])
            ->add('amount', null, [
                'label' => 'form.amount',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Customer::class,
        ));
    }
}
