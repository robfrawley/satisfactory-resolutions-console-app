<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Model;

/**
 * Class ResolutionCollection
 */
class ResolutionCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var string
     */
    public const ORDER_TYPE_X = 'x';

    /**
     * @var string
     */
    public const ORDER_TYPE_X_ASC = 'x:asc';

    /**
     * @var string
     */
    public const ORDER_TYPE_X_DES = 'x:des';

    /**
     * @var string
     */
    public const ORDER_TYPE_Y = 'y';

    /**
     * @var string
     */
    public const ORDER_TYPE_Y_ASC = 'y:asc';

    /**
     * @var string
     */
    public const ORDER_TYPE_Y_DES = 'y:des';

    /**
     * @var string
     */
    public const ORDER_TYPE_R = 'r';

    /**
     * @var string
     */
    public const ORDER_TYPE_R_ASC = 'r:asc';

    /**
     * @var string
     */
    public const ORDER_TYPE_R_DES = 'r:des';

    /**
     * @var array|Resolution[]
     */
    private array $resolutions = [];

    /**
     * ResolutionCollection constructor.
     *
     * @param Resolution ...$resolutions
     */
    public function __construct(Resolution ...$resolutions)
    {
        $this->resolutions = $resolutions;
    }

    /**
     * @param array ...$resolutions
     *
     * @return static
     */
    public static function create(array ...$resolutions): self
    {
        return new self(...array_map(function (array $coordinates): Resolution {
            return new Resolution(...$coordinates);
        }, $resolutions));
    }

    /**
     * @return $this
     */
    public function filterX(string $filterX = null): self
    {
        if (null !== $filterX) {
            $this->resolutions = array_filter($this->resolutions, function (Resolution $r) use ($filterX): bool {
                return fnmatch($filterX, (string) $r->getX(), FNM_CASEFOLD);
            });
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function filterY(string $filterY = null): self
    {
        if (null !== $filterY) {
            $this->resolutions = array_filter($this->resolutions, function (Resolution $r) use ($filterY): bool {
                return fnmatch($filterY, (string) $r->getY(), FNM_CASEFOLD);
            });
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function filterR(string $filterR = null): self
    {
        if (null !== $filterR) {
            $this->resolutions = array_filter($this->resolutions, function (Resolution $r) use ($filterR): bool {
                return fnmatch($filterR, (string) $r->getR(), FNM_CASEFOLD);
            });
        }

        return $this;
    }

    /**
     * @param string ...$types
     *
     * @return $this
     */
    public function orderBy(string ...$types): self
    {
        foreach ($types as $t) {
            switch ($t) {
                case self::ORDER_TYPE_X:
                case self::ORDER_TYPE_X_ASC:
                    $this->orderByX();
                    break;

                case self::ORDER_TYPE_X_DES:
                    $this->orderByX(true);
                    break;

                case self::ORDER_TYPE_Y:
                case self::ORDER_TYPE_Y_ASC:
                    $this->orderByY();
                    break;

                case self::ORDER_TYPE_Y_DES:
                    $this->orderByY(true);
                    break;

                case self::ORDER_TYPE_R:
                case self::ORDER_TYPE_R_ASC:
                    $this->orderByR();
                    break;

                case self::ORDER_TYPE_R_DES:
                    $this->orderByR(true);
                    break;

                default:
                    throw new \RuntimeException(sprintf('Invalid resolution ordering type: "%s".', $t));
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function orderByX(bool $des = false): self
    {
        usort($this->resolutions, function (Resolution $a, Resolution $b) use ($des): int {
            return $des
                ? $a->getX() > $b->getX()
                : $a->getX() < $b->getX();
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function orderByY(bool $des = false): self
    {
        usort($this->resolutions, function (Resolution $a, Resolution $b) use ($des): int {
            return $des
                ? $a->getY() > $b->getY()
                : $a->getY() < $b->getY();
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function orderByR(bool $des = false): self
    {
        usort($this->resolutions, function (Resolution $a, Resolution $b) use ($des): int {
            return $des
                ? $a->getR() > $b->getR()
                : $a->getR() < $b->getR();
        });

        return $this;
    }

    public function map(\Closure $closure): array
    {
        return array_map($closure, $this->resolutions);
    }

    public function count(): int
    {
        return \count($this->resolutions);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->resolutions);
    }

    /**
     * @return int[]
     */
    public function getXArray(): array
    {
        return array_map(function (Resolution $r): int {
            return $r->getX();
        }, $this->resolutions);
    }

    /**
     * @return int[]
     */
    public function getYArray(): array
    {
        return array_map(function (Resolution $r): int {
            return $r->getY();
        }, $this->resolutions);
    }

    /**
     * @return float[]
     */
    public function getRArray(): array
    {
        return array_map(function (Resolution $r): float {
            return $r->getR();
        }, $this->resolutions);
    }

    public function getXMax(): int
    {
        return max($this->getXArray());
    }

    public function getYMax(): int
    {
        return max($this->getYArray());
    }

    public function getRMax(): float
    {
        return max($this->getRArray());
    }

    public function getXMaxLen(): int
    {
        return $this->getMaxLen($this->getXArray());
    }

    public function getYMaxLen(): int
    {
        return $this->getMaxLen($this->getYArray());
    }

    public function getRMaxLen(): int
    {
        return $this->getMaxLen($this->getRArray());
    }

    /**
     * @return string[]
     */
    public static function getOrderTypes(): array
    {
        return [
            self::ORDER_TYPE_X,
            self::ORDER_TYPE_X_ASC,
            self::ORDER_TYPE_X_DES,
            self::ORDER_TYPE_Y,
            self::ORDER_TYPE_Y_ASC,
            self::ORDER_TYPE_Y_DES,
            self::ORDER_TYPE_R,
            self::ORDER_TYPE_R_ASC,
            self::ORDER_TYPE_R_DES,
        ];
    }

    public static function stringifyOrderTypes(string $format = null, string $joiner = null): string
    {
        return implode($joiner ?? ', ', self::mapFormatOrderTypes($format));
    }

    /**
     * @return array
     */
    private static function mapFormatOrderTypes(string $format = null)
    {
        return array_map(function (string $t) use ($format): string {
            return sprintf($format ?? '"%s"', $t);
        }, self::getOrderTypes());
    }

    private function getMaxLen(array $values): int
    {
        return max(array_map(function (string $v): int {
            return mb_strlen($v);
        }, $values));
    }
}
