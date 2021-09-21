<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IdiomaController extends AbstractController
{

    //_locale es una variable especial que tiene symfony para localizar un url con el idioma indicado , si no encuentra el idioma indicado pondra el sigueinte en la cola 
    //ruta es la ruta  la que redireccionaremos cuando cambiemos el idioma
    #[Route('/idioma/{_locale}/{ruta}', name: 'app_idioma', defaults: [ "ruta" => ""]) ]
    public function index(string $ruta , Request $request): Response
    {

//esto no existe actualmente pero lo vamos a crear nosotoros
//symfony tiene el get locale que intenta acceder a reques y obtinee el get locale

    $idiomaActual  = $request->getSession()->get("_locale");
//tenemos que obtener el metodo de la request cual es , ya que esto se va a renderizar tanto en el get como en el post
    $metodo = $request->getMethod();
//porque se va a renderizar tanto en get como en post , porque ahora tenemos que ir a main y hacerle un render controller de esto 

    //indicamos que si viene por post ahora redirigiremos a la ruta que viene del request   


    if('POST' === $metodo){
        $ruta = $request->request-> get('ruta');
       return $this->redirectToRoute($ruta);
    }


    return $this->render('idioma/index.html.twig', [
        'ruta' => $ruta , 
        'idioma_actual' => $idiomaActual, 
    ]);
    }
}
