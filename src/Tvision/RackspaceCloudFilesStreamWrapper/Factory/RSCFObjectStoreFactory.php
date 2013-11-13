<?php

/**
 * This file is part of the RackspaceCloudFilesStreamWrapper package.
 *
 * (c) Claudio D'Alicandro <claudio.dalicandro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tvision\RackspaceCloudFilesStreamWrapper\Factory;

use OpenCloud\OpenStack;
use OpenCloud\Rackspace;
use Tvision\RackspaceCloudFilesStreamWrapper\Exception\InvalidArgumentException;

class RSCFObjectStoreFactory implements RSCFObjectStoreFactoryInterface
{
    /**
     * @param array $credentials
     * @param string $endPoint
     * @param string $region
     * @param string $urlType
     * @throws \Tvision\RackspaceCloudFilesStreamWrapper\Exception\InvalidArgumentException
     * @return \OpenCloud\ObjectStore\Service
     */
    public static function newObjectStore(array $credentials,
                                        $endPoint = Rackspace::UK_IDENTITY_ENDPOINT,
                                        $region   = 'LON',
                                        $urlType  = 'publicURL')
    {
        if(isset($credentials['apiKey']) && isset($credentials['username'])){
            $openCloudConnection = new Rackspace($endPoint, $credentials);
        }
        elseif (isset($credentials['password']) && isset($credentials['username'])) {
            $openCloudConnection = new OpenStack($endPoint, $credentials);
        }
        else {
            throw new InvalidArgumentException(<<<MSG
                The credentials must always contain an 'username' and a 'password' or
                an 'apiKey' if you are using rackspace.
MSG
            );
        }
        return $openCloudConnection->objectStoreService('cloudFiles', $region, $urlType);
    }
}