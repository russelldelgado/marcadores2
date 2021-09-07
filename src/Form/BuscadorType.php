<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class BuscadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //creamos el campo del buscador y decimos que es de tipo texttype::class
            ->add('busqueda' , //este es el buscador que estamos añadiendo
            TextType::class ,
            [
                'label' => null,
                'constraints' =>[
                    new NotBlank(),
                ], //como este formulario no tiene entidad hacemos aqui las validaciones del formulario, aun que tuviera entidad tambien pudieramos hacerlo aquí
                'attr' => [
                    'placeholder' => 'buscar...', //los atributos del formulario son todos aquellos datos extras como clases , placeholder etc dependiendo del tipo de campo que sea
                    'class' => 'form-control mr-sm-2' ,
                ]
            ]
            )
            ->add('buscar', // este es el boton de submit que añadimos
                SubmitType::class,
                [
                    'label' => 'Buscar',
                    'attr' => [
                        'class' => 'btn-outline-primary my-2 my-sm-0'
                    ]    
                ]
            )
        ;
    }
}
//todo esto es para generar el formulario ahora toca generar la vista que lo haremos en fichero comunes

//PASOS DE CREACIÓN
//1-FORMULARIO.PHP
//2-VISTA.HTML.TWIG
//3-CONTROLADOR.PHP
//4-desde twig renderizamos nuestro controlador de symfony con render
//5- al ser un buscador creamos una query para buscar por nombre en repository marcador controller
