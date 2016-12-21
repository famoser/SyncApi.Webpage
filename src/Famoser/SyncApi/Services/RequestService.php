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
     * @param $authCode
     * @param $applicationSeed
     * @param $personSeed
     * @param int $modulo
     * @return bool
     */
    public function isAuthenticationCodeValid($authCode, $applicationSeed, $personSeed, $modulo = 10000019)
    {
        //return true if $applicationSeed is 0 (= not configured)
        if ($applicationSeed == 0) {
            return true;
        }

        $content = explode('_', $authCode);
        //parse time from $content[0]
        $chunks = chunk_split($content[0], 2);
        if (count($chunks) != 4) {
            return false;
        }
        //check if time is valid
        $time = strtotime('today + ' . $chunks[0] . ' seconds ' . $chunks[1] . ' minutes ' . $chunks[2] . ' hours');
        $older = new \DateTime('+ 1 minute');
        $newer = new \DateTime('- 1 minute');
        if ($time < $newer && $time > $older) {
            //construct magic number (the same is done in c#)
            $baseNr = $chunks[0] + $chunks[1] * 100 + $chunks[2] * 10000 + $chunks[3];
            $expectedAuthCode = $baseNr * $applicationSeed * $personSeed;
            $expectedAuthCode %= $this->getModulo();
            return $content[1] == $expectedAuthCode;
        }
        return false;
    }
}