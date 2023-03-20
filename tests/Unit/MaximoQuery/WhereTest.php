<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidQuery;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class WhereTest extends TestCase
{
    /**
     * @dataProvider operators
     */
    public function testPassingInvalidOperatorThrowsException(string $operator, bool $expectException): void
    {
        if ($expectException) {
            $this->expectException(InvalidQuery::class);
            $this->expectExceptionMessage("Invalid operator passed to 'where()' method. Please use one of the following: \n" . print_r(['=', '>=', '>', '<', '⇐', '!='], true));
        }

        $url = MaximoQuery::withObjectStructure('mxperson')
            ->where('column1', $operator, 'some value')
            ->getUrl();

        $this->assertStringContainsString("oslc.where=column1$operator\"some value\"", $url);
    }
    
    public function operators(): array
    {
        return [
            ['=', false],
            ['>=', false],
            ['>', false],
            ['<', false],
            ['⇐', false],
            ['!=', false],
            ['#', true],
        ];
    }
    
    public function testWhereMethodDefaultsToEqualsIfNoOperatorPassed(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->where('column1', 'some value')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1="some value"', $url);
    }
    
    public function testNumericValuesPassedToWhereMethodAreNotQuoted(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->where('column1', 100)
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1=100', $url);
    }
    
    public function testCanAddWhereInClauseQuery(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->whereIn('column1', ['tom', 'dick', 'harry'])
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1 in ["tom","dick","harry"]', $url);
    }
    
    public function testCanAddWhereNotInClauseToQuery(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->whereNotIn('column1', ['tom', 'dick', 'harry'])
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1!="[tom,dick,harry]"', $url);
    }
    
    public function testCanAddWhereStartsWithClauseQuery(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->whereStartsWith('column1', 'some value')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1="some value%"', $url);
    }
    
    public function testCanAddWhereEndsWithClauseQuery(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->whereEndsWith('column1', 'some value')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1="%some value"', $url);
    }
    
    public function testCanAddWhereLikeClauseQuery(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->whereLike('column1', 'some value')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1="%some value%"', $url);
    }
    
    public function testCanAddWhereNullClauseQuery(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->whereNull('column1')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1!="*"', $url);
    }
    
    public function testCanAddWhereNotNullClauseToQuery(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->whereNotNull('column1')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1="*"', $url);
    }
    
    public function testWhereMethodsCanBeChained(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->where('column1', 'some value')
            ->where('column2', 'another value')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.where=column1="some value" and column2="another value"', $url);
    }
}
