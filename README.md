PommFosUserBundle
=================

FosUserBundle driver for Pomm

This bundle permit to use FosUserBundle with Pomm easily.

Configuration
-------------

In `app/config/config.yml` add configuration below :

    fos_user:
        db_driver: custom
        user_class: Pomm\Bundle\FosUserBundle\Entity\User
        service:
            user_manager: pomm_fos_user_bundle.user_manage
            
And that's all.

Overriding
-----------

It's possible you need to inherit `Pomm\Bundle\FosUserBundle\Entity\User` on your own bundle and with its specific Map.
You can so overriding `pomm_fos_user.user_map_class` parameter
(on `YourBundlePath/Resources/config/service.yml` or on `app.config.config.yml`)
to get the good entity's map on pomm user manager.
