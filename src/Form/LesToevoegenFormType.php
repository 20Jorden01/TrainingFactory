<?php


namespace App\Form;


use App\Entity\Lesson;
use App\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LesToevoegenFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('time')
            ->add('date',DateType::class, array(
                'years' => range(date('Y'), date('Y')+1),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ))
            ->add('location')
            ->add('max_persons')
            ->add('training_id',EntityType::class, [
                'class' => Training::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }

}