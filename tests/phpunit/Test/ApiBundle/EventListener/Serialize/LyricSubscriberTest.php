<?php

namespace Test\ApiBundle\Model\Serialize;

use PHPUnit\Framework\TestCase;
use App\EventListener\Serialize\LyricSubscriber;
use Potaka\BbcodeBundle\BbCode\TextToHtml;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use App\Entity\Lyric\Lyric;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricSubscriberTest extends TestCase
{
    public function testOnPostSerializeCall()
    {
        $bbCodeMockBuilder = $this->getMockBuilder(TextToHtml::class);
        $bbCodeMockBuilder->disableOriginalConstructor();
        $bbCodeMockBuilder->setMethods(['getHtml']);
        $bbCodeMock = $bbCodeMockBuilder->getMock();
        $bbCodeMock->expects($this->once())
            ->method('getHtml')
            ->with('someInfo')
            ->willReturn('formattedText');

        $subscriber = new LyricSubscriber($bbCodeMock);
        $lyricMockBuilder = $this->getMockBuilder(Lyric::class);
        $lyricMockBuilder->setMethods(['getId']);
        $lyric = $lyricMockBuilder->getMock();
        // 68126 is allowed to be shown
        $lyric->expects($this->any())->method('getId')->willReturn(68126);
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
        $lyricMockBuilder = $this->getMockBuilder(Lyric::class);
        $lyricMockBuilder->setMethods(['getId']);
        $lyric = $lyricMockBuilder->getMock();
        // 68126 is allowed to be shown
        $lyric->expects($this->any())->method('getId')->willReturn(68126);
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
