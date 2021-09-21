<?php

namespace App\Controller;

use App\Entity\Marcador;
use App\Form\BuscadorType;
use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController
{

    //definimos los elementos por página que se le pueden pasar a un elemento

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

        return $this->render('comunes/_buscador.html.twig',[
            'formulario_busqueda' => $formularioBusqueda->createView(), //creame la vista del formulario
        ]);

       
}



    #[Route("/editar-favorito", name:"app_editar_favoritos")]
    public function editarFavorito(MarcadorRepository $marcadorRepository , Request $request){//tengo que acceder a la reques para indicar que hay una respuestas

        //validamos que lo que venga en la respueta sea una peticion ayax
        if($request->isXmlHttpRequest()){
            $actualizado = true;
            $idMarcador= $request->get('id');//recuperamos el dato id
            $entityManager = $this->getDoctrine()->getManager();//Accedemos a doctrine
            $marcador = $marcadorRepository->findOneById($idMarcador); //recuperamos el marcador
            $marcador->setFavorito(!$marcador->getFavorito());

            try {
                $entityManager->flush(); // hacemos esto para que se realicen los cambios
            } catch (\Exception $e) {
                $actualizado=false;
            }


            //y ahora tenemos que devolver una respuesta en formato json

            return $this->json([
                'actualizado' => $actualizado,
            ]);
        }

        //si esto no es una peticon haya lanzamos una exception no found exception
        throw $this->createNotFoundException();
}



    #[Route("/favoritos/{pagina}", name:"app_favoritos" ,defaults: ['pagina' => 1] ,requirements:['pagina' => '\d+'])]
    public function favoritos(int $pagina , MarcadorRepository $marcadorRepository){

        $elementos_por_pagina = self::ELEMENTO_POR_PAGINA; 

        $marcadores = $marcadorRepository->buscarPorFavoritos($pagina , $elementos_por_pagina);
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
            'pagina' => $pagina,
            'elementos_por_pagina' =>  $elementos_por_pagina,
        ]);
    }

// indicamos que pueden pasarle una categoria o un parametro página que por defecto tendran un valor específico
//en este apartado trabajamos con los requirimientos en el cual tenemos que indicar que pagina es de tipo numérico para que no nos de error , si no haria un match y devolvería un error 404
//tendremos que modificar la query para que acepte el valor de elementos por categoria y nos devuelva lo que queremos


    #[Route('/{categoria}/{pagina}', name: 'app_index' , defaults: ['categoria' => 'todas'  , 'pagina' => 1] , requirements:['pagina' => '\d+'])]
    public function index(
        String $categoria, 
        int $pagina ,
        CategoriaRepository $categoriaRepository, 
        MarcadorRepository $marcadorRepository,
        TranslatorInterface $translator,
        
        ): Response
    {
        $elementosPorPagina= self::ELEMENTO_POR_PAGINA;
        // en algunas ocaciones categoria no viene asique tendremos que arrelgar eso 
        //para eso comprobamos y si categoria en verdad es un entero eso quiere decir que categoria es una pagina 

        $categoria = (int) $categoria > 0 ?  (int)$categoria : $categoria;

        if(is_int($categoria)){
            $categoria = 'todas';
            $pagina = $categoria;
        }

        if($categoria && 'todas' !== $categoria){//comprovamos que el campo no este vacio 

            if(!$categoriaRepository->findByNombre($categoria)){

                throw $this-> createNotFoundException( $translator->trans("La categoria \"{categoria}\" no existe" , [  //{categoria} -> no tiene porque tener este formato , puede tenre otro pero lo ponemos así par que no vayamos a tener errores, podemos poner existe o lo que sea y se cambiaría eso
                    '{categoria}' => $categoria, // le pasamos la categoria
                ],
                'messages', //las transcripciones de validación se encontraran en el fichero de validación de nuestra propia aplicación la de message
            ));
                // throw $this-> createNotFoundException("La categoria '$categoria' no existe");
            }
            
            $marcadores = $marcadorRepository->buscarPorNombreDeCategoria($categoria , $pagina , $elementosPorPagina);
        }else{
            $marcadores = $marcadorRepository->buscarTodos($pagina , $elementosPorPagina);
        }
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
            'pagina' => $pagina,
            'parametros_ruta' => [
                'categoria' => $categoria,
            ],//le pasamos esto para que guarde los valores para cuando nos vamos entre págians
            'elementos_por_pagina' => $elementosPorPagina,
        ]);


        // return $this->render('index/index.html.twig', [
        //     'marcadores' => $marcadorRepository->findAll(),
        // ]);
    }
}
