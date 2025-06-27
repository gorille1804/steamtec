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
                ['id' => null, 'stepType' => 'symptom', 'description' => 'Le moteur de la pompe ne tourne pas ET le programmateur est éteint', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 2],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier la position du bouton On/Off', 'position' => 2, 'parent' => 1, 'nextOK' => 4, 'nextKO' => 3, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Mettre le bouton sur ON', 'position' => 3, 'parent' => 2, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier l\'arrêt d\'urgence (Tiré)', 'position' => 4, 'parent' => 2, 'nextOK' => 6, 'nextKO' => 5, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Tirer l\'Arrêt d\'urgence', 'position' => 5, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier que le câble de la machine est correctement branché', 'position' => 6, 'parent' => 4, 'nextOK' => 8, 'nextKO' => 7, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Brancher le câble correctement', 'position' => 7, 'parent' => 6, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier le disjoncteur de la "maison"', 'position' => 8, 'parent' => 6, 'nextOK' => 10, 'nextKO' => 9, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Réenclancher le disjoncteur, s\'assurrer qu\'il n\'y a rien d\'autre de branché et qu\'il s\'agit d\'un 16A, le cas échéant changer de prise', 'position' => 9, 'parent' => 8, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier l\'enrouleur / Tester avec un autre enrouleur', 'position' => 10, 'parent' => 8, 'nextOK' => 12, 'nextKO' => 11, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Changer d\'enrouleur (Min section 2,5)', 'position' => 11, 'parent' => 10, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'check', 'description' => 'Vérifier le disjoncteur principal de la machine dans coffret électrique', 'position' => 12, 'parent' => 10, 'nextOK' => 15, 'nextKO' => 13, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Réenclancher le disjoncteur, si le problème revient -> appeler le SAV 0681676430', 'position' => 13, 'parent' => 12, 'nextOK' => 14, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Tester si dijonctions avec les 2 disjoncteurs baissés séparémment pour cibler le circuit à problème', 'position' => 14, 'parent' => 12, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'action', 'description' => 'Appeler le SAV 0681676430', 'position' => 15, 'parent' => 12, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ['id' => null, 'stepType' => 'symptom', 'description' => 'Le moteur de la pompe ne tourne pas ET le programmateur est allumé', 'position' => 16, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 7],
                ['id' => null, 'stepType' => 'symptom', 'description' => 'Le moteur de la pompe tourne ET le programmateur est éteint', 'position' => 17, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 4],
            ]],
            ['id' => null, 'name' => 'PROGRAMMATEUR EN ERREUR (affiche ERR ou symboles)', 'categoryId' => $cats[0]['id'], 'position' => 2,
                'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PROBLEME SUR INFOS RECUES', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 3],
                    ['id' => null, 'stepType' => 'action', 'description' => 'ARRETER LA MACHINE ET ATTENDRE QUELQUE SECONDES AVANT DE LA REDEMARRER (POUR REINITIALISER LES CONNEXIONS)', 'position' => 2, 'parent' => 1, 'nextOK' => 3, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER SI LE CABLE DE LA SONDE ELECTRONIQUE N\'EST PAS ABIME', 'position' => 3, 'parent' => 2, 'nextOK' => 5, 'nextKO' => 4, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA SONDE ELECTRONIQUE', 'position' => 4, 'parent' => 3, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LES BRANCHEMENTS DE LA SONDE SUR LE PROGRAMMATEUR', 'position' => 5, 'parent' => 3, 'nextOK' => 7, 'nextKO' => 6, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REBRANCHER CORRECTEMENT', 'position' => 6, 'parent' => 5, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA SONDE ELECTRONIQUE', 'position' => 7, 'parent' => 5, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                ]],
            ['id' => null, 'name' => 'PROGRAMMATEUR ne s\'allume plus ou clignote', 'categoryId' => $cats[0]['id'], 'position' => 3,
                'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PROGRAMMATEUR NE S\'ALLUME PLUS MAIS LE MOTEUR DE POMPE OU VENTILATEUR TOURNE', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 4],
                    ['id' => null, 'stepType' => 'action', 'description' => 'ARRETER LA MACHINE ET ATTENDRE QUELQUE SECONDES AVANT DE LA REDEMARRER (POUR REINITIALISER LES CONNEXIONS)', 'position' => 2, 'parent' => 1, 'nextOK' => 3, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LES BRANCHEMENTS PHASE ET NEUTRE SUR LE PROGRAMMATEUR', 'position' => 3, 'parent' => 2, 'nextOK' => 5, 'nextKO' => 4, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REBRANCHER CORRECTEMENT', 'position' => 4, 'parent' => 3, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'Tester la continuité de l\'alimentation électrique du progr au voltmètre', 'position' => 5, 'parent' => 3, 'nextOK' => 7, 'nextKO' => 6, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'Une fois identifié où cela se passe, appeler le SAV', 'position' => 6, 'parent' => 5, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LE PROGRAMMATEUR', 'position' => 7, 'parent' => 5, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PROGRAMMATEUR NE S\'ALLUME PLUS MAIS LE MOTEUR DE POMPE OU VENTILATEUR A l\'ARRET', 'position' => 8, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ]],
            ['id' => null, 'name' => 'PLUS DE CONSOMMATION D\'ANTICALCAIRE', 'categoryId' => $cats[0]['id'], 'position' => 4,
                'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'LA POMPE TOURNE', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 5],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE NIVEAU D\'ANTICALCAIRE DANS LE RESERVOIR PLASTIQUE', 'position' => 2, 'parent' => 1, 'nextOK' => 4, 'nextKO' => 3, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'FAIRE LE PLEIN D\'ANTICALCAIRE', 'position' => 3, 'parent' => 2, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER QUE LES TUYAUX NE SONT PAS BOUCHES', 'position' => 4, 'parent' => 2, 'nextOK' => 6, 'nextKO' => 5, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LES ELEMENTS DEFECTUEUX', 'position' => 5, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA POMPE ANTICALCAIRE', 'position' => 6, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'LA POMPE NE TOURNE PAS', 'position' => 7, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 5],
                    ['id' => null, 'stepType' => 'check', 'description' => 'SI PRESENCE INTERRUPTEUR, VERIFIER SA POSITION', 'position' => 8, 'parent' => 7, 'nextOK' => 10, 'nextKO' => 9, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'METTRE L\'INTERRUPTEUR SUR "ON"', 'position' => 9, 'parent' => 8, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE CABLAGE DANS LE COFFRET ELECTRIQUE', 'position' => 10, 'parent' => 8, 'nextOK' => 12, 'nextKO' => 11, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REBRANCHER CORRECTEMENT', 'position' => 11, 'parent' => 10, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LA POMPE ANTICALCAIRE', 'position' => 12, 'parent' => 10, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                ]],
                ['id' => null, 'name' => 'DEBORDEMENT DU BAC ANTICALAIRE', 'categoryId' => $cats[0]['id'], 'position' => 5,
                'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'DEBORDEMENT DU BAC ANTICALAIRE', 'position' => 1, 'parent' => null, 'nextOK' => 2, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 6],
                    ['id' => null, 'stepType' => 'check', 'description' => 'LA PRESSION DU RESEAU D\'EAU EST PEUT-ETRE TROP FORTE, FERMER LEGEREMENT LE ROBINET D\'ALIMENTATION', 'position' => 2, 'parent' => 1, 'nextOK' => 4, 'nextKO' => 3, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REGLER LE ROBINET D\'ALIMENTATION POUR ETRE SUFFISAMMENT ALIMENTE SANS DEBORDEMENT', 'position' => 3, 'parent' => 2, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER l\'ETAT MECANIQUE DU FLOTTEUR', 'position' => 4, 'parent' => 2, 'nextOK' => null, 'nextKO' => 5, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LES ELEMENTS DEFECTUEUX', 'position' => 5, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                ]],

            ['id' => null,
                'name' => 'PAS DE PRESSION AU MANO (LA POMPE NE TOURNE PAS) ALORS QU\'ON APPUIE SUR LA GACHETTE',
                'categoryId' => $cats[1]['id'], 'position' => 1, 'steps' => [
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'LE PROGRAMMATEUR EST ETTEINT', 'position' => 1, 'parent' => null, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 7],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'LE PROGRAMMATEUR EST ALLUME', 'position' => 2, 'parent' => null, 'nextOK' => 3, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => 7],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER QUE L\'INTERRUPTEUR DE LA POMPE EST SUR "I"', 'position' => 3, 'parent' => 2, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'REENCLANCHER L\'INTERRUPTEUR DE LA POMPE SUR "I"', 'position' => 4, 'parent' => 3, 'nextOK' => null, 'nextKO' => 5, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'En cas de difficulté pour l\'enclencher, ou de problème répétitif -> Problème d\'arrêt de pompe', 'position' => 5, 'parent' => 4, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'AUCUN DEBIT DETECTE', 'position' => 6, 'parent' => 3, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'QUELLE EST LA CONFIGURATION DE L\'ALIMENTATION EN EAU ? (SUR CITERNE ?)', 'position' => 7, 'parent' => 6, 'nextOK' => 23, 'nextKO' => 8, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'EST-CE EN DIRECT SANS BAC ANTICALCAIRE ?', 'position' => 8, 'parent' => 7, 'nextOK' => 9, 'nextKO' => 30, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'EN DIRECT SANS BAC ANTI CALCAIRE', 'position' => 9, 'parent' => 8, 'nextOK' => 10, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'OUVERTURE DU ROBINET D\'ALIMENTATION', 'position' => 10, 'parent' => 9, 'nextOK' => 11, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ABSENCE DE PLI DANS LE TUYAU D\'ALIMENTATION', 'position' => 11, 'parent' => 10, 'nextOK' => 12, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ETAT DU FILTRE BLANC ENTREE MACHINE (SI BESOIN LE CHANGER OU NETTOYER)', 'position' => 12, 'parent' => 11, 'nextOK' => 13, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE FILTRE ENTREE POMPE (COUVERCLE VERT) (SI BESOIN LE NETTOYER)', 'position' => 13, 'parent' => 12, 'nextOK' => 14, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'OUVERTURE DU ROBINET VAPEUR', 'position' => 14, 'parent' => 13, 'nextOK' => 15, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LA SORTIE D\'EAU (MÊME FAIBLE) EN SORTIE D\'ACCESSOIRE (SI ABSENCE D\'EAU), NETTOYER LA BUSE DE L\'ACCESSOIRE OU CHANGER D\'ACCESSOIRE°', 'position' => 15, 'parent' => 14, 'nextOK' => 16, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PROBLEME ELECTRIQUE', 'position' => 16, 'parent' => 15, 'nextOK' => 17, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ETAT DU/DES CONDENSATEURS (SI BESOIN CHANGER LE CONDENSATEUR)', 'position' => 17, 'parent' => 16, 'nextOK' => 18, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER POSITION DU CONTACTEUR , DU DISJONTEUR, ET VERIFIER TEMOIN ROUGE DU CONTACTEUR DE LA POMPE DANS LE COFFRET ELECTRIQUE ', 'position' => 18, 'parent' => 17, 'nextOK' => 19, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ETAT DU CABLAGE DANS BOITIER ELEC DE LA POMPE ET ENCLENCHEMENT DU CONTACTEUR et contacter le SAV', 'position' => 19, 'parent' => 18, 'nextOK' => 20, 'nextKO' => null, 'needDoc' => 1, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'Appeler le SAV 0681676430', 'position' => 20, 'parent' => 19, 'nextOK' => 21, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'CHANGER LE PRESSOSTAT A COTE DE LA VANNE DE REGLAGE', 'position' => 21, 'parent' => 20, 'nextOK' => 22, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'VERIFIER POSITION CLAPET ANTIRETOUR', 'position' => 22, 'parent' => 21, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'SUR CITERNE (PAS A PLUS DE 3M DE LA MACHINE ET DE NIVEAU)', 'position' => 23, 'parent' => 7, 'nextOK' => 24, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE FONCTIONNEMENT EN DIRECT (SUR ROBINET)', 'position' => 24, 'parent' => 23, 'nextOK' => 25, 'nextKO' => 12, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE NIVEAU D\'EAU DANS LA CITERNE', 'position' => 25, 'parent' => 24, 'nextOK' => 26, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER QUE LA SORTIE N\'EST PAS BOUCHEE (EN DEBRANCHANT)', 'position' => 26, 'parent' => 25, 'nextOK' => 27, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ETAT DU FILTRE BLANC ENTRE LA CITERNE ET LA MACHINE (SI BESOIN LE CHANGER OU NETTOYER)', 'position' => 27, 'parent' => 26, 'nextOK' => 28, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ABSENCE DE POINT BAS ENTRE LA CITERNE ET LA MACHINE', 'position' => 28, 'parent' => 27, 'nextOK' => 29, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'Appeler le SAV 0681676430', 'position' => 29, 'parent' => 28, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'EN DIRECT AVEC BAC ANTI CALCAIRE', 'position' => 30, 'parent' => 8, 'nextOK' => 31, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER l\'ECOULEMENT D\'EAU EN SORTIE DE BAC', 'position' => 31, 'parent' => 30, 'nextOK' => 32, 'nextKO' => 33, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'L\'EAU EN SORTIE DE BAC S\'ECOULE NORMALEMENT', 'position' => 32, 'parent' => 31, 'nextOK' => 13, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'ABSENCE D\'EAU EN SORTIE DE BAC', 'position' => 33, 'parent' => 31, 'nextOK' => 34, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER la presence d\'EAU en entrée de BAC', 'position' => 34, 'parent' => 33, 'nextOK' => 39, 'nextKO' => 35, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'ABSENCE D\'EAU EN ENTREE DE BAC', 'position' => 35, 'parent' => 34, 'nextOK' => 36, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'OUVERTURE DU ROBINET', 'position' => 36, 'parent' => 35, 'nextOK' => 37, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ABSENCE DE PLI DANS LE TUYAU D\'ALIMENTATION', 'position' => 37, 'parent' => 36, 'nextOK' => 38, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER L\'ETAT DU FILTRE BLANC ENTREE MACHINE (SI BESOIN LE CHANGER OU NETTOYER)', 'position' => 38, 'parent' => 37, 'nextOK' => 43, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'symptom', 'description' => 'PRESENCE D\'EAU EN ENTREE DE BAC', 'position' => 39, 'parent' => 34, 'nextOK' => 40, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER LE BON FONCTIONNEMENT DU FLOTTEUR', 'position' => 40, 'parent' => 39, 'nextOK' => 41, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER QUE L\'ENTREE N\'EST PAS BOUCHEE', 'position' => 41, 'parent' => 40, 'nextOK' => 42, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'check', 'description' => 'VERIFIER QUE LA SORTIE N\'EST PAS BOUCHEE', 'position' => 42, 'parent' => 41, 'nextOK' => 44, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'Appeler le SAV 0681676430', 'position' => 43, 'parent' => 38, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],
                    ['id' => null, 'stepType' => 'action', 'description' => 'Appeler le SAV 0681676430', 'position' => 44, 'parent' => 42, 'nextOK' => null, 'nextKO' => null, 'needDoc' => 0, 'goto' => null, 'page' => null],                    
            ]],
            ['id' => null, 'name' => 'IMPOSSIBLE DE MONTER EN PRESSION SORTIE DE POMPE (AU MANO) et le moteur tourne',
                'categoryId' => $cats[1]['id'], 'position' => 2, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'PRESSION OK AU MANO MAIS PAS OU PEU DE SORTIE D\'EAU AUX ACCESSOIRES',
                'categoryId' => $cats[1]['id'], 'position' => 3, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'PRESSION SACCADEE', 'categoryId' => $cats[1]['id'], 'position' => 4, 'steps' => [
                
            ]],
            ['id' => null, 'name' => 'LA POMPE NE S\'ARRETE PAS (1 MINUTE MAX APRES ARRÊT DE LA GACHETTE)', 'categoryId' => $cats[1]['id'], 'position' => 5, 'steps' => [
                
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

        // Première étape : créer tous les ProblemTypes
        for ($i=0; $i < count($datas); $i++) {
            $problemType = new ProblemType(
                ProblemTypeId::make(),
                $datas[$i]['name'],
                $datas[$i]['categoryId'],
                $datas[$i]['position']
            );
            $manager->persist($problemType);
            $datas[$i]['id'] = $problemType->id;
        }
        
        $manager->flush();

        // Deuxième étape : créer toutes les étapes de diagnostic sans les relations
        $stepMap = []; // Pour stocker les IDs des étapes par problème et position
        
        for ($i=0; $i < count($datas); $i++) {
            $problemId = $datas[$i]['id'];
            $stepMap[(string)$problemId] = [];
            
            foreach ($datas[$i]['steps'] as $step) {
                $stepType = match($step['stepType']) {
                    'symptom' => DiagnosticStepType::SYMPTOM,
                    'check' => DiagnosticStepType::CHECK,
                    'action' => DiagnosticStepType::ACTION,
                    default => DiagnosticStepType::SYMPTOM
                };
                
                $diagnosticStep = new DiagnosticStep(
                    DiagnosticStepId::make(),
                    $problemId,
                    $stepType,
                    null, // parentStepId sera mis à jour plus tard
                    null, // nextStepOKId sera mis à jour plus tard
                    null, // nextStepKOId sera mis à jour plus tard
                    $step['description'],
                    $step['needDoc'] == 1,
                    $step['position'],
                    $step['goto'],
                    $step['page']
                );
                
                $manager->persist($diagnosticStep);
                $stepMap[(string)$problemId][$step['position']] = $diagnosticStep->id;
            }
        }
        
        $manager->flush();
        
        // Troisième étape : mettre à jour les relations entre les étapes
        for ($i=0; $i < count($datas); $i++) {
            $problemId = $datas[$i]['id'];
            
            foreach ($datas[$i]['steps'] as $step) {
                $currentStepId = $stepMap[(string)$problemId][$step['position']];
                $currentStep = $manager->find(DiagnosticStep::class, $currentStepId);
                
                // Mettre à jour le parent
                if ($step['parent'] !== null) {
                    $parentStepId = $stepMap[(string)$problemId][$step['parent']];
                    $currentStep->parentStepId = $parentStepId;
                }
                
                // Mettre à jour nextOK
                if ($step['nextOK'] !== null) {
                    $nextOKStepId = $stepMap[(string)$problemId][$step['nextOK']];
                    $currentStep->nextStepOKId = $nextOKStepId;
                }
                
                // Mettre à jour nextKO
                if ($step['nextKO'] !== null) {
                    $nextKOStepId = $stepMap[(string)$problemId][$step['nextKO']];
                    $currentStep->nextStepKOId = $nextKOStepId;
                }
            }
        }
        
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['decision-tree'];
    }
}
