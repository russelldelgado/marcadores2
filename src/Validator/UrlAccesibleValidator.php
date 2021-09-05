<?php

namespace App\Validator;

use App\Service\ClienteHttp;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlAccesibleValidator extends ConstraintValidator
{

    private $clienteHttp;

    public function __construct(ClienteHttp $clienteHttp)
    {
        $this->clienteHttp = $clienteHttp;
    }

    public function validate($value, Constraint $constraint)
    {


        if (null === $value || '' === $value) {
            return;
        }

        $codigoEstado = $this->clienteHttp->obtenerCodigoUrl($value);

        if(null == $codigoEstado){
            $codigoEstado = "Erro";
        }

        if($codigoEstado != 200){
            //solo si el codigo es distinto de 200 que seria un codigo correcto entoces que añada la validación 
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ codigo }}', $codigoEstado)
            ->addViolation();

        }


    }
}
