<?php

/*
 * This file is part of the `robfrawley/satisfactory-settings-console-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactory\Component\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

/**
 * Class Application
 * @package Satisfactory\Component\Console
 */
class Application extends SymfonyApplication
{
    /**
     * @return static
     */
    public static function setup(): self
    {
        $a = new self('satisfactory-settings', '0.1.0');
        $a->addCommands([
            new ListResolutionsCommand(),
        ]);

        return $a;
    }
}