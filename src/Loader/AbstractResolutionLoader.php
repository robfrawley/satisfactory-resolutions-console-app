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

use Satisfactory\Model\Resolution;
use Satisfactory\Model\ResolutionCollection;

/**
 * Class AbstractResolutionLoader
 * @package Satisfactory\Loader
 */
abstract class AbstractResolutionLoader
{
    /**
     * @var \SplFileObject
     */
    protected \SplFileObject $file;

    /**
     * @param \SplFileObject $file
     */
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    abstract protected function readType(): array;

    /**
     * @return ResolutionCollection
     */
    public function load(): ResolutionCollection {
        return ResolutionCollection::create(...$this->readType());
    }

    /**
     * @return string
     */
    protected function readFile(): string {
        try {
            return $this->file->fread($this->file->getSize());
        } catch (\Exception $e) {
            throw new \RuntimeException(
                sprintf('Failed to read contents from "%s" (%s).', $this->file->getRealPath(), $e->getMessage()), $e->getCode(), $e
            );
        }
    }
}