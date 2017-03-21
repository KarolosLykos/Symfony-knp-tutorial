<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21/3/2017
 * Time: 5:07 μμ
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HelpFormExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['help']){
            $view->vars['help'] = $options['help'];
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('help', null);
    }


    public function getExtendedType()
    {
        return FormType::class;
    }

}