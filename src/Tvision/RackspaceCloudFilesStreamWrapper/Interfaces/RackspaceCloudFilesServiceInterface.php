<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Interfaces;

use \OpenCloud\ObjectStore\Resource\Container;

/**
 * Description of RackspaceCloudFilesServiceInterface
 *
 * @author liuggio
 */
interface RackspaceCloudFilesServiceInterface
{
    /**
     * @param string $containerName
     * @return \stdClass
     *
     * @api
     */
    public function apiGetContainer($containerName);

    /**
     * @param Container $container
     * @param $objectData
     * @return mixed
     *
     * @api
     */
    public function apiGetObjectByContainer(Container $container, $objectData);

    /**
     * @param string $path
     * @return resource|false
     *
     * @api
     */
    public function createResourceFromPath($path);

    /**
     * @param type $resource
     * @return false|container
     *
     * @api
     */
    public function getContainerByResource($resource);

    /**
     * @param $resource
     * @return false|object
     *
     * @api
     */
    public function getObjectByResource($resource);

    /**
     * try to guess the mimetype from a filename
     *
     * @param $filename
     * @return string
     *
     * @api
     */
    public function guessFileType($filename);
}
