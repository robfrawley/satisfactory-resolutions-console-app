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

use Satisfactory\Finder\ResolutionsYamlFinder;
use Satisfactory\Loader\ResolutionYamlLoader;
use Satisfactory\Model\Resolution;
use Satisfactory\Model\ResolutionCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ListResolutionsCommand
 * @package Satisfactory\Component\Console
 */
class ListResolutionsCommand extends Command
{


    /**
     * @var string
     */
    protected static $defaultName = 'res:list';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption(
            'filter-x',
            'x',
            InputOption::VALUE_REQUIRED,
            'Filter output resolutions by X coordinate glob.'
        );

        $this->addOption(
            'filter-y',
            'y',
            InputOption::VALUE_REQUIRED,
            'Filter output resolutions by Y coordinate glob.'
        );

        $this->addOption(
            'filter-ratio',
            'r',
            InputOption::VALUE_REQUIRED,
            'Filter output resolutions by X/Y coordinates ratio glob.'
        );

        $this->addOption(
            'order-by',
            'o',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            sprintf('Order output resolutions by one of the following: %s.', join(', ', array_map(function (string $type): string {
                return sprintf('"%s"', $type);
            }, ResolutionCollection::ORDER_TYPES))),
            ['x:asc']
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $styler = new SymfonyStyle($input, $output);
        $loader = new ResolutionYamlLoader((new ResolutionsYamlFinder())->locate());
        $resSet = $loader->load()
            ->filterX($optFilterX = $input->getOption('filter-x'))
            ->filterY($optFilterY = $input->getOption('filter-y'))
            ->filterR($optFilterR = $input->getOption('filter-ratio'))
            ->orderBy(...($optOrderBy = $input->getOption('order-by')));

        $styler->section(strtoupper('Satisfactory Resolution Listing'));
        $styler->success(sprintf(
            'Found %d matches for filters (filter-x: "%s", filter-y: "%s", filter-ratio: "%s", order-by: "%s").',
            count($resSet),
            $optFilterX ?? '*',
            $optFilterY ?? '*',
            $optFilterR ?? '*',
            join('/', $optOrderBy)
        ));

        if (!count($resSet)) {
            $styler->error('No results found for passed filters!');

            return 255;
        }

        $styler->table(
            ['X Coordinate', 'Y Coordinate', 'X/Y Ratio'],
            $resSet->map(function (Resolution $r): array {
                return [
                    $r->getX(),
                    $r->getY(),
                    round($r->getR(), 4),
                ];
            })
        );

        return 0;
    }
}