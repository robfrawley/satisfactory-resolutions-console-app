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

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OutputFactory
 */
class OutputFactory
{
    public static function resolve(OutputInterface $o = null): OutputInterface
    {
        return ConsoleOutputFactory::create($o);
    }
}
