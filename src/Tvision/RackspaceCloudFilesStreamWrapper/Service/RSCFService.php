<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Service;

use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\ObjectStore\Resource\DataObject;
use Tvision\RackspaceCloudFilesStreamWrapper\Interfaces\FileTypeGuesserInterface;
use Tvision\RackspaceCloudFilesStreamWrapper\Interfaces\RackspaceCloudFilesServiceInterface;

/**
 * Description of RackSpaceObject
 *
 * @author liuggio
 */
class RSCFService implements RackspaceCloudFilesServiceInterface
{
    private $rackspaceService;

    private $protocolName;

    private $resourceClass;

    private $streamWrapperClass;

    private $fileTypeGuesser;


    public function __construct($protocolName,
                                RackspaceApi $rackspaceService,
                                $streamWrapperClass,
                                $resourceEntityClass,
                                FileTypeGuesserInterface $fileTypeGuesser)
    {
        $this->protocolName = $protocolName;
        $this->rackspaceService = $rackspaceService;
        $this->streamWrapperClass = $streamWrapperClass;
        $this->resourceClass = $resourceEntityClass;

        if ($fileTypeGuesser) {
            $this->setFileTypeGuesser($fileTypeGuesser);
        }
    }

    /**
     * @return string
     */
    public function getProtocolName()
    {
        return $this->protocolName;
    }

    /**
     * @return string
     */
    public function getResourceClass()
    {
        return $this->resourceClass;
    }

    /**
     * @return string
     */
    public function getStreamWrapperClass()
    {
        return $this->streamWrapperClass;
    }

    /**
     *
     * @param type $resource
     * @return false|container
     */
    public function getContainerByResource($resource)
    {
        return $resource->getContainer();
    }

    /**
     * @param $resource
     * @return false|object
     */
    public function getObjectByResource($resource)
    {
        $container = $resource->getContainer();
        if ($container) {
            return $resource->getObject();
        } else {
            return false;
        }
    }

    /**
     * @param string $containerName
     *
     * @return Container|false
     */
    public function apiGetContainer($containerName)
    {
        $container = $this->getRackspaceService()
            ->getContainer($containerName);
        if (!$container) {
            return false;
        }
        return $container;
    }


    /**
     * @param \OpenCloud\ObjectStore\Resource\Container $container
     * @param $objectData
     *
     * @return DataObject
     */
    public function apiGetObjectByContainer(Container $container, $objectData)
    {
        if (!$container) {
            return false;
        }
        $object = $container->DataObject();
        $object->setName($objectData['name']);
        $object->setContentType($objectData['content_type']);

        return $object;
    }

    /**
     *
     * @param string $path
     *
     * @return resource|false
     */
    public function createResourceFromPath($path)
    {
        $resource = $this->getResourceClass();
        $resource = new $resource($path);
        if (!$resource) {
            return false;
        }

        $container = $this->getRackspaceService()->getContainer();
        if (!$container) {
            return false;
        }
        $resource->setContainer($container);
        //create_object but no problem if already exists
        $objectData = array(
            'name' => $resource->getResourceName(),
            'content_type' => $this->guessFileType($path),
        );

        $obj = $this->apiGetObjectByContainer($container, $objectData, $path);
        if (!$obj) {
            return false;
        }
        $resource->setObject($obj);

        return $resource;
    }

    /**
     * @param $fileTypeGuesser
     */
    public function setFileTypeGuesser($fileTypeGuesser)
    {
        $this->fileTypeGuesser = $fileTypeGuesser;
    }

    /**
     * call the worker and guess the mimetype
     * @param string $filename
     * @return string
     */
    public function guessFileType($filename)
    {

        $class = $this->fileTypeGuesser;
        return $class::guessByFileName($filename);
    }

    /**
     * @return RackspaceApi
     */
    public function getRackspaceService()
    {
        return $this->rackspaceService;
    }

}

 
