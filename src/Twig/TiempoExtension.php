<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TiempoExtension extends AbstractExtension{

    const CONFIGURACION = [
        'formato' => 'Y/m/s',
    ];

    public function getFilters(){

        return [
            new TwigFilter('tiempo' , [$this , 'formatearTiempo']),

        ];

    }

    public function formatearTiempo($fecha , $configuracion = []){
        $configuracion = array_merge(self::CONFIGURACION , $configuracion);
        $fechaActual = new \DateTime();
        $fechaFormateada = $fecha->format($configuracion['formato']);//fecha formateada sera igual a la fecha con la configuracion que tega en su campo formato
        $diferenciaFechasSegundos = $fechaActual->getTimestamp() - $fecha->getTimestamp(); // restamos la fecha actual menos la fecha que nos viene

        if($diferenciaFechasSegundos < 60){
            $fechaFormateada = "Creado ahora mismo";
        }elseif($diferenciaFechasSegundos <3600){
            $fechaFormateada = "Creado recientemente";
        }elseif($diferenciaFechasSegundos < 14400){
            $fechaFormateada = "Creado hace unas horas";
        }

        return $fechaFormateada;

    }


}