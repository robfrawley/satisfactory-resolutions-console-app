<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Console\Configuration;

use Satisfactings\Model\ResolutionCollection;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ResolutionListInputDefConfig
 */
class ResolutionListInputDefConfig implements CommandInputDefConfigInterface
{
    public static function buildDefinition(): InputDefinition
    {
        return new InputDefinition(array_map(function (array $parameters): InputOption {
            return self::instantiateDefinitionType(...$parameters);
        }, self::buildDefinitionInitInstructionSet()));
    }

    /**
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    private static function instantiateDefinitionType(string $type, ...$parameters)
    {
        if (false === \in_array($type, self::TYPES, true) || false === class_exists($type)) {
            throw new \RuntimeException(sprintf('Invalid input definition type "%s" defined in build instructions!', $type));
        }

        try {
            return new $type(...$parameters);
        } catch (\Throwable $t) {
            throw new \RuntimeException(sprintf('[CRITICAL] Invalid input definition type of "%s" provided.'.PHP_EOL, $type));
        }
    }

    /**
     * @return array[]
     */
    private static function buildDefinitionInitInstructionSet(): array
    {
        return [
            [
                InputOption::class,
                'filter-x',
                'x',
                InputOption::VALUE_REQUIRED,
                'Filter output resolutions by X coordinate against passed glob-enabled value.',
            ],
            [
                InputOption::class,
                'filter-y',
                'y',
                InputOption::VALUE_REQUIRED,
                'Filter output resolutions by Y coordinate glob.',
            ],
            [
                InputOption::class,
                'filter-ratio',
                'r',
                InputOption::VALUE_REQUIRED,
                'Filter output resolutions by X/Y coordinates ratio glob.',
            ],
            [
                InputOption::class,
                'order-by',
                'o',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                sprintf('Order output resolutions by one of the following: %s.', ResolutionCollection::stringifyOrderTypes()),
                [ResolutionCollection::ORDER_TYPE_X_ASC],
            ],
        ];
    }
}
