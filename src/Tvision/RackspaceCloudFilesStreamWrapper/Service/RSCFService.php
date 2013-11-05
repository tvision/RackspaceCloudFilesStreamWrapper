<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Service;

use OpenCloud\ObjectStore\Resource\AbstractResource;
use Tvision\RackspaceCloudFilesStreamWrapper\Model\FileTypeGuesserInterface;
use Tvision\RackspaceCloudFilesStreamWrapper\Service\RackspaceApi;
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\ObjectStore\Resource\DataObject;
use Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesServiceInterface;

/**
 * Description of RackSpaceObject
 *
 * @author liuggio
 * @author Chris Warner <cdw.lighting@gmail.com>
 */
class RSCFService implements RackspaceCloudFilesServiceInterface
{
    /**
     * @var RackspaceApi
     */
    private $rackspaceService;

    /**
     * @var string
     */
    private $protocolName;

    /**
     * @var string
     */
    private $resource_class;

    /**
     * @var string
     */
    private $streamWrapperClass;

    /**
     * @var FileTypeGuesserInterface
     */
    private $file_type_guesser;

    /**
     * @param                          $protocol_name
     * @param RackspaceApi             $rackspaceService
     * @param                          $stream_wrapper_class
     * @param                          $resource_entity_class
     * @param FileTypeGuesserInterface $file_type_guesser
     */
    public function __construct(
        $protocol_name, RackspaceApi $rackspaceService, $stream_wrapper_class, $resource_entity_class,
        FileTypeGuesserInterface $file_type_guesser = null
    ) {
        $this->protocolName       = $protocol_name;
        $this->rackspaceService   = $rackspaceService;
        $this->streamWrapperClass = $stream_wrapper_class;
        $this->resource_class     = $resource_entity_class;
        if ($file_type_guesser) {
            $this->setFileTypeGuesser($file_type_guesser);
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
        return $this->resource_class;
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
     * @param  AbstractResource $resource
     * @return false|container
     */
    public function getContainerByResource($resource)
    {
        return $resource->getContainer();
    }

    /**
     *
     *
     * @param AbstractResource $resource
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
     * @param string $container_name
     *
     * @return Container|false
     */
    public function apiGetContainer($container_name)
    {
        $container = $this->getRackspaceService()->getContainer($container_name);
        if (!$container) {
            return false;
        }

        return $container;
    }


    /**
     * @param Container $container
     * @param           $objectData
     *
     * @return DataObject
     */
    public function apiGetObjectByContainer(Container $container, $objectData)
    {
        if (!$container) {
            return false;
        }

        $object = $container->dataObject();
        $object->setName($objectData['name']);
        $object->content_type = $objectData['content_type'];

        return $object;
    }

    /**
     *
     * @param string $path
     *
     * @return AbstractResource|false
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
        $objectData = array (
            'name'         => $resource->getResourceName(),
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
     * @param FileTypeGuesserInterface $file_type_guesser
     */
    public function setFileTypeGuesser(FileTypeGuesserInterface $file_type_guesser)
    {
        $this->file_type_guesser = $file_type_guesser;
    }

    /**
     * call the worker and guess the mimetype
     * @param string $filename
     * @return string
     */
    public function guessFileType($filename)
    {

        $function = $this->file_type_guesser;

        return $function::guessByFileName($filename);
    }

    /**
     * @return RackspaceApi
     */
    public function getRackspaceService()
    {
        return $this->rackspaceService;
    }
}

 
