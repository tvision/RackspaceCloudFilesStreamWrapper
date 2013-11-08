<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Service;

use OpenCloud\ObjectStore\Resource\Container;
use Tvision\RackspaceCloudFilesStreamWrapper\Interfaces\FileTypeGuesserInterface;
use Tvision\RackspaceCloudFilesStreamWrapper\Interfaces\RackspaceCloudFilesServiceInterface;

/**
 * Class RSCFService
 * @package Tvision\RackspaceCloudFilesStreamWrapper\Service
 *
 * @author liuggio
 */
class RSCFService implements RackspaceCloudFilesServiceInterface
{
    /**
     * @var RackspaceApi $rackspaceService
     */
    private $rackspaceApi;

    /**
     * @var string $protocolName
     */
    private $protocolName;

    /**
     * @var string $resourceClass
     */
    private $resourceClass;

    /**
     * @var string $streamWrapperClass
     */
    private $streamWrapperClass;

    /**
     * @var FileTypeGuesserInterface $fileTypeGuesser
     */
    private $fileTypeGuesser;

    /**
     * @param string $protocolName
     * @param RackspaceApi $rackspaceApi
     * @param string $streamWrapperClass
     * @param string $resourceEntityClass
     * @param FileTypeGuesserInterface $fileTypeGuesser
     */
    public function __construct($protocolName,
                                RackspaceApi $rackspaceApi,
                                $streamWrapperClass,
                                $resourceEntityClass,
                                FileTypeGuesserInterface $fileTypeGuesser)
    {
        $this->setProtocolName($protocolName);
        $this->setRackspaceApi($rackspaceApi);
        $this->setStreamWrapperClass($streamWrapperClass);
        $this->setResourceClass($resourceEntityClass);

        if ($fileTypeGuesser) {
            $this->setFileTypeGuesser($fileTypeGuesser);
        }
    }

    /**
     * @param $streamWrapperClass
     * @return $this
     */
    private function setStreamWrapperClass($streamWrapperClass)
    {
        $this->streamWrapperClass = $streamWrapperClass;
        return $this;
    }

    /**
     * @param RackspaceApi $rackspaceApi
     * @return $this
     */
    private function setRackspaceApi(RackspaceApi $rackspaceApi)
    {
        $this->rackspaceApi = $rackspaceApi;
        return $this;
    }

    /**
     * @param $resourceClass
     * @return $this
     */
    private function setResourceClass($resourceClass)
    {
        $this->resourceClass = $resourceClass;
        return $this;
    }

    /**
     * @param $protocolName
     * @return $this
     */
    private function setProtocolName($protocolName)
    {
        $this->protocolName = $protocolName;
        return $this;
    }

    /**
     * @return string
     */
    private function getResourceClass()
    {
        return $this->resourceClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerByResource($resource)
    {
        return $resource->getContainer();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function apiGetContainer($containerName)
    {
        $container = $this->getRackspaceApi()
            ->getContainer($containerName);
        if (!$container) {
            return false;
        }
        return $container;
    }


    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function createResourceFromPath($path)
    {
        $container = $this->getRackspaceApi()->getContainer();
        $resource  = $this->getResourceClass();
        $resource  = new $resource($path);
        if (!$resource) {
            return false;
        }

        //create_object but no problem if already exists
        $objectData = array(
            'name'         => $resource->getResourceName(),
            'content_type' => $this->guessFileType($path),
        );

        $obj = $this->apiGetObjectByContainer($container, $objectData, $path);
        if (!$obj) {
            return false;
        }
        $resource->setObject($obj);
        $resource->setContainer($container);

        return $resource;
    }

    /**
     * @param FileTypeGuesserInterface $fileTypeGuesser
     * @return $this
     */
    private function setFileTypeGuesser(FileTypeGuesserInterface $fileTypeGuesser)
    {
        $this->fileTypeGuesser = $fileTypeGuesser;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function guessFileType($filename)
    {

        $class = $this->fileTypeGuesser;
        return $class::guessByFileName($filename);
    }

    /**
     * @return RackspaceApi
     */
    private function getRackspaceApi()
    {
        return $this->rackspaceApi;
    }
}