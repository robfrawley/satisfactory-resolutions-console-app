<?php

/**
 * Class Resolution
 */
class Resolution {
    /**
     * @var int
     */
	private int $x;

    /**
     * @var int
     */
	private int $y;

    /**
     * @param int $x
     * @param int $y
     */
	public function __construct(int $x, int $y) {
		$this->x = $x;
		$this->y = $y;
	}

    /**
     * @return int
     */
	public function getX(): int {
		return $this->x;
	}

    /**
     * @return int
     */
	public function getY(): int {
		return $this->y;
	}

    /**
     * @return float
     */
	public function getRatio(): float {
		return $this->getX() / $this->getY();
	}
}

/**
 * Class ResolutionSet
 */
class ResolutionSet implements \Countable, \IteratorAggregate {
    /**
     * @var array|Resolution[]
     */
	private array $resolutions = [];

    /**
     * @param Resolution ...$resolutions
     */
	public function __construct(Resolution ...$resolutions) {
		$this->resolutions = $resolutions;
	}

    /**
     * @return int
     */
	public function count(): int {
		return count($this->resolutions);
	}

    /**
     * @return ArrayIterator
     */
	public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->resolutions);
    }

    /**
     * @return int[]
     */
    public function getXArray(): array {
		return array_map(function (Resolution $r): int {
			return $r->getX();
		}, $this->resolutions);
	}

    /**
     * @return int[]
     */
	public function getYArray(): array {
		return array_map(function (Resolution $r): int {
			return $r->getY();
		}, $this->resolutions);
	}

    /**
     * @return float[]
     */
	public function getRArray(): array {
		return array_map(function (Resolution $r): float {
			return $r->getRatio();
		}, $this->resolutions);
	}

    /**
     * @return int
     */
	public function getXMax(): int {
		return max($this->getXArray());
	}

    /**
     * @return int
     */
	public function getYMax(): int {
		return max($this->getYArray());
	}

    /**
     * @return float
     */
	public function getRMax(): float {
		return max($this->getRArray());
	}

    /**
     * @return int
     */
	public function getXMaxLen(): int {
        return $this->getMaxLen($this->getXArray());
	}

    /**
     * @return int
     */
	public function getYMaxLen(): int {
        return $this->getMaxLen($this->getYArray());
	}

    /**
     * @return int
     */
	public function getRMaxLen(): int {
	    return $this->getMaxLen($this->getRArray());
	}

    /**
     * @param array $values
     *
     * @return int
     */
	private function getMaxLen(array $values): int {
	    return max(array_map(function (string $v): int {
	        return strlen($v);
        }, $values));
    }
}

/**
 * Class ResolutionSetLoader
 */
abstract class ResolutionSetLoader {
    /**
     * @var SplFileObject
     */
    protected \SplFileObject $file;

    /**
     * @param SplFileObject $file
     */
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    abstract protected function readType(): array;

    /**
     * @return ResolutionSet
     */
    public function load(): ResolutionSet {
        return new ResolutionSet(...array_map(function (array $coordinates): Resolution {
            return new Resolution(...$coordinates);
        }, $this->readType()));
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

/**
 * Class ResolutionSetLoaderJSON
 */
class ResolutionSetLoaderJSON extends ResolutionSetLoader {
    /**
     * @return array[]
     */
    protected function readType(): array {
        return $this->readJSON()['resolutions'] ?? [];
    }

    /**
     * @return array[]
     */
    private function readJSON() {
        try {
            return (array) json_decode($this->readFile(), true, 512, JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
        } catch (\Exception $e) {
            throw new \RuntimeException(
                sprintf('Failed to load JSON from "%s". Error message: %s', $this->file->getRealPath(), $e->getMessage()), $e->getCode(), $e
            );
        }
    }
}

/**
 * Class ResolutionConsoleOuts
 */
class ResolutionConsoleOuts {
    /**
     * @var ResolutionSet
     */
    private ResolutionSet $resolutions;

    /**
     * @var int
     */
    private int $minimumPads;

    /**
     * @param ResolutionSet $resolutions
     * @param int $minimumPads
     */
    public function __construct(ResolutionSet $resolutions, int $minimumPads = 10)
    {
        $this->resolutions = $resolutions;
        $this->minimumPads = $minimumPads;
    }

    /**
     * @param float|null $limiter
     *
     * @return $this
     */
    public function write(float $limiter = null): self
    {
        if ($this->hasRLines($limiter)) {
            $this->writeSeparator();
            $this->writeHeader();
            $this->writeSeparator();
            $this->writeRLines($limiter);
            $this->writeSeparator();
        } else {
            printf ('No results...'.PHP_EOL);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function writeSeparator(): self
    {
        printf(
            $this->getLineFormat('s', null, null, '+', false),
            str_repeat('-', max($this->resolutions->getXMaxLen(), $this->minimumPads) + 2),
            str_repeat('-', max($this->resolutions->getYMaxLen(), $this->minimumPads) + 2),
            str_repeat('-', max($this->resolutions->getRMaxLen(), $this->minimumPads) + 2)
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function writeHeader(): self
    {
        printf(
            $this->getLineFormat('s'),
            'Coord. X',
            'Coord. Y',
            'Ratio'
        );

        return $this;
    }

    /**
     * @param float|null $limiter
     *
     * @return $this
     */
    public function writeRLines(float $limiter = null): self
    {
        foreach ($this->resolutions as $r) {
            if ($this->isValidRLine($r, $limiter)) {
                printf(
                    $this->getLineFormat('d', 'd', 'f'),
                    $r->getX(),
                    $r->getY(),
                    $r->getRatio()
                );
            }
        }

        return $this;
    }

    /**
     * @param Resolution $r
     * @param float|null $limiter
     *
     * @return bool
     */
    private function isValidRLine(Resolution $r, float $limiter = null): bool
    {
        return null === $limiter || (null !== $limiter && substr($r->getRatio(), 0, strlen((string) $limiter)) === (string) $limiter);
    }

    /**
     * @param float|null $limiter
     *
     * @return bool
     */
    private function hasRLines(float $limiter = null): bool
    {
        foreach ($this->resolutions as $r) {
            if ($this->isValidRLine($r, $limiter)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $typeA
     * @param string|null $typeB
     * @param string|null $typeC
     * @param string|null $separator
     * @param bool $spacing
     * @param int|null $minimumPads
     *
     * @return string
     */
    private function getLineFormat(string $typeA, string $typeB = null, string $typeC = null, string $separator = null, bool $spacing = true, int $minimumPads = null): string {
        $sepper = function() use ($separator): string {
            return $separator ?? '|';
        };

        $spacer = function() use ($spacing): string {
            return $spacing ? ' ' : '';
        };

        return sprintf(
            '%s%s%%-%d%s%s%s%s%%-%d%s%s%s%s%%-%d%s%s%s'.PHP_EOL,
            $sepper(),
            $spacer(),
            max($this->resolutions->getXMaxLen(), $minimumPads ?? $this->minimumPads),
            $typeA,
            $spacer(),
            $sepper(),
            $spacer(),
            max($this->resolutions->getYMaxLen(), $minimumPads ?? $this->minimumPads),
            $typeB ?? $typeA,
            $spacer(),
            $sepper(),
            $spacer(),
            max($this->resolutions->getRMaxLen(), $minimumPads ?? $this->minimumPads),
            $typeC ?? $typeA,
            $spacer(),
            $sepper(),
        );
    }
}

class Main {
    /**
     * @var array
     */
    private array $argumentList;

    /**
     * @var int
     */
    private int $argumentSize;

    /**
     * @param array $argumentList
     * @param int $argumentSize
     */
    public function __construct(array $argumentList, int $argumentSize)
    {
        $this->argumentList = $argumentList;
        $this->argumentSize = $argumentSize;
    }

    /**
     * @return int
     */
    public function run(): int {
        (new ResolutionConsoleOuts((new ResolutionSetLoaderJSON($this->getJSONFile()))->load()))
            ->write($this->argumentList[1] ?? null);

        return 0;
    }

    /**
     * @return SplFileObject
     */
    private function getJSONFile(): \SplFileObject {
        return new \SplFileObject(
            sprintf('%s/%s.%s', __DIR__, pathinfo(__FILE__, PATHINFO_FILENAME), 'json')
        );
    }
}

exit((new Main($argv, $argc))->run());
