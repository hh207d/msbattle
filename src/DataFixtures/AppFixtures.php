<?php

namespace App\DataFixtures;

use App\Entity\Shiptype;
use App\Entity\User;
use App\Helper\Constant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $availableShipTypes =
            [
                [
                    'name' => 'Carrier',
                    'length' => '5'
                ],
                [
                    'name' => 'Battleship',
                    'length' => 4
                ],
                [
                    'name' => 'Cruiser',
                    'length' => 3
                ],
                [
                    'name' => 'Submarine',
                    'length' => 3
                ],
                [
                    'name' => 'Destroyer',
                    'length' => 2
                ],
            ];

        foreach ($availableShipTypes as $availableShipType) {
            $shipType = new Shiptype();
            $shipType->setName($availableShipType['name']);
            $shipType->setLength($availableShipType['length']);
            $manager->persist($shipType);
            $manager->flush();
        }

        $adminUser = new User();
        $adminUser->setEmail(Constant::COMP_EMAIL);
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword('$argon2id$v=19$m=65536,t=4,p=1$s16uKZdxwe0EptZIslq0LA$3TpWU99ufz/19aI5eHC7pjYsC73lS6u4fwwxAtDx94s');
        $manager->persist($adminUser);
        $user = new User();
        $user->setEmail(Constant::PLAYER_EMAIL);
        $user->setRoles([]);
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$uJCPeZPANEZ08zu46+hQ5Q$UR58YPzvi+mSoqu54jaF+3OR5tnQa/CPdSB/nN2mwHg');
        $manager->persist($user);

        $manager->flush();
    }
}
