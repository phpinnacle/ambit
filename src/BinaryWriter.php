<?php
/**
 * This file is part of PHPinnacle/Ambit.
 *
 * (c) PHPinnacle Team <dev@phpinnacle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PHPinnacle\Ambit;

abstract class BinaryWriter
{
    /**
     * @var string
     */
    protected $mask = '';

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var array
     */
    protected $compound = [];

    /**
     * @return static
     */
    abstract public function uint16(): self;

    /**
     * @return static
     */
    abstract public function uint32(): self;

    /**
     * @return static
     */
    abstract public function uint64(): self;

    /**
     * @return static
     */
    abstract public function int16(): self;

    /**
     * @return static
     */
    abstract public function int32(): self;

    /**
     * @return static
     */
    abstract public function int64(): self;

    /**
     * @return static
     */
    abstract public function float(): self;

    /**
     * @return static
     */
    abstract public function double(): self;

    /**
     * @param array $values
     * @param int   $key
     * @param int   $size
     *
     * @return array
     */
    abstract protected function value(array $values, int $key, int $size): array;

    /**
     * @return self
     */
    public static function bigEndian(): self
    {
        return new Writer\BigEndian;
    }

    /**
     * @return self
     */
    public static function littleEndian(): self
    {
        return new Writer\LittleEndian;
    }

    /**
     * @return static
     */
    public function string(): self
    {
        return $this->write('a*', 1);
    }

    /**
     * @return static
     */
    public function int8(): self
    {
        return $this->write('c', 1);
    }

    /**
     * @return static
     */
    public function uint8(): self
    {
        return $this->write('C', 1);
    }

    /**
     * @param mixed ...$data
     *
     * @return string
     */
    public function pack(...$data): string
    {
        $args = [];

        foreach ($this->compound as $key => $count) {
            if ($count > 1) {
                $value = $this->value($data, $key, $count);

                foreach ($value as $item) {
                    $args[] = $item;
                }
            } else {
                $args[] = $data[$key];
            }
        }

        return \pack($this->mask, ...$args);
    }

    /**
     * @param string $format
     * @param int    $count
     *
     * @return BinaryWriter
     */
    protected function write(string $format, int $count): self
    {
        $this->mask .= $format;

        $this->compound[] = $count;

        return $this;
    }
}