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
     * @param mixed $userId user id
     * @param string $username username to check
     * @return bool true if $username is already used by another id
     */
    public function existsUsernameForOtherUser($userId, $username);

    /**
     * Check if $email is already used by another user.
     * @param mixed $userId user id
     * @param string $email email to check
     * @return bool true if $email is already used by another id
     */
    public function existsEmailForOtherUser($userId, $email);

}
