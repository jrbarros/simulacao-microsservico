<?php declare(strict_types=1);


namespace App\DataFixtures;


use App\Entity\SensitiveInformation;
use App\Helpers\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SensitiveInformationFixtures extends Fixture
{
    public const CPF = '14427832067';

    public function load(ObjectManager $manager)
    {
        $sensitiveInformation = new SensitiveInformation();

        $sensitiveInformation->setCpf(self::CPF);
        $sensitiveInformation->setName('Nome de teste');
        $sensitiveInformation->setAddress('Endereço de teste');

        $manager->persist($sensitiveInformation);
        $manager->flush();
    }
}
