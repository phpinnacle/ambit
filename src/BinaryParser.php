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

final class BinaryParser
{
    /**
     * @var BinaryReader
     */
    private $header;

    /**
     * @var int
     */
    private $index;

    /**
     * @var string
     */
    private $data = '';

    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var array
     */
    private $empty;

    /**
     * @param BinaryReader $header
     * @param int          $index
     */
    public function __construct(BinaryReader $header, int $index)
    {
        if ($index < 0 || $index > $header->count()) {
            throw new \InvalidArgumentException("Invalid body size index {$index}.");
        }

        $this->header = $header;
        $this->index  = $index;
        $this->empty  = \array_fill(0, $this->header->count() + 1, null);
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return $this->size === 0;
    }

    /**
     * @param string $data
     *
     * @return void
     */
    public function append(string $data): void
    {
        $this->data .= $data;
        $this->size += \strlen($data);
    }

    /**
     * @param int $size
     *
     * @return void
     */
    public function discard(int $size): void
    {
        if ($size === $this->size) {
            $this->data = '';
            $this->size = 0;
        } else {
            $this->data  = \substr($this->data, $size);
            $this->size -= $size;
        }
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->data = '';
        $this->size = 0;
    }

    /**
     * @return array
     */
    public function header(): array
    {
        return $this->header->unpack($this->data);
    }

    /**
     * @param int $size
     *
     * @return string
     */
    public function body(int $size): ?string
    {
        $length = $this->header->size() + $size;

        if ($this->size < $length) {
            return null;
        }

        $body = \substr($this->data, $this->header->size(), $size);

        $this->data  = \substr($this->data, $length);
        $this->size -= $length;

        return $body;
    }

    /**
     * @return array
     */
    public function parse(): array
    {
        $header = $this->header();

        if (!isset($header[$this->index])) {
            return $this->empty;
        }

        if (!$body = $this->body($header[$this->index])) {
            return $this->empty;
        }

        $header[] = $body;

        return $header;
    }
}
