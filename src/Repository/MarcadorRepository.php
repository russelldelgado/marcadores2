<?php

namespace App\Repository;

use App\Entity\Marcador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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



    public function buscarPorNombreDeCategoria($nombreCategoria){
        return $this->createQueryBuilder('m') //creamos una consulta donde m significa que es esta clase
                    ->innerJoin('m.categoria' , 'c')// le decimos que quermos las categorias de la clase categoria y que son iguales que m.categoria
                    ->where('c.nombre = :nombreCategoria') //le decimos que el nombre de categoria sera igual al parametro nombreCategoria
                    ->setParameter('nombreCategoria' , $nombreCategoria) //indicamos que :nombreCategoria es igual a la variable que le estamos pasando
                    ->getQuery() //traemos la consulta
                    ->getResult(); //traemos todos los resultados en este caso serian varios aunque podria ser solo uno  
    }

    public function buscarPorNombre($nombre){
        return $this->createQueryBuilder('m') //creamos una consulta donde m significa que es esta clase
                    ->where('m.nombre like :nombre') //le decimos que el nombre de categoria sera igual al parametro nombreCategoria
                    ->setParameter('nombre' , "%$nombre%") //indicamos que :nombreCategoria es igual a la variable que le estamos pasando
                    ->getQuery() //traemos la consulta
                    ->getResult(); //traemos todos los resultados en este caso serian varios aunque podria ser solo uno  
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
