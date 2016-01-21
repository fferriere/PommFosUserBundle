<?php

namespace Fferriere\PommProjectFosUserBundle\Manager;

use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Model\UserInterface;
use Fferriere\PommProjectFosUserBundle\Model\WriteModel;
use Fferriere\PommProjectFosUserBundle\Exception\Exception;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Fferriere\PommProjectFosUserBundle\Entity\UserEntity;
use PommProject\Foundation\Inflector;
use PommProject\Foundation\Where;

/**
 * Description of UserManager
 *
 * @author florian
 */
class UserManager extends BaseUserManager implements AdvancedUserManagerInterface
{

    /**
     * @var WriteModel
     */
    protected $model;

    public function __construct(
            EncoderFactoryInterface $encoderFactory,
            CanonicalizerInterface $usernameCanonicalizer,
            CanonicalizerInterface $emailCanonicalizer,
            WriteModel $model
    ) {
        parent::__construct(
            $encoderFactory,
            $usernameCanonicalizer,
            $emailCanonicalizer
        );
        $this->model = $model;
    }

    public function createUser()
    {
        return $this->model->createEntity();
    }

    /**
     * Check if $user is a good instance and return the model
     * @param UserInterface $user instance to check
     * @return WriteModel the model
     * @throws Exception throwed if $user is not a good instance
     */
    protected function checkUser(UserInterface $user)
    {
        if (! $user instanceof UserEntity) {
            throw new Exception('user instance is not an instance of \Fferriere\PommProjectFosUserBundle\Entity\UserEntity');
        }
        return $this->model;
    }

    public function deleteUser(UserInterface $user)
    {
        return $this->checkUser($user)
                    ->deleteOne($user);
    }

    public function findUserBy(array $criteria)
    {
        $where = $this->createWhereByCriteria($criteria);
        $result = $this->model->findWhere($where);
        if ($result->count() > 0) {
            return $result->current();
        }
        return null;
    }

    public function findUsers()
    {
        $this->model->findAll();
    }

    public function getClass()
    {
        return $this->model->getFlexibleEntityClass();
    }

    public function reloadUser(UserInterface $user)
    {
        return $this->checkUser($user)
                    ->findByPK(
                        $this->getPrimaryKeyValues($user)
                    );
    }

    public function updateUser(UserInterface $user)
    {
        $this->checkUser($user);
        $user->updateRoles();
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);
        return $this->model->saveOne($user);
    }

    protected function getPrimaryKeyValues(UserEntity $user) {
        $colnames = $this->model->getStructure()->getPrimaryKey();
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

    /**
     * @inheritDoc
     */
    public function existsUsernameForOtherUser($userId, $username)
    {
        $username = $this->canonicalizeUsername($username);
        return $this->model->existWhere(
                (new Where('username_canonical = $*', [ $username ]))
                    ->andWhere('id != $*', [ $userId ])
        );
    }

    /**
     * @inheritDoc
     */
    public function existsEmailForOtherUser($userId, $email)
    {
        $email = $this->canonicalizeEmail($email);
        return $this->model->existWhere(
                (new Where('email_canonical = $*', [ $email ]))
                    ->andWhere('id != $*', [ $userId ])
        );
    }

}
