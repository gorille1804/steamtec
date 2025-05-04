<?php

namespace Domain\DecisionTree\Service;

use Domain\DecisionTree\Data\Model\DiagnosticStep;

class DecisionTreeBuilder
{
    private const NODE_WIDTH = 280;
    private const NODE_HEIGHT = 160;
    private const HORIZONTAL_SPACING = 450;
    private const VERTICAL_SPACING = 300;

    /**
     * @param DiagnosticStep[] $steps
     * @return array
     */
    public function buildTreeData(array $steps): array
    {
        if (empty($steps)) {
            return ['nodes' => [], 'links' => []];
        }

        // Trier les étapes par ordre
        usort($steps, fn($a, $b) => $a->stepOrder <=> $b->stepOrder);

        // Organiser les étapes en arbre
        $tree = $this->organizeSteps($steps);
        
        if (empty($tree)) {
            return ['nodes' => [], 'links' => []];
        }

        // Calculer les positions des nœuds
        $positions = $this->calculatePositions($tree);

        // Construire les données pour JointJS
        return $this->buildJointJSData($tree, $positions);
    }

    /**
     * @param DiagnosticStep[] $steps
     * @return array
     */
    private function organizeSteps(array $steps): array
    {
        $stepsById = [];
        $rootSteps = [];
        $processedSteps = [];

        // Créer un index des étapes par ID
        foreach ($steps as $step) {
            $stepsById[$step->id->getValue()] = $step;
        }

        // Identifier les étapes racines (celles qui n'ont pas de parent)
        foreach ($steps as $step) {
            if ($step->parentStepId === null) {
                $rootSteps[] = $step;
            }
        }

        // Si aucune étape racine n'est trouvée, utiliser la première étape
        if (empty($rootSteps) && !empty($steps)) {
            $rootSteps[] = $steps[0];
        }

        // Construire l'arbre à partir des étapes racines
        $tree = [];
        foreach ($rootSteps as $rootStep) {
            $tree[] = $this->buildNode($rootStep, $stepsById, $processedSteps);
        }

        return $tree;
    }

    /**
     * @param DiagnosticStep $step
     * @param array $stepsById
     * @param array $processedSteps
     * @return array
     */
    private function buildNode(DiagnosticStep $step, array $stepsById, array &$processedSteps): array
    {
        $stepId = $step->id->getValue();
        
        // Éviter les boucles infinies
        if (isset($processedSteps[$stepId])) {
            return [
                'step' => $step,
                'children' => []
            ];
        }
        
        $processedSteps[$stepId] = true;
        
        $node = [
            'step' => $step,
            'children' => []
        ];

        // Ajouter les enfants OK
        if ($step->nextStepOKId !== null && isset($stepsById[$step->nextStepOKId->getValue()])) {
            $nextStep = $stepsById[$step->nextStepOKId->getValue()];
            $node['children'][] = [
                'step' => $nextStep,
                'label' => 'OK',
                'children' => []
            ];
            
            if (!isset($processedSteps[$nextStep->id->getValue()])) {
                $childNode = $this->buildNode($nextStep, $stepsById, $processedSteps);
                $node['children'][count($node['children']) - 1]['children'] = $childNode['children'];
            }
        }

        // Ajouter les enfants KO
        if ($step->nextStepKOId !== null && isset($stepsById[$step->nextStepKOId->getValue()])) {
            $nextStep = $stepsById[$step->nextStepKOId->getValue()];
            $node['children'][] = [
                'step' => $nextStep,
                'label' => 'KO',
                'children' => []
            ];
            
            if (!isset($processedSteps[$nextStep->id->getValue()])) {
                $childNode = $this->buildNode($nextStep, $stepsById, $processedSteps);
                $node['children'][count($node['children']) - 1]['children'] = $childNode['children'];
            }
        }

        return $node;
    }

    /**
     * @param array $tree
     * @return array
     */
    private function calculatePositions(array $tree): array
    {
        $positions = [];
        foreach ($tree as $index => $node) {
            $this->calculateNodePositions($node, $index * self::HORIZONTAL_SPACING, 0, $positions);
        }
        return $positions;
    }

    /**
     * @param array $node
     * @param int $x
     * @param int $level
     * @param array $positions
     */
    private function calculateNodePositions(array $node, int $x, int $level, array &$positions): void
    {
        $stepId = $node['step']->id->getValue();
        
        // Ne pas recalculer la position si déjà fait
        if (isset($positions[$stepId])) {
            return;
        }
        
        $positions[$stepId] = [
            'x' => $x,
            'y' => $level * self::VERTICAL_SPACING
        ];

        $childCount = count($node['children']);
        if ($childCount > 0) {
            $childSpacing = (int)(self::HORIZONTAL_SPACING / ($childCount + 1));
            foreach ($node['children'] as $index => $child) {
                $childX = (int)($x - (self::HORIZONTAL_SPACING / 2) + ($index + 1) * $childSpacing);
                $this->calculateNodePositions($child, $childX, $level + 1, $positions);
            }
        }
    }

    /**
     * @param array $tree
     * @param array $positions
     * @return array
     */
    private function buildJointJSData(array $tree, array $positions): array
    {
        $nodes = [];
        $links = [];
        $processedNodes = [];

        foreach ($tree as $node) {
            $this->buildNodesAndLinks($node, $positions, $nodes, $links, $processedNodes);
        }

        return [
            'nodes' => array_values($nodes),
            'links' => $links
        ];
    }

    /**
     * @param array $node
     * @param array $positions
     * @param array $nodes
     * @param array $links
     * @param array $processedNodes
     */
    private function buildNodesAndLinks(array $node, array $positions, array &$nodes, array &$links, array &$processedNodes): void
    {
        $step = $node['step'];
        $stepId = $step->id->getValue();

        // Éviter les nœuds en double
        if (!isset($processedNodes[$stepId])) {
            $position = $positions[$stepId];
            $processedNodes[$stepId] = true;

            // Ajouter le nœud
            $nodes[$stepId] = [
                'id' => $stepId,
                'label' => $step->description,
                'type' => strtolower($step->stepType->value),
                'x' => $position['x'],
                'y' => $position['y']
            ];
        }

        // Ajouter les liens vers les enfants
        foreach ($node['children'] as $child) {
            $childId = $child['step']->id->getValue();
            $links[] = [
                'source' => $stepId,
                'target' => $childId,
                'label' => $child['label']
            ];

            $this->buildNodesAndLinks($child, $positions, $nodes, $links, $processedNodes);
        }
    }
} 