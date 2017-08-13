<?php
/**
 * @license see LICENSE
 */

namespace Serps\SearchEngine\Yahoo\Page;

use Serps\Core\Captcha\CaptchaResponse;
use Serps\Exception;
use Serps\SearchEngine\Yahoo\Exception\InvalidDOMException;
use Serps\SearchEngine\Yahoo\Page\YahooError;

class YahooCaptcha implements CaptchaResponse
{


    /**
     * @var GoogleError
     */
    protected $yahooError;

    /**
     * GoogleCaptcha constructor.
     * @param GoogleError $yahooError
     */
    public function __construct(YahooError $yahooError)
    {
        $this->yahooError = $yahooError;
    }

    /**
     * @return yahooError
     */
    public function getErrorPage()
    {
        return $this->yahooError;
    }

    /**
     * Gets the url of the image. Be aware that each call to this method will regenerate the captcha image
     * and the previous generated image will be invalided
     *
     * @return mixed
     * @throws Exception
     */
    public function getImageUrl()
    {
        $imageTag = $this->yahooError->cssQuery('img');

        if ($imageTag->length !== 1) {
            throw new InvalidDOMException('Unable to find the captcha image.');
        }

        $src =  $imageTag->item(0)->getAttribute('src');
        $d = $this->yahooError->getUrl()->resolve($src);
        return $d->buildUrl();
    }

    public function getCaptchaType()
    {
        return self::CAPTCHA_TYPE_IMAGE;
    }

    /**
     * Gets the captcha image. Be aware that each call to this method will regenerate the captcha image
     * and the previous generated image will be invalid
     * @return string
     * @throws Exception
     */
    public function getData()
    {
        $imageUrl = $this->getImageUrl();
        return file_get_contents($imageUrl);
    }

    /**
     * The captcha resolution id to send with the form to solve the captcha
     * @return mixed
     * @throws Exception
     */
    public function getId()
    {
        $inputTag = $this->yahooError->cssQuery('input[name="q"]');
        if ($inputTag->length == 0) {
            throw new InvalidDOMException('Unable to find the captcha id.');
        }
        $id = $inputTag->item(0)->getAttribute('value');
        return $id;
    }

    public function getDetectedIp()
    {

        $ipV4 = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';
        $ipV6 = '(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))';

        $regexp = "/($ipV4|$ipV6)/";
        $hasMatch = preg_match($regexp, $this->yahooError->getDom()->C14N(), $match);
        if ($hasMatch) {
            return $match[1];
        } else {
            return null;
        }
    }
}
