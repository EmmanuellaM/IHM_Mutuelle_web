<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 29/12/18
 * Time: 20:19
 */

namespace app\managers;

class SettingManager
{
    /**
     * Retrieve the "interest" value from the app.json file.
     */
    public static function getInterest()
    {
        return self::getJsonValue('app.json', 'interest');
    }

    /**
     * Retrieve the "social_crown" value from the app.json file.
     */
    public static function getSocialCrown()
    {
        return self::getJsonValue('app.json', 'social_crown');
    }

    /**
     * Retrieve the "inscription" value from the app.json file.
     */
    public static function getInscription()
    {
        return self::getJsonValue('app.json', 'inscription');
    }

    /**
     * Retrieve the "amount" value from the app2.json file.
     */
    public static function getAgape()
    {
        return self::getJsonValue('app2.json', 'amount');
    }

    /**
     * Update the app.json file with new values for interest, social_crown, and inscription.
     */
    public static function setValues($interest, $social_crown, $inscription)
    {
        $data = [
            'interest' => $interest,
            'social_crown' => $social_crown,
            'inscription' => $inscription,
        ];

        self::writeJsonData('app.json', $data);
    }

    /**
     * Update the app2.json file with a new amount value.
     */
    public static function setValuesAgape($amount)
    {
        $data = [
            'amount' => $amount,
        ];

        self::writeJsonData('app2.json', $data);
    }

    /**
     * Retrieve a value from a JSON file.
     */
    private static function getJsonValue($fileName, $key)
    {
        $filePath = \Yii::$app->getBasePath() . '/managers/' . $fileName;

        if (!file_exists($filePath)) {
            throw new \Exception("File not found: $filePath");
        }

        $json_source = file_get_contents($filePath);
        $data = json_decode($json_source, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON from file: $filePath");
        }

        if (!array_key_exists($key, $data)) {
            throw new \Exception("Key '$key' not found in file: $filePath");
        }

        return $data[$key];
    }

    /**
     * Write data to a JSON file.
     */
    private static function writeJsonData($fileName, $data)
    {
        $filePath = \Yii::$app->getBasePath() . '/managers/' . $fileName;

        $json_data = json_encode($data, JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error encoding JSON data for file: $filePath");
        }

        if (file_put_contents($filePath, $json_data) === false) {
            throw new \Exception("Failed to write to file: $filePath");
        }
    }
}
