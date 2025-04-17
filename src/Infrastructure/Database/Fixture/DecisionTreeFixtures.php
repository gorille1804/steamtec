<?php

namespace Infrastructure\Database\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;

class DecisionTreeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create Categories
        // datas
        $cats = [
            ['id' => null, 'name' => 'PROBLEME DE FONCTIONNEMENT GENERAL', 'problems' => []],
            ['id' => null, 'name' => 'PROBLEME DE PRESSION', 'problems' => []],
            ['id' => null, 'name' => 'PROBLEME DE CHAUFFE', 'problems' => []],
        ];
        for ($i=0; $i < count($cats); $i++) {
            $cat = new Category(
                CategoryId::make(),
                $cats[$i]['name']
            );
            $manager->persist($cat);
            $cats[$i]['id'] = $cat->id;
        }

        $manager->flush();

        // Create ProblemTypes
        $datas = [
            ['id' => null, 'name' => 'LA MACHINE NE DEMARRE PAS', 'categoryId' => $cats[0]['id']],
            ['id' => null, 'name' => 'PROGRAMMATEUR EN ERREUR (affiche ERR)', 'categoryId' => $cats[0]['id']],
            ['id' => null, 'name' => 'PROGRAMMATEUR ne s\'allume plus ou clignote', 'categoryId' => $cats[0]['id']],
            ['id' => null, 'name' => 'PLUS DE CONSOMMATION D\'ANTICALCAIRE', 'categoryId' => $cats[0]['id']],

            ['id' => null, 'name' => 'PAS DE PRESSION AU MANO (LA POMPE NE TOURNE PAS) ALORS QU\'ON APPUIE SUR LA GACHETTE', 'categoryId' => $cats[1]['id']],
            ['id' => null, 'name' => 'IMPOSSIBLE DE MONTER EN PRESSION SORTIE DE POMPE (AU MANO) et le moteur tourne', 'categoryId' => $cats[1]['id']],
            ['id' => null, 'name' => 'PRESSION OK AU MANO MAIS PAS OU PEU DE SORTIE D\'EAU AUX ACCESSOIRES', 'categoryId' => $cats[1]['id']],
            ['id' => null, 'name' => 'PRESSION SACCADEE', 'categoryId' => $cats[1]['id']],

            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PLUS VOYANT VERT ALLUME', 'categoryId' => $cats[2]['id']],
            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PLUS VOYANT VERT RESTE ETEINT', 'categoryId' => $cats[2]['id']],
            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PAS ASSEZ', 'categoryId' => $cats[2]['id']],
            ['id' => null, 'name' => 'VOYANT VERT S\'ETEINT JUSQU\'A UNE TEMPERATURE RELATIVEMENT BASSE', 'categoryId' => $cats[2]['id']],
            ['id' => null, 'name' => 'LA MACHINE MONTE TROP EN TEMPERATURE', 'categoryId' => $cats[2]['id']],
            ['id' => null, 'name' => 'LA MACHINE FUME BEAUCOUP', 'categoryId' => $cats[2]['id']],
        ];

        for ($i=0; $i < count($datas); $i++) {
            $problemType = new ProblemType(
                ProblemTypeId::make(),
                $datas[$i]['name'],
                $datas[$i]['categoryId']
            );
            $manager->persist($problemType);
            $datas[$i]['id'] = $problemType->id;
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['decision-tree'];
    }
}
