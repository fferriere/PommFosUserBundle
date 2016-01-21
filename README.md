# PommFosUserBundle

FosUserBundle driver for PommProject 2

This bundle permit to use FosUserBundle with Pomm easily.

## Installation

With composer :
```
composer require fferriere/pomm-project-fos-user-bundle ~2.0
```

You can execute [SQL script](src/lib/Resources/database/tables.sql) to create table.
It's an example to use with default configuration.

You use another table's name with `pomm_fos_user.table_name` parameter.

## Configuration

In `app/config/config.yml` add configuration below :

```
fos_user:
    db_driver: custom
    firewall: main
    user_class: PommProject\PommFosUserBundle\Entity\UserEntity
    service:
        user_manager: pomm_fos_user_bundle.user_manager
```

And that's all.

## Overriding

It's possible you need to inherit `Pomm\Bundle\FosUserBundle\Entity\User` on your own bundle and with its specific Model.
You can so overriding `pomm_fos_user.user_model_class` parameter
(on `YourBundlePath/Resources/config/service.yml` or on `app.config.config.yml`)
to get the good entity's map on pomm user manager.

## Use uuid

It's possible to use uuid on id column and it's not difficult.

Create a new UserManager as MyBundle\Model\MyModel :
```
<?php

namespace MyBundle\Model;

use Fferriere\PommProjectFosUserBundle\Model\UserModel;

class MyModel extends UserModel
{

    protected function initStructure()
    {
        parent::initStructure();
        $this->structure->addField('id', 'uuid');
    }

}
```

And to finish change the `pomm_fos_user.user_model_class` parameter as on [overriding](#overriding) chapter.
