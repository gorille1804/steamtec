<?php

namespace Infrastructure\Database\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use Domain\DecisionTree\Data\Enum\DiagnosticStepType;
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
            ['id' => null, 'name' => 'PROBLEME DE FONCTIONNEMENT GENERAL', 'position' => 1, 'problems' => []],
            ['id' => null, 'name' => 'PROBLEME DE PRESSION', 'position' => 2, 'problems' => []],
            ['id' => null, 'name' => 'PROBLEME DE CHAUFFE', 'position' => 3, 'problems' => []],
        ];
        for ($i=0; $i < count($cats); $i++) {
            $cat = new Category(
                CategoryId::make(),
                $cats[$i]['name'],
                $cats[$i]['position']
            );
            $manager->persist($cat);
            $cats[$i]['id'] = $cat->id;
        }

        $manager->flush();

        // Create ProblemTypes and DiagnosticSteps
        $datas = [
            ['id' => null, 'name' => 'LA MACHINE NE DEMARRE PAS', 'categoryId' => $cats[0]['id'], 'position' => 1, 'steps' => [
                ['id' => null, 'stepType' => 'symptom', 'description' => 'Le moteur de la pompe ne tourne pas ET le programmateur est éteint', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier la position du bouton On/Off', 'position' => 2, 'parent' => 1, 'nextOK' => 4, 'nextKO' => 3, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Mettre le bouton sur ON', 'position' => 3, 'parent' => 2, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier l\'arrêt d\'urgence (Tiré)', 'position' => 4, 'parent' => 2, 'nextOK' => 6, 'nextKO' => 5, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Tirer l\'Arrêt d\'urgence', 'position' => 5, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'check', 'description' => ' Vérifier que le câble de la machine est correctement branché', 'position' => 6, 'parent' => 4, 'nextOK' => 8, 'nextKO' => 7, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Brancher le câble correctement', 'position' => 7, 'parent' => 6, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier le disjoncteur de  la "maison"', 'position' => 8, 'parent' => 6, 'nextOK' => 10, 'nextKO' => 9, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Réenclancher le disjoncteur, s\'assurrer qu\'il n\'y a rien d\'autre de brancher et qu\'il s\'agit d\'un 16A, le cas échéant changer de prise', 'position' => 9, 'parent' => 8, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier l\'enrouleur / Tester avec un autre enrouleur', 'position' => 10, 'parent' => 8, 'nextOK' => 12, 'nextKO' => 11, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Changer d\'enrouleur (Min section 2,5)', 'position' => 11, 'parent' => 10, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier le disjoncteur principal de la machine dans coffret électrique', 'position' => 12, 'parent' => 10, 'nextOK' => 14, 'nextKO' => 13, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Réenclancher le disjoncteur, si le problème revient -> appeler le SAV 0681676430', 'position' => 13, 'parent' => 12, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Tester continuité de l\'alimentation électrique au voltmètre', 'position' => 14, 'parent' => 12, 'nextOK' => 16, 'nextKO' => 15, 'needDoc' => 1, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Une fois identifié où cela se passe, appeler le SAV', 'position' => 15, 'parent' => 14, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Appeler le SAV 0681676430', 'position' => 16, 'parent' => 14, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'symptom', 'description' => 'Le moteur de la pompe ne tourne pas ET le programmateur est allumé', 'position' => 17, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ['id' => null, 'stepType' => 'symptom', 'description' => 'Le moteur de la pompe tourne ET le programmateur est éteint', 'position' => 18, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
            ]],
            ['id' => null, 'name' => 'PROGRAMMATEUR EN ERREUR (affiche ERR)', 'categoryId' => $cats[0]['id'], 'position' => 2,
                'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PROBLEME SUR INFOS RECUES', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'ARRETER LA MACHINE ET ATTENDRE QUELQUE SECONDES AVANT DE LA REDEMARRER (POUR REINITIALISER LES CONNEXIONS)', 'position' => 2, 'parent' => 1, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LES BRANCHEMENTS DE LA SONDE SUR LE PROGRAMMATEUR', 'position' => 3, 'parent' => 1, 'nextOK' => 5, 'nextKO' => 4, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REBRANCHER CORRECTEMENT', 'position' => 4, 'parent' => 3, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER SI LE CABLE DE LA SONDE ELECTRONIQUE N\'EST PAS ABIME', 'position' => 5, 'parent' => 3, 'nextOK' => 7, 'nextKO' => 6, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA SONDE ELECTRONIQUE', 'position' => 6, 'parent' => 5, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA SONDE ELECTRONIQUE', 'position' => 7, 'parent' => 5, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                ]],
            ['id' => null, 'name' => 'PROGRAMMATEUR ne s\'allume plus ou clignote', 'categoryId' => $cats[0]['id'], 'position' => 3,
                'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PROGRAMMATEUR NE S\'ALLUME PLUS MAIS LE MOTEUR DE POMPE OU VENTILATEUR TOURNE', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'MANQUE DE DEBIT D\'EAUX AUX ACCESSOIRES', 'position' => 2, 'parent' => 1, 'nextOK' => 3, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'ARRETER LA MACHINE ET ATTENDRE QUELQUE SECONDES AVANT DE LA REDEMARRER (POUR REINITIALISER LES CONNEXIONS)', 'position' => 3, 'parent' => 2, 'nextOK' => 4, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LES BRANCHEMENTS PHASE ET NEUTRE SUR LE PROGRAMMATEUR', 'position' => 4, 'parent' => 3, 'nextOK' => 6, 'nextKO' => 5, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REBRANCHER CORRECTEMENT', 'position' => 5, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'Tester la continuité de l\'alimentation électrique du progr au voltmètre ', 'position' => 6, 'parent' => 4, 'nextOK' => 8, 'nextKO' => 7, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'Une fois identifié où cela se passe, appeler le SAV', 'position' => 7, 'parent' => 6, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LE PROGRAMMATEUR', 'position' => 8, 'parent' => 6, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PROGRAMMATEUR NE S\'ALLUME PLUS MAIS LE MOTEUR DE POMPE ET VENTILATEUR A L\'ARRET', 'position' => 9, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                ]],
            ['id' => null, 'name' => 'PLUS DE CONSOMMATION D\'ANTICALCAIRE', 'categoryId' => $cats[0]['id'], 'position' => 4,
                'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'LA POMPE TOURNE', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE NIVEAU D\'ANTICALCAIRE DANS LE RESERVOIR PLASTIQUE', 'position' => 2, 'parent' => 1, 'nextOK' => 4, 'nextKO' => 3, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'FAIRE LE PLEIN D\'ANTICALCAIRE', 'position' => 3, 'parent' => 2, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE BON ETAT ET BRANCHEMENT DES TUYAUX', 'position' => 4, 'parent' => 2, 'nextOK' => 6, 'nextKO' => 5, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LES ELEMENTS DEFECTUEUX', 'position' => 5, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA POMPE ANTICALCAIRE', 'position' => 6, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'LA POMPE NE TOURNE PAS', 'position' => 7, 'parent' => null, 'nextOK' => 8, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'SI PRESENCE INTERRUPTEUR, VERIFIER SA POSITION', 'position' => 8, 'parent' => 7, 'nextOK' => 10, 'nextKO' => 9, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'METTRE L\'INTERRUPTEUR SUR "ON"', 'position' => 9, 'parent' => 8, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE CABLAGE DANS LE COFFRET ELECTRIQUE', 'position' => 10, 'parent' => 8, 'nextOK' => 12, 'nextKO' => 11, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REBRANCHER CORRECTEMENT', 'position' => 11, 'parent' => 10, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA POMPE ANTICALCAIRE', 'position' => 12, 'parent' => 10, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null],
                ]],

            ['id' => null,
                'name' => 'PAS DE PRESSION AU MANO (LA POMPE NE TOURNE PAS) ALORS QU\'ON APPUIE SUR LA GACHETTE',
                'categoryId' => $cats[1]['id'], 'position' => 1, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'IMPOSSIBLE DE MONTER EN PRESSION SORTIE DE POMPE (AU MANO) et le moteur tourne',
                'categoryId' => $cats[1]['id'], 'position' => 2, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'PRESSION OK AU MANO MAIS PAS OU PEU DE SORTIE D\'EAU AUX ACCESSOIRES',
                'categoryId' => $cats[1]['id'], 'position' => 3, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'PRESSION SACCADEE', 'categoryId' => $cats[1]['id'], 'position' => 4, 'steps' => [
                
            ]],

            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PLUS VOYANT VERT ALLUME', 'categoryId' => $cats[2]['id'], 'position' => 1,
                'steps' => [
                    
                ]],
            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PLUS VOYANT VERT RESTE ETEINT',
                'categoryId' => $cats[2]['id'], 'position' => 2, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PAS ASSEZ', 'categoryId' => $cats[2]['id'], 'position' => 3, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'VOYANT VERT S\'ETEINT JUSQU\'A UNE TEMPERATURE RELATIVEMENT BASSE',
                'categoryId' => $cats[2]['id'], 'position' => 4, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'LA MACHINE MONTE TROP EN TEMPERATURE', 'categoryId' => $cats[2]['id'], 'position' => 5,
                'steps' => [
                    
                ]],
            ['id' => null, 'name' => 'LA MACHINE FUME BEAUCOUP', 'categoryId' => $cats[2]['id'], 'position' => 6, 'steps' => [
                
            ]],
        ];

        for ($i=0; $i < count($datas); $i++) {
            $problemType = new ProblemType(
                ProblemTypeId::make(),
                $datas[$i]['name'],
                $datas[$i]['categoryId'],
                $datas[$i]['position']
            );
            $manager->persist($problemType);
            $datas[$i]['id'] = $problemType->id;

            foreach ($datas[$i]['steps'] as $step) {
                $stepType = match($step['stepType']) {
                    'symptome' => DiagnosticStepType::SYMPTOM,
                    'check' => DiagnosticStepType::CHECK,
                    'action' => DiagnosticStepType::ACTION,
                    default => DiagnosticStepType::SYMPTOM
                };
                
                $diagnosticStep = new DiagnosticStep(
                    DiagnosticStepId::make(),
                    $problemType->id,
                    $stepType,
                    null,
                    null,
                    null,
                    $step['description'],
                    false,
                    $step['position'],
                    null
                );
                $manager->persist($diagnosticStep);
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['decision-tree'];
    }
}
