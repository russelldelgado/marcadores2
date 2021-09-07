<?php

namespace App\Repository;

use App\Entity\Marcador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Marcador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marcador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marcador[]    findAll()
 * @method Marcador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarcadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marcador::class);
    }



    public function buscarPorNombreDeCategoria($nombreCategoria , $pagina = 1 , $elementos_por_pagina = 5){
        $query =  $this->createQueryBuilder('m') //creamos una consulta donde m significa que es esta clase
                    ->innerJoin('m.categoria' , 'c')// le decimos que quermos las categorias de la clase categoria y que son iguales que m.categoria
                    ->where('c.nombre = :nombreCategoria') //le decimos que el nombre de categoria sera igual al parametro nombreCategoria
                    ->setParameter('nombreCategoria' , $nombreCategoria) //indicamos que :nombreCategoria es igual a la variable que le estamos pasando
                    ->orderBy('m.creado' , 'DESC')
                    ->orderBy('m.nombre' , 'ASC')
                    ->getQuery(); //traemos la consulta
                   // ->getResult(); //traemos todos los resultados en este caso serian varios aunque podria ser solo uno  

        return $this->paginacion($query , $pagina , $elementos_por_pagina); // en este caso devolvemos nuestro paginador creado justo debajo
    }


    public function buscarTodos( $pagina = 1 , $elementos_por_pagina = 5){
        $query =  $this->createQueryBuilder('m') //creamos una consulta donde m significa que es esta clase
                    ->orderBy('m.creado' , 'DESC')
                    ->addOrderBy('m.nombre' , 'ASC')
                    // ->orderBy('m.nombre' , 'ASC') //de la manera de arriba y de la de abajo me lo ordena bien en esta ocación no sabemos en otra
                    ->getQuery(); //traemos la consulta
                   // ->getResult(); //traemos todos los resultados en este caso serian varios aunque podria ser solo uno  

        return $this->paginacion($query , $pagina , $elementos_por_pagina); // en este caso devolvemos nuestro paginador creado justo debajo
    }

    public function buscarPorNombre($nombre , $pagina = 1 , $elementos_por_pagina = 5){
        $query =  $this->createQueryBuilder('m') //creamos una consulta donde m significa que es esta clase
                    ->where('m.nombre like :nombre') //le decimos que el nombre de categoria sera igual al parametro nombreCategoria
                    ->setParameter('nombre' , "%$nombre%") //indicamos que :nombreCategoria es igual a la variable que le estamos pasando
                    ->orderBy('m.creado' , 'DESC') // ORDENAME LOS RESULTADOS DESCENDIENTEMENTE
                    ->getQuery(); //traemos la consulta
                    //->getResult(); //traemos todos los resultados en este caso serian varios aunque podria ser solo uno  
    
        return $this->paginacion($query , $pagina , $elementos_por_pagina);
    }


    //generamos una query para todos los favoritos

    public function buscarPorFavoritos($pagina = 1 , $elementos_por_pagina = 5 ){

        $query = $this->createQueryBuilder('m')
                      ->where('m.favorito = true')
                      ->orderBy('m.creado' , 'DESC')
                      ->addOrderBy('m.nombre' , 'ASC')
                      ->getQuery();

        return $this->paginacion($query , $pagina , $elementos_por_pagina);

    }


    // crearemos una función que nos ayude a realziar una paginación mas sencilla
    //de esta manera todos nuestros métodos llamen a esta función para paginar y sea mucho mas cómodo

//dql es el lenguaje que utiliza doctrine , y es lo que le indicaremos , 
    public function paginacion($dql , $pagina =  1  , $elementos_por_pagina = 5){

        //vamos a utilizar un paginador de doctrine que ya viene por defecto
        $paginador = new Paginator($dql);
        $paginador->getQuery()
                  -> setFirstResult($elementos_por_pagina * ($pagina - 1))// esto es como hacer el offset , que es el offset que indica desde donde tiene que empezar
                  ->setMaxResults($elementos_por_pagina); // este seria el numero máximo de elementos que me tiene que traer

        //ahora devolvermos el paginador
        return $paginador;
    }

    // /**
    //  * @return Marcador[] Returns an array of Marcador objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Marcador
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
