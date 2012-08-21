<?php

require_once __DIR__ . '/../../../../PhraseanetWebTestCaseAuthenticatedAbstract.class.inc';

class SphinxTest extends \PhraseanetWebTestCaseAuthenticatedAbstract
{
    protected $client;
    protected $StubbedACL;

    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../../lib/Alchemy/Phrasea/Application/Admin.php';

        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createClient();
        $this->StubbedACL = $this->getMockBuilder('\ACL')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function setAdmin($bool)
    {
        $stubAuthenticatedUser = $this->getMockBuilder('\User_Adapter')
            ->setMethods(array('is_admin', 'ACL'))
            ->disableOriginalConstructor()
            ->getMock();

        $stubAuthenticatedUser->expects($this->any())
            ->method('is_admin')
            ->will($this->returnValue($bool));

        $this->StubbedACL->expects($this->any())
            ->method('has_right_on_base')
            ->will($this->returnValue($bool));

        $stubAuthenticatedUser->expects($this->any())
            ->method('ACL')
            ->will($this->returnValue($this->StubbedACL));

        $stubCore = $this->getMockBuilder('\Alchemy\Phrasea\Core')
            ->setMethods(array('getAuthenticatedUser'))
            ->getMock();

        $stubCore->expects($this->any())
            ->method('getAuthenticatedUser')
            ->will($this->returnValue($stubAuthenticatedUser));

        $this->app['phraseanet.core'] = $stubCore;
    }

    /**
     * @covers Alchemy\Phrasea\Controller\Admin\Sphinx::getConfiguration
     * @covers Alchemy\Phrasea\Controller\Admin\Sphinx::connect
     */
    public function testGetConfiguration()
    {
        $this->setAdmin(true);

        $this->client->request('GET', '/sphinx/configuration/');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * @covers Alchemy\Phrasea\Controller\Admin\Sphinx::getConfiguration
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testGetConfigurationUnauthorizedException()
    {
        $this->setAdmin(false);

        $this->client->request('GET', '/sphinx/configuration/');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * @covers Alchemy\Phrasea\Controller\Admin\Sphinx::submitConfiguration
     */
    public function testPostConfiguration()
    {
        $this->setAdmin(true);

        $this->client->request('POST', '/sphinx/configuration/');
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }
}
