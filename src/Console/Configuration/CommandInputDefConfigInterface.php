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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * Interface CommandInputDefConfigInterface
 */
interface CommandInputDefConfigInterface
{
    /**
     * @var string
     */
    public const TYPE_OPT = InputOption::class;

    /**
     * @var string
     */
    public const TYPE_ARG = InputArgument::class;

    /**
     * @var string[]
     */
    public const TYPES = [
        self::TYPE_OPT,
        self::TYPE_ARG,
    ];

    public static function buildDefinition(): InputDefinition;
}
