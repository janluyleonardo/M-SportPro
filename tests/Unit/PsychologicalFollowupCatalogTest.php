<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PsychologicalFollowupCatalogTest extends TestCase
{
    public function test_catalog_contains_the_six_expected_blocks_and_forty_four_unique_criteria(): void
    {
        $blocks = require __DIR__.'/../../config/psychological_followup.php';
        $criteria = collect($blocks['blocks'])
            ->flatMap(fn (array $block) => $block['criteria']);

        $this->assertCount(6, $blocks['blocks']);
        $this->assertCount(44, $criteria);
        $this->assertCount(44, $criteria->keys()->unique());
    }
}
