<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Tests\Service;

use Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesResource;
use Tvision\RackspaceCloudFilesStreamWrapper\Service\RSCFService;

/**
 * @author liuggio
 * @author Chris Warner <cdw.lighting@gmail.com>
 */
class RSCFServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Constant for Stream Wrapper Class
     */
    const STREAM_WRAPPER_CLASS = '\\Tvision\\RackspaceCloudFilesStreamWrapper\\StreamWrapper\\RackspaceCloudFilesStreamWrapper';

    /**
     * Constant For Cloud Files Reosurce Class
     */
    const CLOUD_FILES_RESOURCE_CLASS = 'Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesResource';

    /**
     * Assert that the get_container api is called.
     */
    public function testApiGetContainer()
    {
        $container = $this->getMock('\OpenCloud\ObjectStore\Container', array ('Name'), array (), '', false);
        $container->expects($this->any())
                  ->method('Name')
                  ->will($this->returnValue('container-name'));

        $rackspaceService = $this->getMockBuilder("Tvision\RackspaceCloudFilesStreamWrapper\Service\RackspaceApi")
                                 ->disableOriginalConstructor()
                                 ->getMock();
        $rackspaceService->expects($this->once())
                         ->method('getContainer')
                         ->will($this->returnValue($container));

        $typeGuesser = $this->getMockBuilder('Tvision\RackspaceCloudFilesStreamWrapper\Model\FileTypeGuesserInterface')
                            ->disableOriginalConstructor()
                            ->getMock();


        $service = new RSCFService('rscf',
                                   $rackspaceService,
                                   self::STREAM_WRAPPER_CLASS,
                                   self::CLOUD_FILES_RESOURCE_CLASS,
                                   $typeGuesser
        );

        $ret = $service->apiGetContainer('container-name');

        $this->assertEquals($ret, $container);
        $this->assertEquals($ret->Name(), $container->Name());
    }


    /**
     *  Assert that the the create_object api is called.
     */
    public function testApiGetObjectByContainer()
    {
        $obj = $this->getMock('\OpenCloud\ObjectStore\Resource\DataObject', array (), array (), '', false);

        $container = $this->getMock(
                          '\OpenCloud\ObjectStore\Resource\Container', array ('Name', 'DataObject'), array (), '', false
        );
        $container->expects($this->any())
                  ->method('Name')
                  ->will($this->returnValue('container-name'));
        $container->expects($this->once())
                  ->method('DataObject')
                  ->will($this->returnValue($obj));

        $rackspaceService = $this->getMockBuilder("Tvision\RackspaceCloudFilesStreamWrapper\Service\RackspaceApi")
                                 ->disableOriginalConstructor()
                                 ->getMock();
        $rackspaceService->expects($this->never())
                         ->method('getContainer')
                         ->will($this->returnValue($container));

        $typeGuesser = $this->getMockBuilder('Tvision\RackspaceCloudFilesStreamWrapper\Model\FileTypeGuesserInterface')
                            ->disableOriginalConstructor()
                            ->getMock();


        $service = new RSCFService('rscf',
                                   $rackspaceService,
                                   self::STREAM_WRAPPER_CLASS,
                                   self::CLOUD_FILES_RESOURCE_CLASS,
                                   $typeGuesser
        );

        $ret = $service->apiGetObjectByContainer(
                       $container, array ('name' => 'test-object', 'content_type' => 'image/gif')
        );

        $this->assertEquals($ret, $obj);
    }


    /**
     * Asser that the file is uploaded.
     */
    public function testCreateResourceFromPath()
    {
        //we want to test that the file is unlinked
        $resourceName          = 'js_75a9295_bootstrap-modal_3.js';
        $resourceContainerName = 'liuggio_assetic';
        $path                  = 'rscf://' . $resourceContainerName . '/' . $resourceName;

        $object    = $this->getMock('\OpenCloud\ObjectStore\Resource\DataObject', array (), array (), '', false);
        $container = $this->getMock('\OpenCloud\ObjectStore\Resource\Container',
                                    array ('dataObject'),
                                    array (), '', false);

        $container->expects($this->once())
            ->method('dataObject')
            ->will($this->returnValue($object));

        $resource = new RackspaceCloudFilesResource();
        $resource->setResourceName($resourceName);
        $resource->setContainerName($resourceContainerName);
        $resource->setObject($object);
        $resource->setContainer($container);
        $resource->setCurrentPath($path);

        $rackspaceApi = $this->getMockBuilder("Tvision\RackspaceCloudFilesStreamWrapper\Service\RackspaceApi")
                             ->disableOriginalConstructor()
                             ->getMock();
        $rackspaceApi->expects($this->once())
                     ->method('getContainer')
                     ->will($this->returnValue($container));

        $typeGuesser = $this->getMockBuilder('Tvision\RackspaceCloudFilesStreamWrapper\Model\FileTypeGuesserInterface')
                            ->disableOriginalConstructor()
                            ->getMock();

        $service = new RSCFService('rscf', $rackspaceApi, self::STREAM_WRAPPER_CLASS, self::CLOUD_FILES_RESOURCE_CLASS, $typeGuesser);

        $ret = $service->createResourceFromPath($path);

        //asserting
        $this->assertEquals($ret, $resource);
    }
}

 
