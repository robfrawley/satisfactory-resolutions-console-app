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

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ArgvInputFactory
 */
class ArgvInputFactory
{
    /**
     * @var ArgvInput|InputInterface|null
     */
    private static ?InputInterface $instance = null;

    /**
     * @param ArgvInput|InputInterface|null $i
     *
     * @return ArgvInput|InputInterface
     */
    public static function create(InputInterface $i = null): InputInterface
    {
        return self::$instance = $o ?? self::$instance ?? new ArgvInput();
    }
}
