<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Service;

use OpenCloud\ObjectStore\Service as ObjectStore;
use OpenCloud\ObjectStore\Resource\Container as OpenCloudContainer;
use Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesResource;
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
     * @var ObjectStore $objectStore
     */
    private $objectStore;

    /**
     * @var string $containerName
     */
    private $containerName;

    /**
     * @var FileTypeGuesserInterface $fileTypeGuesser
     */
    private $fileTypeGuesser;

    /**
     * @param ObjectStore $objectStore
     * @param string $containerName
     */
    public function __construct(ObjectStore $objectStore, $containerName)
    {
        $this->objectStore   = $objectStore;
        $this->containerName = $containerName;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerByResource(RackspaceCloudFilesResource $resource)
    {
        return $resource->getContainer();
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectByResource(RackspaceCloudFilesResource $resource)
    {
        if ($resource->getContainer()) {
            return $resource->getObject();
        }
        else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer($containerName)
    {
        if (!$container = $this->objectStore->getContainer($containerName)) {
            return false;
        }
        return $container;
    }


    /**
     * {@inheritdoc}
     */
    public function getObjectByContainer(OpenCloudContainer $container, array $objectData)
    {
        $object = $container->dataObject();
        $object->setName($objectData['name']);
        if(isset($objectData['content_type'])){
            $object->setContentType($objectData['content_type']);
        }
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function createResourceFromPath($path)
    {
        $container = $this->objectStore->getContainer(
            $this->containerName
        );

        $resource  = new RackspaceCloudFilesResource($path);

        //create_object but no problem if already exists
        $objectData = array(
            'name' => $resource->getResourceName(),
        );

        if($mimeType = $this->guessFileType($path)){
            $objectData['content_type'] = $mimeType;
        }

        if (!$obj = $this->getObjectByContainer($container, $objectData)) {
            return false;
        }

        $resource->setObject($obj);
        $resource->setContainer($container);

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function guessFileType($filename)
    {
        if(!is_null($this->fileTypeGuesser)){
            return $this->fileTypeGuesser->guessByFileName($filename);
        }
        return false;
    }

    /**
     * set your own file type guesser
     *
     * @param FileTypeGuesserInterface $fileTypeGuesser
     *
     * @api
     */
    public function setFileTypeGuesser(FileTypeGuesserInterface $fileTypeGuesser)
    {
        $this->fileTypeGuesser = $fileTypeGuesser;
    }
}
