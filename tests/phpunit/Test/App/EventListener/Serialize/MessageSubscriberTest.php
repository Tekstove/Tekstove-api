<?php

namespace Test\App\Model\EventListener\Serialize;

use App\Entity\Chat\Message;
use App\EventListener\Serialize\MessageSubscriber;
use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Metadata\PropertyMetadata;
use Metadata\ClassMetadata;
use Metadata\MetadataFactoryInterface;
use PHPUnit\Framework\MockObject\Matcher\InvokedCount;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MessageSubscriberTest extends TestCase
{
    private function getContextMock(callable $visitorExpectations)
    {
        $metadataFactoryMockBuilder = $this->getMockBuilder(MetadataFactoryInterface::class);
        $metadataFactoryMockBuilder->setMethods(['getMetadataForClass']);
        $metadataFactoryMockBuilder->disableOriginalConstructor();
        $metadataFactoryMock = $metadataFactoryMockBuilder->getMockForAbstractClass();

        $classMetadata = new ClassMetadata(Message::class);
        $classMetadata->propertyMetadata = [
            'ip' => new PropertyMetadata(Message::class, 'ip'),
        ];

        $metadataFactoryMock->expects($this->any())->method('getMetadataForClass')->willReturn($classMetadata);


        $mockBuilder = $this->getMockBuilder(Context::class);
        $mockBuilder->setMethods(['getMetadataFactory', 'getExclusionStrategy', 'getVisitor']);
        $mockBuilder->disableOriginalConstructor();
        $mock = $mockBuilder->getMockForAbstractClass();
        $mock->expects($this->any())->method('getMetadataFactory')->willReturn($metadataFactoryMock);

        $exclusionStrategyMockBuilder = $this->getMockBuilder(ExclusionStrategyInterface::class);
        $exclusionStrategyMock = $exclusionStrategyMockBuilder->getMockForAbstractClass();
        $mock->expects($this->any())->method('getExclusionStrategy')->willReturn($exclusionStrategyMock);

        $visitorMockBuilder = $this->getMockBuilder(JsonSerializationVisitor::class);
        $visitorMockBuilder->setMethods(['addData', 'setdata']);
        $visitorMockBuilder->disableOriginalConstructor();
        $visitorMock = $visitorMockBuilder->getMockForAbstractClass();
        $mock->expects($this->any())->method('getVisitor')->willReturn($visitorMock);

        $visitorExpectations($visitorMock);

        return $mock;
    }

    public function metadataDataProvider()
    {
        return [
            [false, false, false, false],
            [true, true, true, true],
        ];
    }

    /**
     * @dataProvider metadataDataProvider
     */
    public function testOnPostSerializeMetadata(bool $expectedBan, bool $expectedCensore, bool $allowedBan, bool $allowedCensore)
    {
        $message = new Message();

        $authMockBuilder = $this->getMockBuilder(AuthorizationCheckerInterface::class);
        $authMockBuilder->setMethods(['isGranted']);

        $mock = $authMockBuilder->getMock();
        $mock->expects($this->any())->method('isGranted')
            ->withConsecutive(
                [$this->equalTo('censore'), $this->equalTo($message)],
                [$this->equalTo('ban'), $this->equalTo($message)]
            )
            ->willReturnOnConsecutiveCalls(
                $allowedCensore,
                $allowedBan
            );

        $subscriber = new MessageSubscriber($mock);

        $testCase = $this;
        $visitorExpectations = function (JsonSerializationVisitor $visitorMock) use ($testCase, $expectedBan, $expectedCensore) {
            $visitorMock->expects($testCase->once())->method('addData')->with(
                $testCase->equalTo('_meta'),
                $testCase->equalTo([
                    'ban' => $expectedBan,
                    'censore' => $expectedCensore,
                ])
            );
        };
        $contextMock = $this->getContextMock($visitorExpectations);

        $event = new ObjectEvent($contextMock, $message, []);

        $subscriber->onPostSerialize($event);
    }

    public function ipDataProvider()
    {
        return [
            [true, $this->never()],
            [false, $this->once()],
        ];
    }

    /**
     * @dataProvider ipDataProvider
     */
    public function testOnPostSerializeMetadataIp(bool $allowedToViewIp, InvokedCount $ipCalls)
    {
        $message = new Message();

        $authMockBuilder = $this->getMockBuilder(AuthorizationCheckerInterface::class);
        $authMockBuilder->setMethods(['isGranted']);

        $mock = $authMockBuilder->getMock();
        $mock->expects($this->any())->method('isGranted')
            ->withConsecutive(
                [$this->equalTo('censore'), $this->equalTo($message)],
                [$this->equalTo('ban'), $this->equalTo($message)],
                [$this->equalTo('viewIp'), $this->equalTo($message)]
            )
            ->willReturnOnConsecutiveCalls(
                true,
                true,
                $allowedToViewIp
            );


        $subscriber = new MessageSubscriber($mock);

        $testCase = $this;
        $visitorExpectations = function (JsonSerializationVisitor $visitorMock) use ($testCase, $ipCalls) {
            $visitorMock->expects($ipCalls)->method('setData')->with(
                $testCase->equalTo('ip'),
                $testCase->equalTo(false)
            );
        };
        $contextMock = $this->getContextMock($visitorExpectations);

        $event = new ObjectEvent($contextMock, $message, []);

        $subscriber->onPostSerialize($event);
    }
}
