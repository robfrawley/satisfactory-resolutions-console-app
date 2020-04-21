<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Console\Command;

use Satisfactings\Console\Configuration\ResolutionListInputDefConfig;
use Satisfactings\Console\Style\SatisfactingsStyleFactory;
use Satisfactings\Finder\ResolutionsYamlFinder;
use Satisfactings\Loader\ResolutionYamlLoader;
use Satisfactings\Model\Resolution;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListResolutionsCommand
 */
class ListResolutionsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'resolution:list';

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $styler = SatisfactingsStyleFactory::resolve($input, $output);
        $valSet = (new ResolutionYamlLoader((new ResolutionsYamlFinder())->locate()))->load()
            ->filterX($optFilterX = $input->getOption('filter-x'))
            ->filterY($optFilterY = $input->getOption('filter-y'))
            ->filterR($optFilterR = $input->getOption('filter-ratio'))
            ->orderBy(...($optOrderBy = $input->getOption('order-by')));

        $styler->section(mb_strtoupper('Satisfactory Resolution Listing'));

        if (!\count($valSet)) {
            return $styler->errorExit('No results found for passed filters!', 255);
        }

        $styler->compileSuccess([
            'Found %d matches for filters (filter-x: "%s", filter-y: "%s", filter-ratio: "%s", order-by: "%s").',
            \count($valSet), $optFilterX ?? '*', $optFilterY ?? '*', $optFilterR ?? '*', implode('/', $optOrderBy),
        ]);

        $styler->table(
            ['X Coordinate', 'Y Coordinate', 'X/Y Ratio'],
            $valSet->map(function (Resolution $r): array {
                return [
                    $r->getX(),
                    $r->getY(),
                    round($r->getR(), 4),
                ];
            })
        );

        return 0;
    }

    protected function configure(): void
    {
        $this
            ->setDefinition(ResolutionListInputDefConfig::buildDefinition())
            ->setAliases(['r-list']);
    }
}
