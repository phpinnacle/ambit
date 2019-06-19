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

abstract class BinaryReader
{
    /**
     * @var string
     */
    protected $mask = "";

    /**
     * @var int
     */
    protected $size = 0;

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
     * @param array  $values
     * @param string $key
     * @param int    $size
     *
     * @return mixed
     */
    abstract public function value(array $values, string $key, int $size);

    /**
     * @return self
     */
    public static function bigEndian(): self
    {
        return new Reader\BigEndian;
    }

    /**
     * @return self
     */
    public static function littleEndian(): self
    {
        return new Reader\LittleEndian;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return static
     */
    public function string(int $size): self
    {
        return $this->read('a'.$size, $size, 1);
    }

    /**
     * @return static
     */
    public function uint8(): self
    {
        return $this->read('C', 1, 1);
    }

    /**
     * @return static
     */
    public function int8(): self
    {
        return $this->read('c', 1, 1);
    }

    /**
     * @param string $data
     * @param int    $offset
     *
     * @return array
     */
    public function unpack(string $data, int $offset = 0): array
    {
        if ($this->size > \strlen($data)) {
            return \array_fill(0, $this->count - 1, null);
        }

        $values = \unpack($this->mask, $data, $offset);
        $result = [];

        foreach ($this->compound as $key => $size) {
            $result[] = $this->value($values, $key, $size);
        }

        return $result;
    }

    /**
     * @param string $format
     * @param int    $size
     * @param int    $length
     *
     * @return static
     */
    protected function read(string $format, int $size, int $length): self
    {
        $index = $this->count++;

        $this->mask .= "{$format}b{$index}/";
        $this->size += $size;

        $this->compound["b{$index}"] = $length;

        return $this;
    }
}
