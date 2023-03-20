<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class CollectionCountTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }
    
    public function testWillNotRequestCollectionCountByDefault(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->getUrl();
    
        $this->assertStringNotContainsString('_collectioncount', $url);
    }
    
    public function testCollectionCountCanBeEnabled(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->withCount()
            ->getUrl();
    
        $this->assertStringContainsString('collectioncount=1', $url);
    }
}
