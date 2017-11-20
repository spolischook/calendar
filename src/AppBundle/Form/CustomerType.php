<?php

namespace AppBundle\Form;

use AppBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'label' => 'form.country',
                'attr' => [
//                    'style' => 'max-width: 200px',
                ],
                'preferred_choices' => ['UA'],
            ])
            ->add('city', null, [
                'label' => 'form.city',
            ])
            ->add('np', IntegerType::class, [
                'label' => 'form.np',
            ])
            ->add('amount', IntegerType::class, [
                'label' => 'form.amount',
            ])
            ->add('snAccount', null, [
                'label' => 'form.social_network_account',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Order::class,
        ));
    }
}
