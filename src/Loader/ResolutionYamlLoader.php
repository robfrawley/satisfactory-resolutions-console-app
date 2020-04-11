<?php

/*
 * This file is part of the `robfrawley/satisfactory-settings-console-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactory\Loader;

use Symfony\Component\Yaml\Yaml;

/**
 * Class ResolutionYamlLoader
 * @package Satisfactory\Loader
 */
class ResolutionYamlLoader extends AbstractResolutionLoader
{
    /**
     * @return array[]
     */
    protected function readType(): array {
        return $this->readJSON() ?? [];
    }

    /**
     * @return array[]
     */
    private function readJSON() {
        try {
            return Yaml::parse($this->readFile());
        } catch (\Exception $e) {
            throw new \RuntimeException(
                sprintf('Failed to load JSON from "%s". Error message: %s', $this->file->getRealPath(), $e->getMessage()), $e->getCode(), $e
            );
        }
    }
}