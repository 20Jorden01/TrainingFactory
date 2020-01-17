<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstructeurToevoegenFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('firstname')
            ->add('lastname')
            ->add('preprovision', TextType::class ,['required' => false])
            ->add('dateofbirth', DateType::class, array(
                'years' => range(date('Y'), date('Y')-120),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ))
            ->add('email', EmailType::class)
            ->add('gender',ChoiceType::class,
                array('choices' => array(
                    'man' => 'man',
                    'vrouw' => 'vrouw',
                    'anders' => 'anders'),
                    'multiple'=>false,'expanded'=>true, 'label'=>false))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}