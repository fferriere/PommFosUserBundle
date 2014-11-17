<?php

namespace Pomm\Bundle\FosUserBundle\Model;

use FOS\UserBundle\Model\UserInterface;
use Pomm\Object\BaseObject;

/**
 * Description of User
 *
 * @author florian
 */
abstract class User extends BaseObject implements UserInterface {

    protected $roles;

    protected $plainPassword;

    public function __construct(array $values = null)
    {
        $this->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        $this->roles = array();
        parent::__construct($values);
    }

    public function addRole($role) {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    public function getCredentialsExpireAt() {
        return $this->getWithoutThrow('credentials_expire_at');
    }

    public function getConfirmationToken() {
        return $this->getWithoutThrow('confirmation_token');
    }

    public function getEmail() {
        return $this->getWithoutThrow('email');
    }

    public function getEmailCanonical() {
        return $this->getWithoutThrow('email_canonical');
    }

    public function getExpiresAt() {
        return $this->getWithoutThrow('expires_at');
    }

    public function getId() {
        return $this->getWithoutThrow('id');
    }

    public function getLastLogin() {
        return $this->getWithoutThrow('last_login');
    }

    public function getPassword() {
        return $this->getWithoutThrow('password');
    }

    public function getPasswordRequestedAt() {
        return $this->getWithoutThrow('password_requested_at');
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function getRoles() {
        if((!$this->roles || !is_array($this->roles) || empty($this->roles)) && $this->has('roles')) {
            $this->roles = unserialize($this->get('roles'));
        }

        $this->roles[] = static::ROLE_DEFAULT;
        $this->roles = array_unique($this->roles);

        return $this->roles;
    }

    public function getSalt() {
        return $this->getWithoutThrow('salt');
    }

    public function getUsername() {
        return $this->getWithoutThrow('username');
    }

    public function getUsernameCanonical() {
        return $this->getWithoutThrow('username_canonical');
    }

    public function hasRole($role) {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function isAccountNonExpired() {
        if (true === $this->isExpired()) {
            return false;
        }

        if (null !== $this->getExpiresAt()
                && $this->getExpiresAt()->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

    public function isAccountNonLocked() {
        return !$this->isLocked();
    }

    public function isCredentialsExpired() {
        return $this->getWithoutThrow('credentials_expired', false);
    }

    public function isCredentialsNonExpired() {
        if (true === $this->isCredentialsExpired()) {
            return false;
        }

        if (null !== $this->getCredentialsExpireAt()
                && $this->getCredentialsExpireAt()->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

    public function isEnabled() {
        return $this->getWithoutThrow('enabled', false);
    }

    public function isExpired() {
        return $this->getWithoutThrow('expired', false);
    }

    public function isLocked() {
        return $this->getWithoutThrow('locked', false);
    }

    public function isPasswordRequestNonExpired($ttl) {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
               $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    public function isSuperAdmin() {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    public function isUser(UserInterface $user = null) {
        return null !== $user && $this->getId() === $user->getId();
    }

    public function removeRole($role) {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function serialize() {
        return serialize(array(
            $this->getPassword(),
            $this->getSalt(),
            $this->getUsernameCanonical(),
            $this->getUsername(),
            $this->isExpired(),
            $this->isLocked(),
            $this->isCredentialsExpired(),
            $this->isEnabled(),
            $this->getId(),
        ));
    }

    public function setConfirmationToken($confirmationToken) {
        $this->set('confimation_token', $confirmationToken);
        return $this;
    }

    public function setCredentialsExpired($credentialsExpired) {
        $this->set('credentials_expired', $credentialsExpired);
        return $this;
    }

    public function setCredentialsExpireAt($credentialsExpireAt) {
        $this->set('credentials_expire_at', $credentialsExpireAt);
        return $this;
    }

    public function setEmail($email) {
        $this->set('email', $email);
        return $this;
    }

    public function setEmailCanonical($emailCanonical) {
        $this->set('email_canonical', $emailCanonical);
        return $this;
    }

    public function setEnabled($boolean) {
        $this->set('enabled', $boolean);
        return $this;
    }

    public function setExpired($expired) {
        $this->set('expired', $expired);
        return $this;
    }

    public function setExpiresAt($expiresAt) {
        $this->set('expires_at', $expiresAt);
        return $this;
    }

    public function setId($id) {
        $this->set('id', $id);
        return $this;
    }

    public function setLastLogin(\DateTime $time = null) {
        $this->set('last_login', $time);
        return $this;
    }

    public function setLocked($boolean) {
        $this->set('locked', $boolean);
        return $this;
    }

    public function setPassword($password) {
        $this->set('password', $password);
        return $this;
    }

    public function setPasswordRequestedAt(\DateTime $date = null) {
        $this->set('password_requested_at', $date);
        return $this;
    }

    public function setPlainPassword($password) {
        $this->plainPassword = $password;
        return $this;
    }

    public function setRoles(array $roles) {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function setSalt($salt) {
        $this->set('salt', $salt);
        return $this;
    }

    public function setSuperAdmin($boolean) {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }

        return $this;
    }

    public function setUsername($username) {
        $this->set('username', $username);
        return $this;
    }

    public function setUsernameCanonical($usernameCanonical) {
        $this->set('username_canonical', $usernameCanonical);
        return $this;
    }

    public function unserialize($serialized) {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $password,
            $salt,
            $usernameCanonical,
            $username,
            $expired,
            $locked,
            $credentialsExpired,
            $enabled,
            $id
        ) = $data;

        $this->setPassword($password)
            ->setSalt($salt)
            ->setUsernameCanonical($usernameCanonical)
            ->setUsername($username)
            ->setExpired($expired)
            ->setLocked($locked)
            ->setCredentialsExpired($credentialsExpired)
            ->setEnabled($enabled)
            ->setId($id);
    }

    public function updateRoles() {
        $this->set('roles', serialize($this->getRoles()));
    }

    public function __toString() {
        return (string) $this->getUsername();
    }

    protected function getWithoutThrow($var, $defaultValue = null) {
        if($this->has($var)) {
            return $this->get($var);
        }
        return $defaultValue;
    }

}
