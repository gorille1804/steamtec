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
                ['stepType' => 'symptome', 'description' => 'Le moteur de la pompe ne tourne pas ET le programmateur est éteint'],
                ['stepType' => 'check', 'description' => 'Vérifier la position du bouton On/Off'],
                ['stepType' => 'action', 'description' => 'Mettre le bouton sur ON'],
                ['stepType' => 'check', 'description' => 'Vérifier l\'arrêt d\'urgence (Tiré)'],
                ['stepType' => 'action', 'description' => 'Tirer l\'Arrêt d\'urgence'],
                ['stepType' => 'check', 'description' => 'Vérifier que le câble de la machine est correctement branché'],
                ['stepType' => 'action', 'description' => 'Brancher le câble correctement'],
                ['stepType' => 'check', 'description' => 'Vérifier le disjoncteur de la "maison"'],
                ['stepType' => 'action', 'description' => 'Réenclancher le disjoncteur, s\'assurrer qu\'il n\'y a rien d\'autre de brancher et qu\'il s\'agit d\'un 16A, le cas échéant changer de prise'],
                ['stepType' => 'symptome', 'description' => 'Le moteur de la pompe ne tourne pas ET le programmateur est allumé'],
                ['stepType' => 'symptome', 'description' => 'Le moteur de la pompe tourne ET le programmateur est éteint'],
                ['stepType' => 'check', 'description' => 'Vérifier l\'enrouleur / Tester avec un autre enrouleur'],
                ['stepType' => 'action', 'description' => 'Changer d\'enrouleur (Min section 2,5)'],
                ['stepType' => 'check', 'description' => 'Vérifier le disjoncteur principal de la machine dans coffret électrique'],
                ['stepType' => 'action', 'description' => 'Réenclancher le disjoncteur, si le problème revient -> appeler le SAV 0681676430'],
                ['stepType' => 'check', 'description' => 'Tester continuité de l\'alimentation électrique au voltmètre'],
                ['stepType' => 'action', 'description' => 'Une fois identifié où cela se passe, appeler le SAV'],
                ['stepType' => 'action', 'description' => 'Appeler le SAV 0681676430'],
            ]],
            ['id' => null, 'name' => 'PROGRAMMATEUR EN ERREUR (affiche ERR)', 'categoryId' => $cats[0]['id'], 'position' => 2,
                'steps' => [
                    ['stepType' => 'symptome', 'description' => 'Identification du problème : PROGRAMMATEUR EN ERREUR (affiche ERR)'],
                    ['stepType' => 'check', 'description' => 'Vérifier l\'affichage du code d\'erreur'],
                    ['stepType' => 'check', 'description' => 'Vérifier les connexions du programmateur'],
                    ['stepType' => 'check', 'description' => 'Vérifier la tension d\'alimentation'],
                    ['stepType' => 'check', 'description' => 'Vérifier les capteurs connectés'],
                    ['stepType' => 'action', 'description' => 'Réinitialiser le programmateur'],
                    ['stepType' => 'action', 'description' => 'Remplacer le programmateur si nécessaire'],
                    ['stepType' => 'action', 'description' => 'Rétablir les connexions'],
                    ['stepType' => 'action', 'description' => 'Mettre à jour le firmware'],
                ]],
            ['id' => null, 'name' => 'PROGRAMMATEUR ne s\'allume plus ou clignote', 'categoryId' => $cats[0]['id'], 'position' => 3,
                'steps' => [
                    ['stepType' => 'symptome', 'description' => 'Identification du problème : PROGRAMMATEUR ne s\'allume plus ou clignote'],
                    ['stepType' => 'check', 'description' => 'Vérifier l\'alimentation électrique'],
                    ['stepType' => 'check', 'description' => 'Vérifier le bouton d\'alimentation'],
                    ['stepType' => 'check', 'description' => 'Vérifier les connexions du programmateur'],
                    ['stepType' => 'check', 'description' => 'Vérifier l\'état du fusible'],
                    ['stepType' => 'action', 'description' => 'Remplacer le programmateur'],
                    ['stepType' => 'action', 'description' => 'Réparer les connexions'],
                    ['stepType' => 'action', 'description' => 'Remplacer le bouton d\'alimentation'],
                    ['stepType' => 'action', 'description' => 'Nettoyer les contacts'],
                ]],
            ['id' => null, 'name' => 'PLUS DE CONSOMMATION D\'ANTICALCAIRE', 'categoryId' => $cats[0]['id'], 'position' => 4,
                'steps' => [
                    ['stepType' => 'symptome', 'description' => 'Identification du problème : PLUS DE CONSOMMATION D\'ANTICALCAIRE'],
                    ['stepType' => 'check', 'description' => 'Vérifier le niveau d\'anticalcaire'],
                    ['stepType' => 'check', 'description' => 'Vérifier le système de dosage'],
                    ['stepType' => 'check', 'description' => 'Vérifier les connexions du système'],
                    ['stepType' => 'check', 'description' => 'Vérifier l\'état du réservoir'],
                    ['stepType' => 'action', 'description' => 'Recharger l\'anticalcaire'],
                    ['stepType' => 'action', 'description' => 'Remplacer le système de dosage'],
                    ['stepType' => 'action', 'description' => 'Nettoyer le réservoir'],
                    ['stepType' => 'action', 'description' => 'Vérifier les paramètres de dosage'],
                ]],

            ['id' => null,
                'name' => 'PAS DE PRESSION AU MANO (LA POMPE NE TOURNE PAS) ALORS QU\'ON APPUIE SUR LA GACHETTE',
                'categoryId' => $cats[1]['id'], 'position' => 1, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : PAS DE PRESSION AU MANO (LA POMPE NE TOURNE PAS) ALORS QU\'ON APPUIE SUR LA GACHETTE'],
                ['stepType' => 'check', 'description' => 'Vérifier le manomètre'],
                ['stepType' => 'check', 'description' => 'Vérifier la pompe'],
                ['stepType' => 'check', 'description' => 'Vérifier les connexions électriques'],
                ['stepType' => 'check', 'description' => 'Vérifier l\'état du moteur'],
                ['stepType' => 'action', 'description' => 'Remplacer la pompe'],
                ['stepType' => 'action', 'description' => 'Réparer les connexions électriques'],
                ['stepType' => 'action', 'description' => 'Remplacer le manomètre'],
                ['stepType' => 'action', 'description' => 'Nettoyer les vannes'],
            ]],
            ['id' => null, 'name' => 'IMPOSSIBLE DE MONTER EN PRESSION SORTIE DE POMPE (AU MANO) et le moteur tourne',
                'categoryId' => $cats[1]['id'], 'position' => 2, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : IMPOSSIBLE DE MONTER EN PRESSION SORTIE DE POMPE (AU MANO) et le moteur tourne'],
                ['stepType' => 'check', 'description' => 'Vérifier le manomètre'],
                ['stepType' => 'check', 'description' => 'Vérifier les joints d\'étanchéité'],
                ['stepType' => 'check', 'description' => 'Vérifier l\'état de la pompe'],
                ['stepType' => 'check', 'description' => 'Vérifier les vannes'],
                ['stepType' => 'action', 'description' => 'Remplacer les joints d\'étanchéité'],
                ['stepType' => 'action', 'description' => 'Remplacer la pompe'],
                ['stepType' => 'action', 'description' => 'Nettoyer les vannes'],
                ['stepType' => 'action', 'description' => 'Ajuster la pression'],
            ]],
            ['id' => null, 'name' => 'PRESSION OK AU MANO MAIS PAS OU PEU DE SORTIE D\'EAU AUX ACCESSOIRES',
                'categoryId' => $cats[1]['id'], 'position' => 3, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : PRESSION OK AU MANO MAIS PAS OU PEU DE SORTIE D\'EAU AUX ACCESSOIRES'],
                ['stepType' => 'check', 'description' => 'Vérifier les accessoires'],
                ['stepType' => 'check', 'description' => 'Vérifier les tuyaux'],
                ['stepType' => 'check', 'description' => 'Vérifier les filtres'],
                ['stepType' => 'check', 'description' => 'Vérifier les vannes'],
                ['stepType' => 'action', 'description' => 'Nettoyer les accessoires'],
                ['stepType' => 'action', 'description' => 'Remplacer les tuyaux'],
                ['stepType' => 'action', 'description' => 'Nettoyer les filtres'],
                ['stepType' => 'action', 'description' => 'Ajuster les vannes'],
            ]],
            ['id' => null, 'name' => 'PRESSION SACCADEE', 'categoryId' => $cats[1]['id'], 'position' => 4, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : PRESSION SACCADEE'],
                ['stepType' => 'check', 'description' => 'Vérifier le manomètre'],
                ['stepType' => 'check', 'description' => 'Vérifier la pompe'],
                ['stepType' => 'check', 'description' => 'Vérifier les vannes'],
                ['stepType' => 'check', 'description' => 'Vérifier les joints'],
                ['stepType' => 'action', 'description' => 'Remplacer le manomètre'],
                ['stepType' => 'action', 'description' => 'Remplacer la pompe'],
                ['stepType' => 'action', 'description' => 'Nettoyer les vannes'],
                ['stepType' => 'action', 'description' => 'Ajuster la pression'],
            ]],

            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PLUS VOYANT VERT ALLUME', 'categoryId' => $cats[2]['id'], 'position' => 1,
                'steps' => [
                    ['stepType' => 'symptome', 'description' => 'Identification du problème : LA MACHINE NE CHAUFFE PLUS VOYANT VERT ALLUME'],
                    ['stepType' => 'check', 'description' => 'Vérifier la résistance'],
                    ['stepType' => 'check', 'description' => 'Vérifier le thermostat'],
                    ['stepType' => 'check', 'description' => 'Vérifier les connexions électriques'],
                    ['stepType' => 'check', 'description' => 'Vérifier l\'état du voyant'],
                    ['stepType' => 'action', 'description' => 'Remplacer la résistance'],
                    ['stepType' => 'action', 'description' => 'Remplacer le thermostat'],
                    ['stepType' => 'action', 'description' => 'Réparer les connexions'],
                    ['stepType' => 'action', 'description' => 'Nettoyer les contacts'],
                ]],
            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PLUS VOYANT VERT RESTE ETEINT',
                'categoryId' => $cats[2]['id'], 'position' => 2, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : LA MACHINE NE CHAUFFE PLUS VOYANT VERT RESTE ETEINT'],
                ['stepType' => 'check', 'description' => 'Vérifier le voyant'],
                ['stepType' => 'check', 'description' => 'Vérifier le thermostat'],
                ['stepType' => 'check', 'description' => 'Vérifier les connexions'],
                ['stepType' => 'check', 'description' => 'Vérifier la résistance'],
                ['stepType' => 'action', 'description' => 'Remplacer le thermostat'],
                ['stepType' => 'action', 'description' => 'Remplacer la résistance'],
                ['stepType' => 'action', 'description' => 'Réparer les connexions'],
                ['stepType' => 'action', 'description' => 'Nettoyer les contacts'],
            ]],
            ['id' => null, 'name' => 'LA MACHINE NE CHAUFFE PAS ASSEZ', 'categoryId' => $cats[2]['id'], 'position' => 3, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : LA MACHINE NE CHAUFFE PAS ASSEZ'],
                ['stepType' => 'check', 'description' => 'Vérifier la température'],
                ['stepType' => 'check', 'description' => 'Vérifier le thermostat'],
                ['stepType' => 'check', 'description' => 'Vérifier la résistance'],
                ['stepType' => 'check', 'description' => 'Vérifier les paramètres'],
                ['stepType' => 'action', 'description' => 'Ajuster le thermostat'],
                ['stepType' => 'action', 'description' => 'Remplacer la résistance'],
                ['stepType' => 'action', 'description' => 'Nettoyer les composants'],
                ['stepType' => 'action', 'description' => 'Mettre à jour les paramètres'],
            ]],
            ['id' => null, 'name' => 'VOYANT VERT S\'ETEINT JUSQU\'A UNE TEMPERATURE RELATIVEMENT BASSE',
                'categoryId' => $cats[2]['id'], 'position' => 4, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : VOYANT VERT S\'ETEINT JUSQU\'A UNE TEMPERATURE RELATIVEMENT BASSE'],
                ['stepType' => 'check', 'description' => 'Vérifier le thermostat'],
                ['stepType' => 'check', 'description' => 'Vérifier la température'],
                ['stepType' => 'check', 'description' => 'Vérifier le voyant'],
                ['stepType' => 'check', 'description' => 'Vérifier les paramètres'],
                ['stepType' => 'action', 'description' => 'Remplacer le thermostat'],
                ['stepType' => 'action', 'description' => 'Ajuster les paramètres'],
                ['stepType' => 'action', 'description' => 'Nettoyer les composants'],
                ['stepType' => 'action', 'description' => 'Vérifier la calibration'],
            ]],
            ['id' => null, 'name' => 'LA MACHINE MONTE TROP EN TEMPERATURE', 'categoryId' => $cats[2]['id'], 'position' => 5,
                'steps' => [
                    ['stepType' => 'symptome', 'description' => 'Identification du problème : LA MACHINE MONTE TROP EN TEMPERATURE'],
                    ['stepType' => 'check', 'description' => 'Vérifier le thermostat'],
                    ['stepType' => 'check', 'description' => 'Vérifier la température'],
                    ['stepType' => 'check', 'description' => 'Vérifier la résistance'],
                    ['stepType' => 'check', 'description' => 'Vérifier les paramètres'],
                    ['stepType' => 'action', 'description' => 'Remplacer le thermostat'],
                    ['stepType' => 'action', 'description' => 'Ajuster les paramètres'],
                    ['stepType' => 'action', 'description' => 'Nettoyer les composants'],
                    ['stepType' => 'action', 'description' => 'Vérifier la ventilation'],
                ]],
            ['id' => null, 'name' => 'LA MACHINE FUME BEAUCOUP', 'categoryId' => $cats[2]['id'], 'position' => 6, 'steps' => [
                ['stepType' => 'symptome', 'description' => 'Identification du problème : LA MACHINE FUME BEAUCOUP'],
                ['stepType' => 'check', 'description' => 'Vérifier la température'],
                ['stepType' => 'check', 'description' => 'Vérifier les joints'],
                ['stepType' => 'check', 'description' => 'Vérifier la résistance'],
                ['stepType' => 'check', 'description' => 'Vérifier l\'état général'],
                ['stepType' => 'action', 'description' => 'Remplacer les joints'],
                ['stepType' => 'action', 'description' => 'Nettoyer les composants'],
                ['stepType' => 'action', 'description' => 'Ajuster la température'],
                ['stepType' => 'action', 'description' => 'Vérifier la ventilation'],
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

            $counter = 0;
            foreach ($datas[$i]['steps'] as $step) {
                $counter++;
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
                    $counter,
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
