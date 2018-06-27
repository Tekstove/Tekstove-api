<?php

namespace Test\ApiBundle\Model\Serialize;

use PHPUnit\Framework\TestCase;
use Tekstove\ApiBundle\EventListener\Model\Serialize\LyricSubscriber;
use Potaka\BbcodeBundle\BbCode\TextToHtml;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Tekstove\ApiBundle\Model\Lyric;

/**
 * @author potaka
 */
class LyricSubscriberTest extends TestCase
{
    public function testOnPostSerializeCall()
    {
        $bbCodeMockBuilder = $this->getMockBuilder(TextToHtml::class);
        $bbCodeMockBuilder->disableOriginalConstructor();
        $bbCodeMockBuilder->setMethods(['getHtml']);
        $bbCodeMock = $bbCodeMockBuilder->getMock();
        $bbCodeMock->expects($this->once())->method('getHtml')->with('someInfo')->willReturn('formattedText');

        $subscriber = new LyricSubscriber($bbCodeMock);
        $lyric = new Lyric();
        $lyric->setExtraInfo('someInfo');

        $visitorMockBuilder = $this->getMockBuilder(\JMS\Serializer\JsonSerializationVisitor::class);
        $visitorMockBuilder->disableOriginalConstructor();
        $visitorMockBuilder->setMethods(['setData']);
        $visitorMock = $visitorMockBuilder->getMock();
        $visitorMock->expects($this->once())->method('setData')->with(
            'extraInfoHtml',
            'formattedText'
        );

        $objectEventMockBuilder = $this->getMockBuilder(ObjectEvent::class);
        $objectEventMockBuilder->disableOriginalConstructor();
        $objectEventMockBuilder->setMethods(['getObject', 'getVisitor']);
        $objectEventMock = $objectEventMockBuilder->getMock();
        $objectEventMock->expects($this->once())->method('getObject')->willReturn($lyric);
        $objectEventMock->expects($this->once())->method('getVisitor')->willReturn($visitorMock);

        $subscriber->onPostSerialize($objectEventMock);
    }

    public function testOnPostSerializeCallEmptyExtraInfo()
    {
        $bbCodeMockBuilder = $this->getMockBuilder(TextToHtml::class);
        $bbCodeMockBuilder->disableOriginalConstructor();
        $bbCodeMockBuilder->setMethods(['getHtml']);
        $bbCodeMock = $bbCodeMockBuilder->getMock();
        $bbCodeMock->expects($this->never())->method('getHtml');

        $subscriber = new LyricSubscriber($bbCodeMock);
        $lyric = new Lyric();
        $lyric->setExtraInfo(null);

        $visitorMockBuilder = $this->getMockBuilder(\JMS\Serializer\JsonSerializationVisitor::class);
        $visitorMockBuilder->disableOriginalConstructor();
        $visitorMockBuilder->setMethods(['setData']);
        $visitorMock = $visitorMockBuilder->getMock();
        $visitorMock->expects($this->once())->method('setData')->with(
            'extraInfoHtml',
            ''
        );

        $objectEventMockBuilder = $this->getMockBuilder(ObjectEvent::class);
        $objectEventMockBuilder->disableOriginalConstructor();
        $objectEventMockBuilder->setMethods(['getObject', 'getVisitor']);
        $objectEventMock = $objectEventMockBuilder->getMock();
        $objectEventMock->expects($this->once())->method('getObject')->willReturn($lyric);
        $objectEventMock->expects($this->once())->method('getVisitor')->willReturn($visitorMock);

        $subscriber->onPostSerialize($objectEventMock);
    }
}
