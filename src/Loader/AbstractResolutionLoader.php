<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Loader;

use Satisfactings\Model\ResolutionCollection;

/**
 * Class AbstractResolutionLoader
 */
abstract class AbstractResolutionLoader
{
    protected \SplFileObject $file;

    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function load(): ResolutionCollection
    {
        return ResolutionCollection::create(...$this->readType());
    }

    abstract protected function readType(): array;

    protected function readFile(): string
    {
        try {
            return $this->file->fread($this->file->getSize());
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Failed to read contents from "%s" (%s).', $this->file->getRealPath(), $e->getMessage()), $e->getCode(), $e);
        }
    }
}
