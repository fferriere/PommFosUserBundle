<?php

namespace Pomm\Bundle\FosUserBundle\Entity\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class UserMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'Pomm\Bundle\FosUserBundle\Entity\User';
        $this->object_name  =  '"public"."fos_user"';

        $this->addField('id', 'int4');
        $this->addField('username', 'varchar');
        $this->addField('username_canonical', 'varchar');
        $this->addField('email', 'varchar');
        $this->addField('email_canonical', 'varchar');
        $this->addField('enabled', 'bool');
        $this->addField('salt', 'varchar');
        $this->addField('password', 'varchar');
        $this->addField('last_login', 'timestamp');
        $this->addField('locked', 'bool');
        $this->addField('expired', 'bool');
        $this->addField('expires_at', 'timestamp');
        $this->addField('confirmation_token', 'varchar');
        $this->addField('password_requested_at', 'timestamp');
        $this->addField('roles', 'text');
        $this->addField('credentials_expired', 'bool');
        $this->addField('credentials_expire_at', 'timestamp');

        $this->pk_fields = array('id');
    }
}
