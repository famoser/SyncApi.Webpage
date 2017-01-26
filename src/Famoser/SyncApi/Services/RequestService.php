<?php
/**
 * Created by PhpStorm.
 * User: famoser
 * Date: 14.11.2016
 * Time: 12:39
 */

namespace Famoser\SyncApi\Services;


use Famoser\SyncApi\Framework\Json\Models\ObjectProperty;
use Famoser\SyncApi\Framework\Json\SimpleJsonMapper;
use Famoser\SyncApi\Interfaces\JsonDeserializableInterface;
use Famoser\SyncApi\Models\Communication\Request\AuthorizationRequest;
use Famoser\SyncApi\Models\Communication\Request\CollectionEntityRequest;
use Famoser\SyncApi\Models\Communication\Request\HistoryEntityRequest;
use Famoser\SyncApi\Models\Communication\Request\SyncEntityRequest;
use Famoser\SyncApi\Services\Base\BaseService;
use Famoser\SyncApi\Services\Interfaces\RequestServiceInterface;
use Slim\Http\Request;

/**
 * the request service parses & validates requests
 *
 * @package Famoser\SyncApi\Services
 */
class RequestService extends BaseService implements RequestServiceInterface
{
    /**
     * @param Request $request
     * @return AuthorizationRequest
     * @throws \JsonMapper_Exception
     */
    public function parseAuthorizationRequest(Request $request)
    {
        return $this->executeJsonMapper($request, new AuthorizationRequest());
    }

    /**
     * @param Request $request
     * @return CollectionEntityRequest
     * @throws \JsonMapper_Exception
     */
    public function parseCollectionEntityRequest(Request $request)
    {
        return $this->executeJsonMapper($request, new CollectionEntityRequest());
    }

    /**
     * @param Request $request
     * @return HistoryEntityRequest
     * @throws \JsonMapper_Exception
     */
    public function parseHistoryEntityRequest(Request $request)
    {
        return $this->executeJsonMapper($request, new HistoryEntityRequest());
    }

    /**
     * @param Request $request
     * @return SyncEntityRequest
     * @throws \JsonMapper_Exception
     */
    public function parseSyncEntityRequest(Request $request)
    {
        return $this->executeJsonMapper($request, new SyncEntityRequest());
    }

    /**
     * @param Request $request
     * @param $model
     * @return mixed
     * @throws \JsonMapper_Exception
     */
    private function executeJsonMapper(Request $request, JsonDeserializableInterface $model)
    {
        $json = $request->getBody()->getContents();
        $mapper = new SimpleJsonMapper();
        $objProp = new ObjectProperty('root', $model);
        $resObj = $mapper->mapObject($json, $objProp);
        $this->getLoggingService()->log(json_encode($resObj, JSON_PRETTY_PRINT), 'RequestHelper.txt');
        return $resObj;
    }

    /**
     * @param string $authCode
     * @param int $applicationSeed
     * @param int $personSeed
     * @param int $apiModulo
     * @param int $requestCount
     * @param int $requestMagicNumber
     * @return bool
     */
    public function isAuthenticationCodeValid($authCode, $applicationSeed, $personSeed, $apiModulo, $requestCount, $requestMagicNumber)
    {
        /* C#:
            var authCode = apiRoamingEntity.PersonalSeed * apiRoamingEntity.RequestCount + requestMagicNumber * info.ApplicationSeed;
            return (authCode % info.ApiModulo).ToString();

           php:
            $expectedAuthCode = $personSeed * $requestCount + $requestMagicNumber * $applicationSeed;
            return ($expectedAuthCode % $apiModulo) == $authCode;
        */
        //to come in future version
        return true;
    }
}