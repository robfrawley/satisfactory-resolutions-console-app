<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Console\Input;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class InputFactory
 */
class InputFactory
{
    public static function resolve(InputInterface $i = null): InputInterface
    {
        return ArgvInputFactory::create($i);
    }
}
