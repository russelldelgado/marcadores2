<?php

namespace App\Form;

use App\Entity\Marcador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class MarcadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('url')
            ->add('categoria')
            ->add('favorito')
            ->add('etiqueta' , Select2EntityType::class , [
                    'multiple' => true, //se puede añadir mas de una etiqueta
                    'remote_route' => 'app_buscar_etiquetas' ,//esta es la funcion que nos va a devolver el listado de etiquetas que se pueden mostrar 
                    'class' => '\App\Entity\Etiqueta',//esta es la clase que vamos a renderizar aqui
                    'primary_key' => 'id' ,//por defecto es id , ya que coje la primary key de la clase Etiqueta que hemos renderizado antes
                    'text_property' => 'nombre' ,// Este es el nombre por defecto que se va a mostrar.
                    'minimum_input_length' => 3 , //Numero mínimo de caracteres que tenemos que introducir para que realice una búsqueda
                    'delay' => 3 , // tiempo antes de que empiece ha hacer la busqueda
                    'cache' =>false , //indicamos que no tiene cache , que todo el tiempo se traiga los valores que tiene ne la base de datos
                    'placeholder' => 'Selección de etiquetas',
                    'allow_add' =>[
                        'enabled' => true,
                        'new_tag_text' => '(nuevo)' ,// esto lo ponemos para diferenciar cuales son nuevos y cuales no lo son
                        'tag_separators' => '[","]' , //indicamos que los separadores en este caso es la "," coma , por defecto es el espacio
                    ] , //se permiten añadir nuevos registros desde aquí ya que solo tiene un campo y no tendríamos problemas
                    ]) //para esto nos vamos a descargar un bundle para un selector que sera select2entity-bundle
                            //este bundle requiere de dos dependencias --selec2css y select2js que tendremos que descargarlas
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Marcador::class,
        ]);
    }
}
