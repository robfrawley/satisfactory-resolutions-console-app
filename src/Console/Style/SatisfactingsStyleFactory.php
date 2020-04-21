<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Console\Style;

use Satisfactings\Console\Input\InputFactory;
use Satisfactings\Console\Output\OutputFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SatisfactingsStyleFactory
 */
class SatisfactingsStyleFactory
{
    public static function resolve(?InputInterface $i = null, ?OutputInterface $o = null): SatisfactingsStyle
    {
        return new SatisfactingsStyle(InputFactory::resolve($i), OutputFactory::resolve($o));
    }
}
