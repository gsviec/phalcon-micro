<?php

namespace App\Models\Services\Exceptions;

use Exception;

/**
 * \App\Models\Services\Exceptions\EntityNotFoundException
 *
 * @package App\Models\Services\Exceptions
 */
class EntityNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $id;

    /**
     * EntityNotFoundException constructor.
     *
     * @param string         $id
     * @param string         $type
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($id, $type = 'id', $code = 0, Exception $previous = null)
    {
        $this->id = $id;

        parent::__construct(
            sprintf('No entity found for %s "%s"', $type, $this->getId()),
            $code,
            $previous
        );
    }

    /**
     * Get the ID which was not found.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
