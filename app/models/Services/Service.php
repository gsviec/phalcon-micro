<?php
namespace App\Models\Services;

use Phalcon\Di;
use Phalcon\DiInterface;
use Phalcon\Di\Injectable;

/**
 * \App\Models\Services\Service
 *
 * @property \Phalcon\Config $config
 * @property \Phalcon\Security\Random $random
 * @property \Phalcon\Logger\AdapterInterface $logger
 *
 * @package App\Models\Services
 */
abstract class Service extends Injectable
{
    /**
     * Service constructor.
     *
     * @param DiInterface|null $di
     */
    public function __construct(DiInterface $di = null)
    {
        $this->setDI($di ?: Di::getDefault());
    }
}
