<?php

namespace Tests\Domain\DecisionTree\Service;

use Domain\DecisionTree\Data\Enum\DiagnosticStepType;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Service\DecisionTreeBuilder;
use PHPUnit\Framework\TestCase;

class DecisionTreeBuilderTest extends TestCase
{
    private DecisionTreeBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new DecisionTreeBuilder();
    }

    public function testEmptySteps(): void
    {
        $result = $this->builder->buildTreeData([]);
        
        $this->assertEquals([
            'nodes' => [],
            'links' => []
        ], $result);
    }

    public function testSingleStep(): void
    {
        $step = $this->createStep('step1', 'Première étape');
        
        $result = $this->builder->buildTreeData([$step]);
        
        $this->assertCount(1, $result['nodes']);
        $this->assertEmpty($result['links']);
        
        $this->assertEquals('step1', $result['nodes'][0]['id']);
        $this->assertEquals('Première étape', $result['nodes'][0]['label']);
    }

    public function testSimpleTree(): void
    {
        $step1 = $this->createStep('step1', 'Première étape');
        $step2 = $this->createStep('step2', 'Deuxième étape');
        $step3 = $this->createStep('step3', 'Troisième étape');

        $step1->nextStepOKId = new DiagnosticStepId('step2');
        $step1->nextStepKOId = new DiagnosticStepId('step3');

        $result = $this->builder->buildTreeData([$step1, $step2, $step3]);
        
        $this->assertCount(3, $result['nodes']);
        $this->assertCount(2, $result['links']);
        
        // Vérifier les liens
        $this->assertEquals('step1', $result['links'][0]['source']);
        $this->assertEquals('step2', $result['links'][0]['target']);
        $this->assertEquals('OK', $result['links'][0]['label']);
        
        $this->assertEquals('step1', $result['links'][1]['source']);
        $this->assertEquals('step3', $result['links'][1]['target']);
        $this->assertEquals('KO', $result['links'][1]['label']);
    }

    public function testComplexTree(): void
    {
        $step1 = $this->createStep('step1', 'Première étape');
        $step2 = $this->createStep('step2', 'Deuxième étape');
        $step3 = $this->createStep('step3', 'Troisième étape');
        $step4 = $this->createStep('step4', 'Quatrième étape');
        $step5 = $this->createStep('step5', 'Cinquième étape');

        $step1->nextStepOKId = new DiagnosticStepId('step2');
        $step1->nextStepKOId = new DiagnosticStepId('step3');
        $step2->nextStepOKId = new DiagnosticStepId('step4');
        $step2->nextStepKOId = new DiagnosticStepId('step5');

        $result = $this->builder->buildTreeData([$step1, $step2, $step3, $step4, $step5]);
        
        $this->assertCount(5, $result['nodes']);
        $this->assertCount(4, $result['links']);
        
        // Vérifier que tous les nœuds sont présents
        $nodeIds = array_column($result['nodes'], 'id');
        $this->assertContains('step1', $nodeIds);
        $this->assertContains('step2', $nodeIds);
        $this->assertContains('step3', $nodeIds);
        $this->assertContains('step4', $nodeIds);
        $this->assertContains('step5', $nodeIds);
        
        // Vérifier que tous les liens sont corrects
        $this->assertLinkExists($result['links'], 'step1', 'step2', 'OK');
        $this->assertLinkExists($result['links'], 'step1', 'step3', 'KO');
        $this->assertLinkExists($result['links'], 'step2', 'step4', 'OK');
        $this->assertLinkExists($result['links'], 'step2', 'step5', 'KO');
    }

    private function createStep(string $id, string $description): DiagnosticStep
    {
        return new DiagnosticStep(
            new DiagnosticStepId($id),
            new ProblemTypeId('problem1'),
            DiagnosticStepType::SYMPTOM,
            null,
            null,
            null,
            $description,
            false,
            0,
            null
        );
    }

    private function assertLinkExists(array $links, string $source, string $target, string $label): void
    {
        $found = false;
        foreach ($links as $link) {
            if ($link['source'] === $source && 
                $link['target'] === $target && 
                $link['label'] === $label) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Link from $source to $target with label $label not found");
    }
} 