<?php

namespace Test\ApiBundle\Model;

use Tekstove\ApiBundle\Model\User;
use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\Acl\Permission;

/*
 * UserTest
 */

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Anonymous user try to edit existing lyric
     */
    public function testGetAllowedFields()
    {
        $user = new User();
        $lyric = new Lyric();
        $lyric->setId(5);
        $allowedFields = $user->getAllowedLyricFields($lyric);
        $this->assertEmpty($allowedFields);
    }
    
    public function testGetAllowedFieldsDownload()
    {
        $userMockBuilder = $this->getMockBuilder(User::class);
        $userMockBuilder->setMethods(['getPermissions']);
        $user = $userMockBuilder->getMock();
        $user->expects($this->once())
                ->method('getPermissions')
                ->will(
                    $this->returnValue(
                        [Permission::LYRIC_EDIT_DOWNLOAD => Permission::LYRIC_EDIT_DOWNLOAD]
                    )
                );
        $lyric = new \Tekstove\ApiBundle\Model\Lyric();
        $lyric->setId(5);
        $allowedFields = $user->getAllowedLyricFields($lyric);
        $this->assertCount(1, $allowedFields);
        $this->assertSame('download', $allowedFields[0]);
    }
}
