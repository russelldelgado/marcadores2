<?php

namespace App\EventSuscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

//esto lo creamos porque es necesario que nos suscribamos a este evento para que llamemos la ruta que llamemos siempre este escuchando 


//creamos una clase que implemente una interfaz que nos dan para los eventos , en este caso para los subcriber 

class IdiomaSuscriber  implements EventSubscriberInterface{


    //EN ESTE MOMENTO NO PODEMOS ACCEDER AL REQUEST ASI QUE ACCEDEMOS POR EVENTO , YA QUE ES LA ÚNICA MANERA DE ACCEDER A EL

    public function alComienzoDelRequest(RequestEvent $event){

        $request = $event->getRequest(); //Recuperamos datos del evento
        //si viene el idioma , tenemos que generar una sesión sobre el objeto sesión  que sera _locale

        if($locale =$request->attributes->get('_locale')){
            $request->getSession()->set('_locale' , $locale);
        }else{
            //si no fuera el dato en request entonces , symfony tiene un metodo para esto recuperar el idioma y mostrarlo

            $request->setlocale($request->getSession()->get('_locale', $request->getDefaultLocale()));//al final si no existiera el locale lo que tenemos que hacer es trarernos el get defalt lcoale de symfony
        }

    

    }


//este metodo nos lo pide la interfaz
    public static function getSubscribedEvents(){
    //ahora tenemos que retornar un array de eventos a los que nos vamos a conectar , en este caso es un evento del kernel event y elemento del reques,
    //indicamos al metodo donde nos vamos a subcribir y la prioridad que queramos darle a este   
    //podemos tener tambien dos o tres funciones que se enganchen al mismo evento pero en distintos momentos
        return [
            KernelEvents::REQUEST =>['alComienzoDelRequest' ,20]
         ];
    }

}

