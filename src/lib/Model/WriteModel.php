<?php

namespace PommProject\PommFosUserBundle\Model;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;
use PommProject\ModelManager\Model\FlexibleEntity\FlexibleEntityInterface;

/**
 * Description of WriteModel
 *
 * @author florian
 */
abstract class WriteModel extends Model
{

    use WriteQueries;

    public function __construct()
    {
        $this->initStructure();
    }

    abstract protected function initStructure();

    public function saveOne(FlexibleEntityInterface &$entity)
    {
        if ($entity->status() & FlexibleEntityInterface::STATUS_EXIST) {
            if ($entity->status() & FlexibleEntityInterface::STATUS_MODIFIED) {
                return $this->updateOneEntity($entity);
            }
        } else {
            return $this->insertOne($entity);
        }
        return $this;
    }

    public function updateOneEntity(FlexibleEntityInterface &$entity, array $fields = null)
    {
        if (!$fields) {
            $fields = array_diff(
                        $this->getStructure()->getFieldNames(),
                        $this->getStructure()->getPrimaryKey()
                    );
        }
        return $this->updateOne($entity, $fields);
    }

}
