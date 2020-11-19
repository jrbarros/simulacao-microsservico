<?php declare(strict_types=1);


namespace App\DataFixtures;


use App\Entity\SensitiveInformation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SensitiveInformationFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $sensitiveInformation = new SensitiveInformation();

        $manager->persist($sensitiveInformation);
        $manager->flush();
    }
}
