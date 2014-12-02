<?php

namespace Pomm\Bundle\FosUserBundle\Model;

use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Pomm\Object\BaseObjectMap;
use Pomm\Bundle\FosUserBundle\Exception\Exception;
use Pomm\Bundle\FosUserBundle\Model\User;
use Pomm\Query\Where;
use Pomm\Tools\Inflector;

/**
 * Description of UserManager
 *
 * @author florian
 */
class UserManager extends BaseUserManager {

    /**
     * @var BaseObjectMap
     */
    protected $map;
    protected $class;

    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, BaseObjectMap $pommMap) {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer);
        $this->map = $pommMap;
        $this->class = $pommMap->getObjectClass();
    }

    public function createUser() {
        return $this->map->createObject();
    }

    public function deleteUser(UserInterface $user) {
        $this->checkUser($user);
        $this->map->deleteOne($user);
    }

    public function findUserBy(array $criteria) {
        $where = $this->createWhereByCriteria($criteria);
        $result = $this->map->findWhere($where);
        if($result->count() > 0) {
            return $result->current();
        }
        return null;
    }

    public function findUsers() {
        return $this->map->findAll();
    }

    public function getClass() {
        return $this->class;
    }

    public function reloadUser(UserInterface $user) {
        $this->checkUser($user);
        $values = $this->getPrimaryKeyValues($user);
        return $this->map->findByPk($values);
    }

    public function updateUser(UserInterface $user) {
        $this->checkUser($user);
        $user->updateRoles();
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);
        return $this->map->saveOne($user);
    }

    protected function checkUser($user) {
        if(! $user instanceof User) {
            throw new Exception('user isn\'t a good instance of \Pomm\Bundle\FosUserBundle\Model\User');
        }
    }

    protected function getPrimaryKeyValues(User $user) {
        $colnames = $this->map->getPrimaryKey();
        $values = array();
        for($i = 0, $size = count($colnames); $i < $size; $i++) {
            $colname = $colnames[$i];
            $value = $user->get($colname);
            $values[$colname] = $value;
        }
        return $values;
    }

    protected function createWhereByCriteria($criteria = array()) {
        $where = new Where();
        foreach($criteria as $colname => $value) {
            $colname = Inflector::underscore($colname);
            $element = sprintf('%s = $*', $colname);
            $subWhere = new Where($element, array($value));
            $where->andWhere($subWhere);
        }
        return $where;
    }

    public function getMap() {
        return $this->map;
    }

}
