<?php
/**
 * Created by PhpStorm.
 * User: famoser
 * Date: 04.11.2016
 * Time: 19:10
 */

namespace Famoser\SyncApi\Models\Communication\Request\Base;

use Famoser\SyncApi\Framework\Json\Models\Base\AbstractJsonValueProperty;
use Famoser\SyncApi\Framework\Json\Models\TextProperty;
use Famoser\SyncApi\Interfaces\JsonDeserializableInterface;


/**
 * a base request
 * contains properties which every request may fill out with very few exceptions
 *
 * @package Famoser\SyncApi\Models\Communication\Request\Base
 */
class BaseRequest implements JsonDeserializableInterface
{
    /* @var string $UserId type_of:guid */
    public $UserId;

    /* @var string $DeviceId type_of:guid */
    public $DeviceId;

    /* @var string $AuthorizationCode */
    public $AuthorizationCode;

    /* @var string $ApplicationId */
    public $ApplicationId;

    /* @var string $Identifier */
    public $Identifier;

    /**
     * gets the json properties needed to deserialize
     *
     * @return AbstractJsonValueProperty[]
     */
    public function getJsonProperties()
    {
        $arr = [];
        $arr['UserId'] = new TextProperty('UserId');
        $arr['DeviceId'] = new TextProperty('DeviceId');
        $arr['AuthorizationCode'] = new TextProperty('AuthorizationCode');
        $arr['ApplicationId'] = new TextProperty('ApplicationId');
        $arr['Identifier'] = new TextProperty('Identifier');
        return $arr;
    }
}
