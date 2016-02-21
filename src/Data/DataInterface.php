<?php

namespace Laasti\Views\Data;

interface DataInterface extends \ArrayAccess
{
    /**
     * Get value for property
     * @param string $property
     */
    public function get($property);

    /**
     * Set value for property
     * @param string $property
     * @param mixed $value
     */
    public function set($property, $value);

    /**
     * Unset property
     * @param string $property
     */
    public function remove($property);

    /**
     * Add data in batch
     * @param array $data
     */
    public function add($data);

    /**
     * Checks whether a value exists for the property
     */
    public function has($property);

    /**
     * Push value into property which is an array
     * @param string $property
     * @param mixed $value
     */
    public function push($property, $value);

    /**
     * Exports all data as plain array
     * @return array
     */
    public function export();

    /**
     * Removes all data
     */
    public function clear();

    /**
     * Returns a new instance with new data added to it
     * @param array $data
     */
    public function withData(DataInterface $data);
}
