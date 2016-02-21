<?php

namespace Laasti\Views;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * Description of TemplateStream
 *
 * @author Sonia
 */
class TemplateStream implements StreamInterface
{
    protected $render;

    public function __construct(TemplateRender $render)
    {
        $this->render = $render;
    }
    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString()
    {
        return $this->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->render = null;
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        $render = $this->render;
        $this->render = null;
        return $render;
    }

    /**
     *
     * @param TemplateRender $render
     */
    public function attach(TemplateRender $render)
    {
        $this->render = $render;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        throw new RuntimeException('Cannot seek position of a TemplateStream');
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        return empty($this->renderer);
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        throw new RuntimeException('Cannot seek position of a TemplateStream');
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        throw new RuntimeException('Cannot rewind position of a TemplateStream');
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function write($string)
    {
        throw new RuntimeException('Cannot write to a TemplateStream.');
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        throw new RuntimeException('Cannot read a TemplateStream');
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents()
    {
        //TODO
        $render = $this->detach();
        return $render ? $render->render() : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key = null)
    {
        $metadata = [
            'eof' => $this->eof(),
            'stream_type' => 'template',
            'seekable' => false
        ];
        if (null === $key) {
            return $metadata;
        }
        if (!array_key_exists($key, $metadata)) {
            return null;
        }
        return $metadata[$key];
    }

}
