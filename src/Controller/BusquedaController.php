<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BuscadorType;
use App\Repository\MarcadorRepository;
use Symfony\Component\HttpFoundation\Request;

class BusquedaController extends AbstractController
{
    
    public const ELEMENTO_POR_PAGINA = 1;



//symfony busca por orden , es decir tendremos que introducir primero lo que es favoritos en el controlador y despues el otro ya que si no symfony nos daria un fallo
//todos los controladores tiene que devolver un response si o si (render , thow , etc symphony lo trata como response)

#[Route("/buscar/{busqueda}/{pagina}", name:"app_busqueda" , defaults:['busqueda' => '' , 'pagina' => 1] , requirements: ['pagina' => '\d+'])]
public function busqueda(String $busqueda  , int $pagina, MarcadorRepository $marcadorRepository , Request $request){
    //generamos el formulario de busqueda , he indicamos el tipo de formulario que es en este caso buscador type
    $formularioBusqueda = $this->createForm(BuscadorType::class);
    //primero validamos que el furmulario este enviado correctamenme y cargamos los datos con el request
    $formularioBusqueda->handleRequest($request);
    //definimos marcadores como un array vacio
    $marcadores = [];
    //validamos que el formulario se haya enviado
    if($formularioBusqueda-> isSubmitted()){
        //validamos que los datos del formulario sean validos
        if($formularioBusqueda->isValid()){
            //obtenemos los valores del formulario , lo podríamos recoger directamente del request pero lo hacemos de esta manera para verla simplemente
            $busqueda = $formularioBusqueda->get('busqueda')->getData();
        }
    }
    // $busqueda = (int) $busqueda > 0 ?  (int)$busqueda : $busqueda;

    // if(is_int($busqueda)){
    //     $busqueda = 'todas';
    //     $pagina = $busqueda;
    // }

    //si busqueda no esta vacia lo que hacemos es buscar en nuestra base de datos
    if(!empty($busqueda)){
        $marcadores = $marcadorRepository->buscarPorNombre($busqueda , $pagina , self::ELEMENTO_POR_PAGINA);
    }
    //ahora bien si el forulario es enviado correctamente o no esta vacio me tiene que renderizar la vista 
    if(!empty($busqueda) || $formularioBusqueda->isSubmitted()){

        return $this->render('index/index.html.twig',[
            'formulario_busqueda' => $formularioBusqueda->createView(), //creame la vista del formulario
            'marcadores' =>$marcadores , //creame la lista de los marcadores 
            'elementos_por_pagina' => self::ELEMENTO_POR_PAGINA,
            'pagina' => $pagina,
            'parametros_ruta' => [
                'busqueda' => $busqueda,
            ],//le pasamos esto para que guarde los valores para cuando nos vamos entre págians
        ]);

    }

    return $this->render('busqueda/_buscador.html.twig',[
        'formulario_busqueda' => $formularioBusqueda->createView(), //creame la vista del formulario
    ]);

   
}



}
