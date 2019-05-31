<?php
/**
 * Created by PhpStorm.
 * User: zhangzhenwei
 * Date: 2019/5/31
 * Time: 11:35
 */

namespace Ritin\Weather;

use GuzzleHttp\Client;
use Ritin\Weather\Exceptions\HttpException;
use Ritin\Weather\Exceptions\InvalidArgumentException;

class Weather
{

    protected $key;
    protected $guzzleOptions = [];

    public function __construct($key)
    {

        $this->key = $key;
    }

    /**
     * getHttpClient
     *
     * @return Client
     * @author 张镇炜 <772979140@qq.com>
     */
    public function getHttpClient()
    {

        return new Client($this->guzzleOptions);
    }

    /**
     * setGuzzleOptions
     *
     * @param array $options
     * @author 张镇炜 <772979140@qq.com>
     */
    public function setGuzzleOptions(array $options)
    {

        $this->guzzleOptions = $options;
    }

    /**
     * getWeather
     *
     * @param        $city
     * @param string $type
     * @param string $format
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author 张镇炜 <772979140@qq.com>
     */
    public function getWeather($city, $type = 'base', $format = 'json')
    {

        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        if (!in_array(strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format);
        }

        if (!in_array(strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): ' . $type);
        }
        $query = array_filter([
            'key'        => $this->key,
            'city'       => $city,
            'output'     => $format,
            'extensions' => $type,
        ]);
        try {

            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return 'json' === $format ? json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getLiveWeather($city, $format = 'json')
    {

        return $this->getWeather($city, 'base', $format);
    }

    public function getForecastsWeather($city, $format = 'json')
    {

        return $this->getWeather($city, 'all', $format);
    }
}