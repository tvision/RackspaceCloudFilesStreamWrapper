<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Tests\Service;

use Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesResource;
use Tvision\RackspaceCloudFilesStreamWrapper\Service\RSCFService;

/**
 * @author liuggio
 */
class RSCFServiceTest extends \PHPUnit_Framework_TestCase
{
    private $service;
    private $container;
    private $dataObject;
    private $RSCFService;
    private $rackspaceAPI;
    private $fileTypeGuesser;

    public function setUp()
    {
        $this->fileTypeGuesser = $this->getMock(
            'Tvision\RackspaceCloudFilesStreamWrapper\Interfaces\FileTypeGuesserInterface'
        );

        $this->rackspaceAPI    = $this
            ->getMockBuilder('Tvision\RackspaceCloudFilesStreamWrapper\Service\RackspaceApi')
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataObject = $this->getMockBuilder('OpenCloud\ObjectStore\Resource\DataObject')
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = $this->getMockBuilder('OpenCloud\Common\Service\AbstractService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container = $this->getMockBuilder('OpenCloud\ObjectStore\Resource\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->expects($this->any())
            ->method('Name')
            ->will($this->returnValue('liuggio_assetic'));

        $this->container->expects($this->any())
            ->method('DataObject')
            ->will($this->returnValue($this->dataObject));

        $this->container->expects($this->any())
            ->method('getService')
            ->will($this->returnValue($this->service));

        $this->rackspaceAPI->expects($this->any())
            ->method('getContainer')
            ->will($this->returnValue($this->container));

        $this->RSCFService = new RSCFService(
            'rscf',
            $this->rackspaceAPI,
            '\Tvision\RackspaceCloudFilesStreamWrapper\StreamWrapper\RackspaceCloudFilesStreamWrapper',
            '\Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesResource',
            $this->fileTypeGuesser
        );
    }

    /**
     * We want to assert that the get_container api is called
     */
    public function testApiGetContainer()
    {
        $ret = $this->RSCFService->apiGetContainer('container-name');

        $this->assertEquals($ret, $this->container);
        $this->assertEquals($ret->Name(), $this->container->Name());
    }

    /**
     * We want to assert that the create_object api is called
     */
    public function testApiGetObjectByContainer()
    {
        $ret = $this->RSCFService->apiGetObjectByContainer($this->container, array(
            'name'         => 'test-object',
            'content_type' => 'image/gif'
        ));

        $this->assertEquals($ret, $this->dataObject);
    }

    /**
     * We want to test that the file is unlinked
     */
    public function testCreateResourceFromPath()
    {
        $resourceName          = 'js_75a9295_bootstrap-modal_3.js';
        $resourceContainerName = 'liuggio_assetic';
        $path = 'rscf://' . $resourceContainerName . '/' . $resourceName;

        $resource = new RackspaceCloudFilesResource();
        $resource->setResourceName($resourceName);
        $resource->setContainerName($resourceContainerName);
        $resource->setObject($this->dataObject);
        $resource->setContainer($this->container);
        $resource->setCurrentPath($path);

        $ret = $this->RSCFService->createResourceFromPath($path);

        $this->assertEquals($ret, $resource);
    }
}
