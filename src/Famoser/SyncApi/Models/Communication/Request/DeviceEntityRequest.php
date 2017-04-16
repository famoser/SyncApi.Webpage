<?php
/**
 * Created by PhpStorm.
 * User: famoser
 * Date: 04.11.2016
 * Time: 19:17
 */

namespace Famoser\SyncApi\Models\Communication\Request;

use Famoser\SyncApi\Framework\Json\Models\ArrayProperty;
use Famoser\SyncApi\Framework\Json\Models\Base\AbstractJsonProperty;
use Famoser\SyncApi\Framework\Json\Models\Base\AbstractJsonValueProperty;
use Famoser\SyncApi\Framework\Json\Models\ObjectProperty;
use Famoser\SyncApi\Models\Communication\Entities\DeviceCommunicationEntity;
use Famoser\SyncApi\Models\Communication\Entities\SyncCommunicationEntity;
use Famoser\SyncApi\Models\Communication\Request\Base\BaseRequest;

/**
 * the entities to be synced
 * @package Famoser\SyncApi\Models\Communication\Request
 */
class DeviceEntityRequest extends BaseRequest
{
    /* @var DeviceCommunicationEntity[] $DeviceEntities */
    public $DeviceEntities;

    /**
     * gets the json properties needed to deserialize
     *
     * @return AbstractJsonValueProperty[]
     */
    public function getJsonProperties()
    {
        $arr = parent::getJsonProperties();
        $arr['DeviceEntities'] = new ArrayProperty(
            'DeviceEntities',
            new ObjectProperty('DeviceEntities', new DeviceCommunicationEntity())
        );
        return $arr;
    }
}
