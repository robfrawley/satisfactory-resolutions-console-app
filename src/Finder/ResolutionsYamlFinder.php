<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Finder;

use Symfony\Component\Finder\Finder;

/**
 * Class ResolutionsYamlFinder
 */
class ResolutionsYamlFinder
{
    public function locate(): \SplFileObject
    {
        $finder = new Finder();
        $finder->in(self::getResourcesPath());
        $finder->name(self::getResolutionsResourcesYAMLFile());

        if (1 === $finder->count()) {
            foreach ($finder->files() as $f) {
                return new \SplFileObject($f->getRealPath());
            }
        }

        throw new \RuntimeException(sprintf('Failed to locate YAML file "%s" in "%s".', self::getResolutionsResourcesYAMLFile(), self::getResourcesPath()));
    }

    public static function getResolutionsResourcesYAMLFile(): string
    {
        return 'resolutions.yaml';
    }

    public static function getResourcesPath(): string
    {
        return sprintf('%s%s..%s..%s%s', __DIR__, \DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR, 'resources');
    }
}
