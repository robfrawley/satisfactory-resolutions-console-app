<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Utility;

/**
 * Trait NotConstructableUtilityTrait
 */
trait NotConstructableUtilityTrait
{
    public function __construct()
    {
        throw new \RuntimeException(sprintf('Object "%s" cannot be constructed; it likely provides constants or static methods for its API.', static::class));
    }
}
