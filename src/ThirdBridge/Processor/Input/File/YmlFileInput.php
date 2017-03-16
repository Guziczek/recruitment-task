<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Input\File;

use Bajcik\Util\BooleanUtils;
use Symfony\Component\Yaml\Yaml;
use ThirdBridge\Domain\User;
use ThirdBridge\Domain\UserValue;

class YmlFileInput extends FileInput
{

    public function getIterableUsers()
    {
        $yaml = Yaml::parse($this->getFile()->getContents());
        foreach ($yaml['users'] as $userYaml) {
            // TODO should be proper mapper for this
            $user = $this->mapValues($userYaml);
            yield $user;
        }
    }

    private function mapValues(array $userYaml): User
    {
        // TODO data validation
        $name = $userYaml['name'];
        $active = BooleanUtils::isTrue($userYaml['active']);
        $value = UserValue::of($userYaml['value']);

        return new User($name, $active, $value);
    }

}