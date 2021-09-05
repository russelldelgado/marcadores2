<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
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
