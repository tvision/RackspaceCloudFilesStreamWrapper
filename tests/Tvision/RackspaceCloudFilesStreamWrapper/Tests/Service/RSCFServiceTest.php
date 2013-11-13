<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Tests\Service;

use Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesResource;
use Tvision\RackspaceCloudFilesStreamWrapper\Service\RSCFService;

/**
 * @author liuggio
 */
class RSCFServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_framework_MockObject_MockObject $objectStore
     */
    private $objectStore;

    /**
     * @var RSCFService $RSCFService
     */
    private $RSCFService;

    public function setUp()
    {
        $this->objectStore = $this->getMockBuilder('\OpenCloud\ObjectStore\Service')
            ->disableOriginalConstructor()
            ->getMock();
        $this->RSCFService = new RSCFService($this->objectStore, 'liuggio_assetic');
    }

    /**
     * We want to assert that the getContainer api is called
     */
    public function testGetContainer()
    {
        $this->objectStore
            ->expects($this->once())
            ->method('getContainer')
            ->with($this->equalTo('container-name'));

        $this->RSCFService->getContainer('container-name');
    }

    /**
     * We want to assert that the create_object api is called
     */
    public function testGetObjectByContainer()
    {
        $object = $this->getMockBuilder('\OpenCloud\ObjectStore\Resource\Container')
            ->disableOriginalConstructor()
            ->setMethods(array('setName','setContentType'))
            ->getMock();
        $object->expects($this->once())
            ->method('setName')
            ->with($this->equalTo('testName'));
        $object->expects($this->once())
            ->method('setContentType')
            ->with($this->equalTo('testContentType'));
        $container = $this->getMockBuilder('\OpenCloud\ObjectStore\Resource\Container')
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects($this->once())
            ->method('dataObject')
            ->will($this->returnValue($object));

        $data = array(
            'name'         => 'testName',
            'content_type' => 'testContentType',
        );

        $this->RSCFService->getObjectByContainer($container, $data);
    }

    /**
     * We want to test that the file is unlinked
     */
    public function testCreateResourceFromPath()
    {

        $resourceName          = 'js_75a9295_bootstrap-modal_3.js';
        $resourceContainerName = 'liuggio_assetic';
        $path = 'rscf://' . $resourceContainerName . '/' . $resourceName;

        $object = $this->getMockBuilder('\OpenCloud\ObjectStore\Resource\Container')
            ->disableOriginalConstructor()
            ->setMethods(array('setName'))
            ->getMock();
        $object->expects($this->once())
            ->method('setName')
            ->with($this->equalTo($resourceName));

        $container = $this->getMockBuilder('\OpenCloud\ObjectStore\Resource\Container')
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects($this->once())
            ->method('dataObject')
            ->will($this->returnValue($object));

        $this->objectStore
            ->expects($this->once())
            ->method('getContainer')
            ->with($this->equalTo('liuggio_assetic'))
            ->will($this->returnValue($container));

        $resource = new RackspaceCloudFilesResource();
        $resource->setResourceName($resourceName);
        $resource->setContainerName($resourceContainerName);
        $resource->setObject($object);
        $resource->setContainer($container);
        $resource->setCurrentPath($path);

        $ret = $this->RSCFService->createResourceFromPath($path);

        $this->assertEquals($ret, $resource);
    }
}
