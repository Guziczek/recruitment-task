<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Input\File;

use Bajcik\Csv\CsvFileFormat;
use Bajcik\Csv\CsvUtils;
use Bajcik\Util\BooleanUtils;
use ThirdBridge\Domain\User;
use ThirdBridge\Domain\UserValue;

class CsvFileInput extends FileInput
{

    public function getIterableUsers()
    {
        $csvLines = CsvUtils::createLinesGeneratorFromFile($this->getFile(), CsvFileFormat::of(',', '""'), true, 1);
        foreach ($csvLines as $userCsv) {
            // TODO should be proper mapper for this
            $user = $this->mapValues($userCsv);
            yield $user;
        }
    }

    private function mapValues(array $userCsv): User
    {
        // TODO data validation
        $name = $userCsv[0];
        $active = BooleanUtils::isTrue($userCsv[1]);
        $value = UserValue::ofString($userCsv[2]);

        return new User($name, $active, $value);
    }

}