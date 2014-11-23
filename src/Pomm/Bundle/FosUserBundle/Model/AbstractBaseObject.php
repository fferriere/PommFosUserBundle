<?php

namespace Pomm\Bundle\FosUserBundle\Model;

use Pomm\Object\BaseObject;

/**
 * Description of BaseObject
 *
 * @author florian
 */
abstract class AbstractBaseObject extends BaseObject {
    
    protected function getWithoutThrow($var, $defaultValue = null) {
        if($this->has($var)) {
            return $this->get($var);
        }
        return $defaultValue;
    }
    
}
