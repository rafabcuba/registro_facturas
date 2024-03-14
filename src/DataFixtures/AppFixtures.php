<?php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Factura;

class AppFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager): void
    {
        // crear administrador
        $user = new User();
        $user->setUsername('admin');
        $user->setFullname('Administrador');
        $user->setEmail('admin@raxpa.cu');
        $user->setRoles(["ROLE_USER","ROLE_ADMIN"]);
        $user->setPassword('$2y$13$q6vgicz3Huvq.OQDTRg//.qrgjEdFBPwJzbAwFYQM7WD3ObMKjNXq');
        $manager->persist($user);
        $manager->flush();

        // crear 20 facturas!
        $this->createMany(Factura::class, 20, function(Factura $factura) {
            $factura->setNumero($this->faker->unique()->creditCardNumber);
            $factura->setProveedor($this->faker->unique()->company);
            $factura->setDescripcion($this->faker->text);
            $factura->setImporte($this->faker->numerify);
            $factura->setFecha($this->faker->dateTimeInInterval('-3 week', '+15 days'));
        });

        $manager->flush();


        $manager->flush();
    }

}
