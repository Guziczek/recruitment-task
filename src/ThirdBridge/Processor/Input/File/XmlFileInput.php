<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Input\File;

use Bajcik\Util\BooleanUtils;
use Symfony\Component\DomCrawler\Crawler;
use ThirdBridge\Domain\User;
use ThirdBridge\Domain\UserValue;

class XmlFileInput extends FileInput
{
    public function getIterableUsers()
    {
        $xml = new Crawler($this->getFile()->getContents());
        $userXmlList = $xml->filterXPath('//users/user')->each(function (Crawler $userXml, $i) {
            return $userXml;
        });

        foreach ($userXmlList as $userXml) {
            // TODO should be proper mapper for this
            $user = $this->mapValues($userXml);
            yield $user;
        }
    }

    private function mapValues(Crawler $userXml): User
    {
        // TODO data validation

        $name = $userXml->filterXPath('//name')->text();
        $active = BooleanUtils::isTrue($userXml->filterXPath('//active')->text());
        $value = UserValue::ofString($userXml->filterXPath('//value')->text());

        return new User($name, $active, $value);
    }

}