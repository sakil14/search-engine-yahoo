<?php
/**
 * @license see LICENSE
 */

namespace Serps\SearchEngine\Yahoo\Page;

use Serps\Core\Dom\WebPage;
use Serps\SearchEngine\Yahoo\Page\YahooDom;

class YahooError extends WebPage
{

    /**
     * @return bool Check if the page is a captcha
     */
    public function isCaptcha()
    {
        $captchaQuery = "//input[@name='captcha']";
        return $this->getXpath()->query($captchaQuery)->length > 0;
    }
}
