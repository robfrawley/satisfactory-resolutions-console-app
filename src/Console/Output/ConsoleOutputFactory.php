<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleOutputFactory
 */
class ConsoleOutputFactory
{
    /**
     * @var ConsoleOutput|OutputInterface|null
     */
    private static ?OutputInterface $instance = null;

    /**
     * @param ConsoleOutput|OutputInterface|null $o
     *
     * @return ConsoleOutput|OutputInterface
     */
    public static function create(OutputInterface $o = null): OutputInterface
    {
        return self::$instance = $o ?? self::$instance ?? new ConsoleOutput();
    }
}
