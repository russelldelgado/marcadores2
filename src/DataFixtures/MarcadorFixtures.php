<?php

namespace App\DataFixtures;

use App\Entity\Marcador;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MarcadorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        for ($i=0; $i < 20; $i++) { 
            $marcador = new Marcador();
            $marcador->setNombre("Google $i");
            $marcador->setUrl("https://www.google.com");
            $marcador->setCategoria($this->getReference(CategoriaFixtures::CATEGORIA_INTERNET_REFERENCIA)); // en este caso no tenemos una categoria como tal ya que pertenece a la otra clase por eso tenemos que añadir una referencia
            $manager->persist($marcador);
            $manager->flush();
        }


    }

    public function getDependencies(){
        return [
            CategoriaFixtures::class,

        ];
    }

  
}
