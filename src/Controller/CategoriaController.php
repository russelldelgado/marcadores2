<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/categoria')]
class CategoriaController extends AbstractController
{
    #[Route('/listado', name: 'app_listado_categoria')]
    public function index(CategoriaRepository $categoriaRepository): Response
    {
        $categorias = $categoriaRepository->findAll();

        return $this->render('categoria/index.html.twig', [
            'categorias' => $categorias,
        ]);
    }


  
    //hacemos una inserción de datos en categoría
    #[Route('/nueva', name: 'app_nueva_categoria')]
    public function nueva(CategoriaRepository $categoriaRepository ,EntityManagerInterface $entityManager ,Request $request): Response
    {

        // verificamos que venga el campo de seguridad que hemos indicado en el submit con el csrf_token('categoria') si viene el campo hacemos algo
        //si no viene hacemos otra cosa
        $categoria = new Categoria();
        if($this->isCsrfTokenValid('categoria' , $request->request->get('_token'))){
            $nombre = $request-> request->get('nombre' , null);
            $color = $request-> request->get('color' , null);
            $categoria->setNombre($nombre);
            $categoria->setColor($color);

            if($nombre && $color ){
        //doctrine nos proporciona una clase Entitymaganerinterface , entity tiene un metodo llamado persist que indica a doctrine para 
        //que conozca una entidad , esto no pasa si viene de base de datos ya que entonces si que la conoce pero cuando son registros neuvos si 
        //que hay que indicarlo
                $entityManager->persist($categoria);
        //con flus guardamos este valor y todo lo que haya sido persistido anteriormente
                $entityManager->flush();
                // mostramos un mensaje de que todo ha sido correcto
                $this->addFlash('success' , "Categoria creada correctamente" );
                //redireccionamos a una ruta con el nombre interno de nuestro ruta indicada anteriormente
                return $this-> redirectToRoute('app_listado_categoria',);
            }else{
                if(!$nombre|| !$color ){ //
                    $this->addFlash('danger' , "Todos los campos son obligatorios es obligatorio" );
                }
            }

        }
        //retornamos una ruta que es renderizada por nosotros
        return $this->render('categoria/nueva.html.twig', [
            'categoria' => $categoria
        ]);
    }



    //actualizamos la categoira
//automáticamente si tenemos una variable que pasamos por parametro , symfony tiene algo muy especial que es que si en la cabecera indicamos 
//la entidad , si el parametro coincide con la entidad pasada por la url , lo busca y hace el find.....
#[Route('/{id}/editar', name: 'app_editar_categoria')]
public function editar(Categoria $categoria,EntityManagerInterface $entityManager ,Request $request): Response
{

       if($this->isCsrfTokenValid('categoria' , $request->request->get('_token'))){
        $nombre = $request-> request->get('nombre' , null);
        $color = $request-> request->get('color' , null);
        $categoria->setNombre($nombre);
        $categoria->setColor($color);

        if($nombre && $color){
    //doctrine nos proporciona una clase Entitymaganerinterface , entity tiene un metodo llamado persist que indica a doctrine para 
    //que conozca una entidad , esto no pasa si viene de base de datos ya que entonces si que la conoce pero cuando son registros neuvos si 
    //que hay que indicarlo
            $entityManager->persist($categoria);
    //con flus guardamos este valor y todo lo que haya sido persistido anteriormente
            $entityManager->flush();
            // mostramos un mensaje de que todo ha sido correcto
            $this->addFlash('success' , "Categoria editada correctamente" );
            //redireccionamos a una ruta con el nombre interno de nuestro ruta indicada anteriormente
            return $this-> redirectToRoute('app_listado_categoria',);
        }else{
            if(!$nombre || !$color){
                $this->addFlash('danger' , "Todos los campos son obligatorios" );
            }
        }

    }
    


    return $this->render('categoria/editar.html.twig', [
        'categoria' => $categoria
        
    ]);
}



#[Route('/{id}/eliminar', name: 'app_eliminar_categoria')]
public function eliminar(Categoria $categoria  ,Request $request): Response
{
//recuperamos la entidad de doctrine
    $entityManager = $this-> getDoctrine() -> getManager();
//le decimos que elimine la entidad con este id que viene gracias al parametro id
    $entityManager->remove($categoria);
//hacmeos el commit para que se elimine
    $entityManager->flush();

    $this->addFlash("success" , "Categoría eliminada correctamente");

    return $this->redirectToRoute("app_listado_categoria");
}


}
