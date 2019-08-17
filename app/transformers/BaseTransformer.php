<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Phalcon\Di;

class BaseTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $config;
    /**
     * @var object
     */
    protected $request;


    public function __construct($request)
    {
        $this->request = $request;
        // Load Config
        $this->config = Di::getDefault()->get('config');
    }

    /**
     * @param $obj object or array
     *
     * @return array
     */
    public function getDataToArray($obj)
    {
        $result = [];
        if (method_exists($obj, 'getFirst')) {
            $column = $obj->getFirst()->apiColumnMap();
            foreach ($obj as $item) {
                foreach ($column as $key => $value) {
                    if (isset($this->request['fields']) && !in_array($value, $this->request['fields'])) {
                        continue;
                    }
                    $result[$value] = $item->$key;
                }
            }
        } else {
            $result = $obj;
        }

        return $result;
    }

    /**
     * Performs the final layer of output of each of the columns.
     * Checks and converts data types as required, also does a check in case we are mapping against different values
     *
     * @param $object
     *
     * @return array
     */
    public function transformFinalOutput($object)
    {
        $result = [];

        if (!method_exists($object, 'apiColumnMultiMap')) {
            return $this->getDataToArray($object);
        }

        foreach ($object->apiColumnMultiMap() as $key => $value) {
            // Is this column even defined in our map
            if (isset($object->$key)) {
                // Do we need to map this value to something defined in the map array
                if (isset($value['map'])) {
                    $result[$value['alias']] = $value['map'][$object->$key];
                } else {
                    // Check if we have a boolean datatype, and convert to true or false
                    if ($value['type'] == 'boolean') {
                        $result[$value['alias']] = ($object->$key === '1');
                        // Check for integer data type and convert to int
                    } elseif ($value['type'] == 'integer') {
                        $result[$value['alias']] = (int)$object->$key;
                        // No special circumstances apply, just output the value as is
                    } else {
                        $result[$value['alias']] = $object->$key;
                    }
                }
            }
        }

        return $result;
    }
}
