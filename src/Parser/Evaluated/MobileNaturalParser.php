<?php
/**
 * @license see LICENSE
 */

namespace Serps\SearchEngine\Google\Parser\Evaluated;

use Serps\SearchEngine\Google\Page\GoogleDom;
use Serps\SearchEngine\Google\Parser\AbstractParser;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\AnswerBox;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\Classical\ClassicalResult;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\Divider;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\Flight;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\ImageGroup;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\ImageGroupCarousel;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\InTheNews;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\Classical\LargeClassicalResult;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\KnowledgeCard;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\Map;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\Classical\ClassicalCardsResult;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\SearchResultGroup;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\TweetsCarousel;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\Classical\ClassicalWithLargeVideo;
use Serps\SearchEngine\Google\Parser\Evaluated\Rule\Natural\VideoGroup;

/**
 * Parses natural results from a mobile google SERP
 */
class MobileNaturalParser extends AbstractParser
{

    /**
     * @inheritdoc
     */
    protected function generateRules()
    {
        return [
            new Divider(),
            new SearchResultGroup(),
            new ClassicalCardsResult(),
            new ImageGroupCarousel(),
            new VideoGroup(),
            new ImageGroup(),
            new KnowledgeCard()
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getParsableItems(GoogleDom $googleDom)
    {
        $xpathObject = $googleDom->getXpath();
        $xpathElementGroups = "//div[@id = 'ires']/*[@id = 'rso']/*";
        return $xpathObject->query($xpathElementGroups);
    }
}
