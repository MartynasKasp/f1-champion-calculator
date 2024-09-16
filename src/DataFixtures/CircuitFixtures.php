<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CircuitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $circuit = (new Circuit())
            ->setCountry('Italy')
            ->setCircuit('Autodromo Nazionale Monza');
        $manager->persist($circuit);
        $this->addReference('circuit_monza', $circuit);

        $circuit = (new Circuit())
            ->setCountry('Singapore')
            ->setCircuit('Marina Bay Street Circuit');
        $manager->persist($circuit);
        $this->addReference('circuit_marina_bay', $circuit);

        $circuit = (new Circuit())
            ->setCountry('Japan')
            ->setCircuit('Suzuka International Racing Course');
        $manager->persist($circuit);
        $this->addReference('circuit_suzuka', $circuit);

        $circuit = (new Circuit())
            ->setCountry('Qatar')
            ->setCircuit('Lusail International Circuit');
        $manager->persist($circuit);
        $this->addReference('circuit_lusail', $circuit);

        $manager->flush();
    }
}
