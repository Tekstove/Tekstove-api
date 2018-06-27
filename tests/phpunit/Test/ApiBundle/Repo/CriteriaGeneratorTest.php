<?php

namespace Test\ApiBundle\Repo;

use Tekstove\ApiBundle\Repo\CriteriaGenerator;
use Mockery;
use Propel\Runtime\Map\TableMap;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class CriteriaGeneratorTest extends MockeryTestCase
{
    public function testGenerateCompositeCriterion()
    {
        $generatorMockBuilder = $this->getMockBuilder(CriteriaGenerator::class);
        $generatorMockBuilder->disableOriginalConstructor();
        $generatorMockBuilder->setMethods(['getSqlFieldNameFromPhpName']);
        $generator = $generatorMockBuilder->getMock();
        $generator->expects($this->once())
                  ->method('getSqlFieldNameFromPhpName')
                  ->willReturn('mockedSqlField');

        $modelCriteriaMockBuilder = $this->getMockBuilder(ModelCriteria::class);
        $modelCriteriaMockBuilder->disableOriginalConstructor();
        $modelCriteriaMockBuilder->setMethods(
            [
                'getTableMap',
                'addCond',
                'combine',
                'getNewCriterion',
            ]
        );

        $criterionMock = Mockery::mock(\Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion::class);

        $modelCriteriaMock = $modelCriteriaMockBuilder->getMock();

        $modelCriteriaMock->expects($this->once())
                ->method('getNewCriterion')
                ->with(
                    $this->equalTo('mockedSqlField'),
                    $this->equalTo('2'),
                    $this->equalTo('=')
                )
                ->willReturn($criterionMock);

        $tableMapMockBuilder = $this->getMockBuilder(TableMap::class);
        $tableMapMockBuilder->disableOriginalConstructor();
        $tableMock = $tableMapMockBuilder->getMock();

        $modelCriteriaMock->expects($this->once())
                          ->method('getTableMap')
                          ->wilLReturn($tableMock);

        $modelCriteriaMock->expects($this->once())
                          ->method('addCond')
                          ->with(
                                $this->anything(),
                                $this->equalTo($criterionMock)
                            );

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

    public function testGenerateCompositeCriterionNotNull()
    {
        $tableMap = Mockery::mock(TableMap::class);

        $generatorMockBuilder = $this->getMockBuilder(CriteriaGenerator::class);
        $generatorMockBuilder->disableOriginalConstructor();
        $generatorMockBuilder->setMethods(['getSqlFieldNameFromPhpName']);
        $generator = $generatorMockBuilder->getMock();
        $generator->expects($this->once())
                  ->method('getSqlFieldNameFromPhpName')
                  ->willReturn('title');

        $modelCriteria = Mockery::mock(ModelCriteria::class);
        $modelCriteria->shouldReceive('getTableMap')
                      ->andReturn($tableMap);

        $modelCriteria->shouldReceive('getNewCriterion')
                      ->once()
                      ->with(
                            'title',
                            null,
                            \Propel\Runtime\ActiveQuery\Criteria::ISNOTNULL
                        );

        $modelCriteria->shouldReceive('addCond')
                      ->once();

        $modelCriteria->shouldReceive('combine')
                      ->once();

        $generator->generateCompositeCriterion(
            [
                'operator' => 'or',
                'value' => [
                    [
                        'operator' => 'NOT_NULL',
                        'value' => '0',
                        'field' => 'title',
                    ],
                ]
            ],
            $modelCriteria
        );
    }

    private function getColumnMapMock($returnedField)
    {
        $mockBuilder = $this->getMockBuilder(\Propel\Runtime\Map\ColumnMap::class);
        $mockBuilder->disableOriginalConstructor();
        $mockBuilder->setMethods(['getName']);
        $mock = $mockBuilder->getMock();
        $mock->expects($this->once())
                ->method('getName')
                ->willReturn($returnedField);

        return $mock;
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
                     ->willReturn($this->getColumnMapMock('titleCache'));

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
                ->andReturn($this->getColumnMapMock('titleCache'));

        $sqlField = $generator->getSqlFieldNameFromPhpName('titleCache', $tableMapMock);
        $this->assertSame('titleCache', $sqlField);
    }

    public function testGetSqlFieldNameFromPhpNamePhpAndSqlNameNoField()
    {
        $this->expectException(\Tekstove\ApiBundle\Repo\Exception\FieldNameNotFound::class);
        $this->expectExceptionMessage('Unknown field field1');

        $generator = new CriteriaGenerator();

        $tableMap = Mockery::mock(TableMap::class);
        $tableMap->shouldReceive('hasColumnByPhpName')
                 ->times(2)
                 ->andReturn(false);

        $generator->getSqlFieldNameFromPhpName('field1', $tableMap);
    }
}
