<?php

namespace Fferriere\PommProjectFosUserBundle\Manager;

/**
 *
 * @author florian
 */
interface AdvancedUserManagerInterface
{

    /**
     * Check if $username is already used by another user.
     * @param string $username username to check
     * @param mixed $userId user id
     * @return bool true if $username is already used by another id
     */
    public function existsUsernameForOtherUser($username, $userId = null);

    /**
     * Check if $email is already used by another user.
     * @param string $email email to check
     * @param mixed $userId user id
     * @return bool true if $email is already used by another id
     */
    public function existsEmailForOtherUser($email, $userId = null);

}
