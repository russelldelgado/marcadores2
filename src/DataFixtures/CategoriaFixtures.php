<?php
//IMPORTANTE LOS FIXTURES SE CARGAN POR ORDEN DE FICHERO , ES DECIR A-B-C..... DEL PRIMER FICHERO QUE SE ENCUENTRA AL ULTIMO 
//SI POR CASUALIDAD EL PRIMERO DEPENDE DE OTROS DOS FALLARA A NO SE QUE implementemos la calse de implements DependentFixtureInterface y metamos el método getDependencies y return [dependencias de las que depende]
namespace App\DataFixtures;

use App\Entity\Categoria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriaFixtures extends Fixture
{

    public const CATEGORIA_INTERNET_REFERENCIA = 'categoria-internet';

    public function load(ObjectManager $manager)
    {

        
        $categoria = new Categoria();
        $categoria->setNombre("Internet");
        $categoria->setColor("green");
        $manager->persist($categoria);
         $manager->flush();

        //una vez creado nuestra categoria internet referencia tenemos que crear la referencia

        $this->addReference(Self::CATEGORIA_INTERNET_REFERENCIA , $categoria); // LO PRIMERO QUE AÑADIMOS ES EL NOMBRE DE LA REFERENCIA Y DESPUES EL OBJETO AL QUE HARA REFERENCIA

    }
}
