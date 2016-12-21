<?php
/**
 * Created by PhpStorm.
 * User: Florian Moser
 * Date: 05.11.2016
 * Time: 17:44
 */

namespace Famoser\SyncApi\Models\Entities;

/*
CREATE TABLE 'authorization_codes' (
  'id'                   INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
  'user_guid'            TEXT    DEFAULT NULL,
  'code'                 TEXT    DEFAULT NULL,
  'valid_till_date_time' TEXT    DEFAULT NULL
);
*/

use Famoser\SyncApi\Models\Entities\Base\BaseEntity;

/**
 * this represents an authorization code for a specific user
 * @package Famoser\SyncApi\Models\Entities
 */
class AuthorizationCode extends BaseEntity
{
    /* @var string $user_guid type_of:guid */
    public $user_guid;

    /* @var string $code */
    public $code;

    /* @var int $id type_of:timestamp*/
    public $valid_till_date_time;

    /**
     * get the name of the table from the database
     *
     * @return string
     */
    public function getTableName()
    {
        return 'authorization_codes';
    }
}
