<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Service;

use OpenCloud\Rackspace;
use OpenCloud\OpenStack;
use OpenCloud\ObjectStore;

/**
 * Class RackspaceApi
 * @package Tvision\RackspaceCloudFilesStreamWrapper\Service
 *
 * @author toretto460
 */
class RackspaceApi
{
    /**
     * @var OpenStack $connection
     */
    private static $connection;

    private $endpoint;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * @var string $connectionClass
     */
    private $connectionClass;

    /**
     * @var string $containerName
     */
    private $containerName;

    /**
     * @var string $serviceName
     */
    private $serviceName;

    /**
     * @var string $region
     */
    private $region;

    /**
     * @var string $urlType
     */
    private $urlType;

    public function __construct($connectionClass,
                                $endPoint,
                                $username,
                                $apiKey,
                                $containerName,
                                $region = 'LON')
    {
        $this->setConnectionClass($connectionClass);
        $this->setEndpoint($endPoint);
        $this->setUsername($username);
        $this->setApiKey($apiKey);
        $this->setContainerName($containerName);
        $this->SetRegion($region);
        $this->setServiceName('cloudFiles');
        $this->setUrlType('publicURL');
    }

    /**
     * Return the OpenStack object.
     *
     * @return OpenStack
     */
    private function connect()
    {
        if (!self::$connection) {
            $credential = array(
                'username' => $this->getUsername(),
                'apiKey'   => $this->getApiKey()
            );
            self::$connection = new Rackspace($this->endpoint, $credential);
        }
        return self::$connection;
    }

    /**
     * @return OpenStack
     */
    public function getConnection()
    {
        return $this->connect();
    }

    /**
     * Set the default connection values for cloudFiles service.
     *
     * @return void
     */
    private function setDefaults()
    {
        $this->getConnection()->setDefaults(
            'ObjectStore',
            $this->getServiceName(),
            $this->getRegion(),
            $this->getUrlType()
        );
    }

    /**
     * Get the ObjectStore.
     *
     * @return \OpenCloud\ObjectStore\Service
     * 
     * @api
     */
    public function getObjectStore()
    {
        $this->setDefaults();
        return $this->getConnection()->ObjectStore();
    }

    /**
     * Get the container.
     *
     * @param String|null $containerName
     *
     * @return \OpenCloud\ObjectStore\Resource\Container
     * 
     * @api
     */
    public function getContainer($containerName = null)
    {
        if (is_null($containerName)) {
            $containerName = $this->getContainerName();
        }
        return $this->getObjectStore()->Container($containerName);
    }

    /**
     * @param string $apiKey
     */
    private function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    private function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $connectionClass
     */
    private function setConnectionClass($connectionClass)
    {
        $this->connectionClass = $connectionClass;
    }

    /**
     * @return string
     */
    private function getConnectionClass()
    {
        return $this->connectionClass;
    }

    /**
     * @param string $containerName
     */
    private function setContainerName($containerName)
    {
        $this->containerName = $containerName;
    }

    /**
     * @return string
     */
    private function getContainerName()
    {
        return $this->containerName;
    }

    /**
     * @param mixed $endpoint
     */
    private function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return mixed
     */
    private function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $region
     */
    private function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    private function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $serviceName
     */
    private function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return string
     */
    private function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param string $urlType
     */
    private function setUrlType($urlType)
    {
        $this->urlType = $urlType;
    }

    /**
     * @return string
     */
    private function getUrlType()
    {
        return $this->urlType;
    }

    /**
     * @param string $username
     */
    private function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    private function getUsername()
    {
        return $this->username;
    }
}
