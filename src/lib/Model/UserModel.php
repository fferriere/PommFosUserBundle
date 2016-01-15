<?php

namespace Fferriere\PommProjectFosUserBundle\Model;

use PommProject\ModelManager\Model\RowStructure;

/**
 * Description of UserModel
 *
 * @author florian
 */
class UserModel extends WriteModel
{

    protected $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        parent::__construct();
    }

    protected function initStructure()
    {
        $this->structure = (new RowStructure())
                ->setRelation($this->tableName)
                ->addField('id', 'int4')
                ->addField('username', 'varchar')
                ->addField('username_canonical', 'varchar')
                ->addField('email', 'varchar')
                ->addField('email_canonical', 'varchar')
                ->addField('enabled', 'bool')
                ->addField('salt', 'varchar')
                ->addField('password', 'varchar')
                ->addField('last_login', 'timestamp')
                ->addField('locked', 'bool')
                ->addField('expired', 'bool')
                ->addField('expires_at', 'timestamp')
                ->addField('confirmation_token', 'varchar')
                ->addField('password_requested_at', 'timestamp')
                ->addField('roles', 'text')
                ->addField('credentials_expired', 'bool')
                ->addField('credentials_expire_at', 'timestamp')
                ->setPrimaryKey([ 'id' ]);
        $this->flexible_entity_class = 'Fferriere\PommProjectFosUserBundle\Entity\UserEntity';
    }

}
