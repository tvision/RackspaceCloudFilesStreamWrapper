<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Interfaces;

use OpenCloud\ObjectStore\Resource\Container;
use Tvision\RackspaceCloudFilesStreamWrapper\Model\RackspaceCloudFilesResource;

/**
 * Description of RackspaceCloudFilesServiceInterface
 *
 * @author liuggio
 */
interface RackspaceCloudFilesServiceInterface
{
    /**
     * @param string $containerName
     * @return Container
     *
     * @api
     */
    public function getContainer($containerName);

    /**
     * @param Container $container
     * @param $objectData
     * @return mixed
     *
     * @api
     */
    public function getObjectByContainer(Container $container, array $objectData);

    /**
     * @param string $path
     * @return RackspaceCloudFilesResource|false
     *
     * @api
     */
    public function createResourceFromPath($path);

    /**
     * @param RackspaceCloudFilesResource $resource
     * @return false|container
     *
     * @api
     */
    public function getContainerByResource(RackspaceCloudFilesResource $resource);

    /**
     * @param RackspaceCloudFilesResource $resource
     * @return false|object
     *
     * @api
     */
    public function getObjectByResource(RackspaceCloudFilesResource $resource);

    /**
     * try to guess the mimetype from a filename
     *
     * @param $filename
     * @return string
     *
     * @api
     */
    public function guessFileType($filename);

    /**
     * set your own file type guesser
     *
     * @param FileTypeGuesserInterface $fileTypeGuesser
     *
     * @api
     */
    public function setFileTypeGuesser(FileTypeGuesserInterface $fileTypeGuesser);
}
