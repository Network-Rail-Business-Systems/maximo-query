<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidQuery;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class SetObjectTest extends TestCase
{
    public function testThrowsExceptionIfObjectTypeIsNotSet(): void
    {
        $this->expectException(InvalidQuery::class);
        $this->expectExceptionMessage("Object type not set! Use the 'withObjectStructure()' or the 'withMaximoBusinessObject()' methods and pass in the relevant data.");
    
        MaximoQuery::getUrl();
    }
    
    public function testWithObjectStructureMethodReturnsTheCorrectQueryString(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->getUrl();
    
        $this->assertStringContainsString('os/mxperson', $url);
    }
    
    public function testWithBusinessObjectMethodReturnsTheCorrectQueryString(): void
    {
        $url = MaximoQuery::withBusinessObject('person')
            ->getUrl();
    
        $this->assertStringContainsString('mbo/person', $url);
    }
}
