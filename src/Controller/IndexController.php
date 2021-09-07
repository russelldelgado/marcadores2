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

class IndexController extends AbstractController
{
//symfony busca por orden , es decir tendremos que introducir primero lo que es favoritos en el controlador y despues el otro ya que si no symfony nos daria un fallo
//todos los controladores tiene que devolver un response si o si (render , thow , etc symphony lo trata como response)

    #[Route("/buscar/{busqueda}", name:"app_busqueda" , defaults:['busqueda' => ''])]
    public function busqueda(String $busqueda , MarcadorRepository $marcadorRepository , Request $request){
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
        //si busqueda no esta vacia lo que hacemos es buscar en nuestra base de datos
        if(!empty($busqueda)){
            $marcadores = $marcadorRepository->buscarPorNombre($busqueda);
        }
        //ahora bien si el forulario es enviado correctamente o no esta vacio me tiene que renderizar la vista 
        if(!empty($busqueda) || $formularioBusqueda->isSubmitted()){

            return $this->render('index/index.html.twig',[
                'formulario_busqueda' => $formularioBusqueda->createView(), //creame la vista del formulario
                'marcadores' =>$marcadores  //creame la lista de los marcadores 
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



    #[Route("/favoritos", name:"app_favoritos")]
    public function favoritos(MarcadorRepository $marcadorRepository){

        $marcadores = $marcadorRepository->findBy([
            'favorito' => true,
        ]);
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);
    }



    #[Route('/{categoria}', name: 'app_index' , defaults: ['categoria' => ''])]
    public function index(String $categoria,CategoriaRepository $categoriaRepository, MarcadorRepository $marcadorRepository): Response
    {

        if(!empty($categoria)){//comprovamos que el campo no este vacio 

            if(!$categoriaRepository->findByNombre($categoria)){
                throw $this-> createNotFoundException("La categoria '$categoria' no existe");
            }
            
            $marcadores = $marcadorRepository->buscarPorNombreDeCategoria($categoria);
        }else{
            $marcadores = $marcadorRepository->findAll();
        }
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);


        // return $this->render('index/index.html.twig', [
        //     'marcadores' => $marcadorRepository->findAll(),
        // ]);
    }
}
