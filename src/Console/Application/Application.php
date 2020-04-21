<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Console\Application;

use Satisfactings\Console\Command\ListResolutionsCommand;
use Satisfactings\Console\Style\SatisfactingsStyleFactory;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 */
class Application extends SymfonyApplication
{
    /**
     * @return static
     */
    public static function setup(): self
    {
        ($application = new self('satisfactings-application', '0.1.0'))->addCommands([
            new ListResolutionsCommand(),
        ]);

        return $application;
    }

    /**
     * @return int
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        try {
            return parent::run($input, $output);
        } catch (\Throwable $t) {
            SatisfactingsStyleFactory::resolve($input, $output)->compileError([
                'Failed to run satisfactings console application: %s', $t->getMessage(),
            ]);

            return 255;
        }
    }
}
