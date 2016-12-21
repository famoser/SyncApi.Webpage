<?php
/**
 * Created by PhpStorm.
 * User: Florian Moser
 * Date: 05.11.2016
 * Time: 20:21
 */

namespace Famoser\SyncApi\Repositories;

/*
    const AuthorizationCodeValidTime = 0;
    const DeviceAuthenticationRequired = 1;
    const AuthorizationCodeLength = 2;
*/

use Famoser\SyncApi\Models\Display\SettingModel;
use Famoser\SyncApi\Models\Entities\ApplicationSetting;
use Famoser\SyncApi\Services\Interfaces\DatabaseServiceInterface;
use Famoser\SyncApi\Types\SettingKeys;

/**
 * manages the settings of an application
 *
 * @package Famoser\SyncApi\Repositories
 */
class SettingsRepository
{
    private $helper;
    private $applicationId;

    /**
     * SettingsRepository constructor.
     *
     * @param DatabaseServiceInterface $helper
     * @param $applicationId
     */
    public function __construct(DatabaseServiceInterface $helper, $applicationId)
    {
        $this->helper = $helper;
        $this->applicationId = $applicationId;
    }

    private $isInitialized;
    /* @var ApplicationSetting[] */
    private $dic;

    /**
     * initialize the settings
     */
    private function ensureInitialized()
    {
        if ($this->isInitialized) {
            return;
        }

        $settings = $this->helper->getFromDatabase(
            new ApplicationSetting(),
            'application_id = :application_id',
            ['application_id' => $this->applicationId]
        );
        $this->dic = [];
        foreach ($settings as $setting) {
            $this->dic[$setting->key] = $setting;
        }
        $this->isInitialized = true;
    }

    /**
     * get the value from the database or create a new entry and use default
     *
     * @param $key
     * @return string
     */
    private function getOrCreateValue($key)
    {
        $this->ensureInitialized();
        if (isset($this->dic[$key])) {
            return $this->dic[$key]->val;
        }
        $this->persistNewSetting($key);
        return $this->dic[$key]->val;
    }

    /**
     * sets the value to an existing entity or creates a new one
     *
     * @param string $key
     * @param $val
     */
    private function setOrCreateValue($key, $val)
    {
        $this->ensureInitialized();
        //check if valid value, else simply ignore
        if (SettingKeys::isValidValue($key, $val)) {
            if (key_exists($key, $this->dic)) {
                $this->dic[$key]->val = $val;
                $this->helper->saveToDatabase($this->dic[$key]);
            } else {
                $this->persistNewSetting($key, $val);
            }
        }
    }

    /**
     * save a new setting to the database & SettingsRepository cache
     *
     * @param $key
     * @param $val
     */
    private function persistNewSetting($key, $val = null)
    {
        $setting = new ApplicationSetting();
        $setting->application_id = $this->applicationId;
        $setting->key = $key;
        if ($val == null) {
            $setting->val = SettingKeys::getDefaultValue($key);
        } else {
            $setting->val = $val;
        }
        $this->helper->saveToDatabase($setting);
        $this->dic[$setting->key] = $setting;
    }

    /**
     * get the authentication code valid time in seconds
     *
     * @return string
     */
    public function getAuthorizationCodeValidTime()
    {
        return $this->getOrCreateValue(SettingKeys::AUTHORIZATION_CODE_VALID_TIME);
    }

    /**
     * get a boolean if the device needs to be authenticated before accessing a resource from the user
     *
     * @return bool
     */
    public function getDeviceAuthenticationRequired()
    {
        return $this->getOrCreateValue(SettingKeys::DEVICE_AUTHENTICATION_REQUIRED) == 'true';
    }

    /**
     * get the authorization code length
     *
     * @return string
     */
    public function getAuthorizationCodeLength()
    {
        return $this->getOrCreateValue(SettingKeys::AUTHORIZATION_CODE_LENGTH);
    }

    /**
     * create setting models for display
     *
     * @return SettingModel[]
     */
    public function getAllSettings()
    {
        $res = [];

        $res[] = $this->createSettingModel(SettingKeys::AUTHORIZATION_CODE_VALID_TIME);
        $res[] = $this->createSettingModel(SettingKeys::DEVICE_AUTHENTICATION_REQUIRED);
        $res[] = $this->createSettingModel(SettingKeys::AUTHORIZATION_CODE_LENGTH);

        return $res;
    }

    /**
     * persist settings to database
     *
     * @param $keyValPairs
     */
    public function setSettings($keyValPairs)
    {
        foreach ($keyValPairs as $key => $val) {
            if (strrpos($key, 'setting_') === 0) {
                $intKey = substr($key, strlen('setting_'));
                $this->setOrCreateValue($intKey, $val);
            }
        }
    }

    /**
     * create a model ready for display to the user
     *
     * @param $key
     * @return SettingModel
     */
    private function createSettingModel($key)
    {
        $settingModel = new SettingModel();
        $settingModel->value = $this->getOrCreateValue($key);
        $settingModel->key = 'setting_' . $key;
        $settingModel->description = SettingKeys::getSettingDescription($key);
        return $settingModel;
    }
}
