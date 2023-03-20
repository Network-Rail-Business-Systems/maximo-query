<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidQuery;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidResponse;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\MaximoResponse;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class UpdateTest extends TestCase
{
    public function testWillThrowExceptionIfThereIsNoWhereClause(): void
    {
        $this->expectException(InvalidQuery::class);
        $this->expectExceptionMessage('No where clause has been set. Please filter your query so that a single resource is updated');
        
        MaximoQuery::withObjectStructure('trim')
            ->update([]);
    }
    
    public function testWillThrowExceptionIfWhereClauseReturnsMultipleRecords(): void
    {
        $this->expectException(InvalidResponse::class);
        $this->expectExceptionMessage('Your query was ambiguous and multiple resources were found. Updates can only be performed on single resources.');
        
        Http::fake([
            '*' => Http::response(MockResponses::multiRecords())
        ]);
    
        MaximoQuery::withObjectStructure('trim')
            ->where('sausage', 'eggs')
            ->update([]);
    }
    
    public function testWillThrowExceptionIfNoResourceIsFound(): void
    {
        $this->expectException(InvalidResponse::class);
        $this->expectExceptionMessage('A resource could not be found. Please try different parameters.');
    
        Http::fake([
            '*' => Http::response(MockResponses::noResults())
        ]);
    
        MaximoQuery::withObjectStructure('trim')
            ->where('sausage', 'eggs')
            ->update([]);
    }
    
    public function testReturnsMaximoResponseObject(): void
    {
        Http::fake([
            '*' => Http::response(MockResponses::singleRecord())
        ]);
    
        $response = MaximoQuery::withObjectStructure('trim')
            ->where('sausage', 'eggs')
            ->update([]);
    
        $this->assertInstanceOf(MaximoResponse::class, $response);
    }
}
