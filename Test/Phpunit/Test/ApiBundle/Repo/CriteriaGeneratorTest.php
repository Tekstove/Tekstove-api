<?php

namespace Test\ApiBundle\Repo;

use Tekstove\ApiBundle\Repo\CriteriaGenerator;
use Mockery;
use Propel\Runtime\Map\TableMap;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class CriteriaGeneratorTest extends MockeryTestCase
{
    public function testGenerateCompositeCriterion()
    {
        // this test is not very useful

        $generatorMockBuilder = $this->getMockBuilder(CriteriaGenerator::class);
        $generatorMockBuilder->disableOriginalConstructor();
        $generatorMockBuilder->setMethods(['getSqlFieldNameFromPhpName']);
        $generator = $generatorMockBuilder->getMock();
        $generator->expects($this->once())
                  ->method('getSqlFieldNameFromPhpName')
                  ->willReturn('mockedValue');

        $modelCriteriaMockBuilder = $this->getMockBuilder(\Propel\Runtime\ActiveQuery\ModelCriteria::class);
        $modelCriteriaMockBuilder->disableOriginalConstructor();
        $modelCriteriaMockBuilder->setMethods(['getTableMap', 'addCond', 'combine']);

        $modelCriteriaMock = $modelCriteriaMockBuilder->getMock();

        $tableMapMockBuilder = $this->getMockBuilder(TableMap::class);
        $tableMapMockBuilder->disableOriginalConstructor();
        $tableMock = $tableMapMockBuilder->getMock();

        $modelCriteriaMock->expects($this->once())
                          ->method('getTableMap')
                          ->wilLReturn($tableMock);

        $modelCriteriaMock->expects($this->once())
                          ->method('addCond');

        $modelCriteriaMock->expects($this->once())
                          ->method('combine');

        $generator->generateCompositeCriterion(
            [
                'operator' => 'or',
                'value' => [
                    [
                        'operator' => '=',
                        'value' => '2',
                        'field' => 'title',
                    ],
                ],
            ],
            $modelCriteriaMock
        );
    }

    public function testGetSqlFieldNameFromPhpNamePhpAndSqlNameMatch()
    {
        $generator = new CriteriaGenerator();
        $tableMapMockBuilder = $this->getMockBuilder(TableMap::class);
        $tableMapMockBuilder->disableOriginalConstructor();
        $tableMapMockBuilder->setMethods(
            [
                'hasColumnByPhpName',
                'getColumnByPhpName'
            ]
        );

        $tableMapMock = $tableMapMockBuilder->getMock();
        $tableMapMock->expects($this->once())
                     ->method('hasColumnByPhpName')
                     ->willReturn(true);

        $tableMapMock->expects($this->once())
                     ->method('getColumnByPhpName')
                     ->willReturn('titleCache');

        $sqlField = $generator->getSqlFieldNameFromPhpName('titleCache', $tableMapMock);
        $this->assertSame('titleCache', $sqlField);
    }

    public function testGetSqlFieldNameFromPhpNamePhpAndSqlNameNotMatching()
    {
        $generator = new CriteriaGenerator();

        $tableMapMock = Mockery::mock(TableMap::class);
        $tableMapMock->shouldReceive('hasColumnByPhpName')
            ->times(1)
            ->with('titleCache')
            ->andReturn(false);
        $tableMapMock->shouldReceive('hasColumnByPhpName')
            ->times(1)
            ->with('TitleCache')
            ->andReturn(true);
        

        $tableMapMock->shouldReceive('getColumnByPhpName')
                ->times(1)
                ->with('TitleCache')
                ->andReturn('titleCache');

        $sqlField = $generator->getSqlFieldNameFromPhpName('titleCache', $tableMapMock);
        $this->assertSame('titleCache', $sqlField);
    }

    public function testGetSqlFieldNameFromPhpNamePhpAndSqlNameNoField()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown field field1');

        $generator = new CriteriaGenerator();

        $tableMap = Mockery::mock(TableMap::class);
        $tableMap->shouldReceive('hasColumnByPhpName')
                 ->times(2)
                 ->andReturn(false);

        $generator->getSqlFieldNameFromPhpName('field1', $tableMap);
    }
}
