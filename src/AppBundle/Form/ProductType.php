<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 25/10/17
 * Time: 15:22
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class ProductType extends AbstractType
{
    private $translator;

    public function __construct(Translator $translator){
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => $this->translator->trans('product.title')])
            ->add('price', MoneyType::class, ['label' => $this->translator->trans('product.price')])
            ->add('description', TextareaType::class, ['label' => $this->translator->trans('product.description')])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Product'
        ]);
    }
}