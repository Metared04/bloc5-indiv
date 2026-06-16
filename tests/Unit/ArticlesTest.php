<?php

namespace Tests\Unit;

use App\Models\Articles;
use PHPUnit\Framework\TestCase;

class ArticlesTest extends TestCase 
{
    public function testBuildOrderClauseWithViewsFilterReturnsSqlClause(): void 
    {
        $filter = 'views';
        $result = Articles::buildOrderClause($filter);
        $this->assertSame(' ORDER BY articles.views DESC', $result);
    }

    public function testBuildOrderClauseWithDateFilterReturnsSqlClause(): void
    {
        $filter = 'date';
        $result = Articles::buildOrderClause($filter);
        $this->assertSame(' ORDER BY articles.published_date DESC', $result);
    }

    public function testBuildOrderClauseWithEmptyFilterReturnsEmptyString(): void
    {
        $filter = '';
        $result = Articles::buildOrderClause($filter);
        $this->assertSame('', $result);
    }
}