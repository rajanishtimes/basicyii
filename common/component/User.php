<?php
/*
 * Project: CMS TimesCity 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\component;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Controller is the base class of web/module controllers.
 *
 * @author Mithun Mandal <mithun12000@gmail.com> * 
 */

class User extends \yii\web\User{
    private $_identity = false;    
   
    private $_access = [];
    /**
     * Logs in a user by the given Credential.
     * This method will first authenticate the user by calling [[UserIdentityInterface::findIdentityByCredential()]]
     * with the provided access token. If successful, it will call [[login()]] to log in the authenticated user.
     * If authentication fails or [[login()]] is unsuccessful, it will return null.
     * @param string $username Username
     * @param string $password Password
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\common\filters\auth\HttpBasicAuth]] will set this parameter to be `yii\filters\auth\HttpBasicAuth`.
     * @return IdentityInterface|null the identity associated with the given access token. Null is returned if
     * the access token is invalid or [[login()]] is unsuccessful.
     */
    public function loginByCredential($username,$password,$type = null)
    {
        /* @var $class IdentityInterface */
        $class = $this->identityClass;
        $identity = $class::findIdentityByCredential($username,$password,$type);
        if ($identity && $this->login($identity)) {
            return $identity;
        } else {
            return null;
        }
    }
    
    
    /**
     * Logs in a user by Http Digest Method
     * This method will first authenticate the user by calling [[UserIdentityInterface::findIdentityByDigest()]]
     * with the provided Digest data and realm. If successful, it will call [[login()]] to log in the authenticated user.
     * If authentication fails or [[login()]] is unsuccessful, it will return null.
     * @param array $digest Http Digest part as array
     * @param string $realm RealM value for Http Digest Method
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\common\filters\auth\HttpDigestAuth]] will set this parameter to be `yii\filters\auth\HttpDigestAuth`.
     * @return IdentityInterface|null the identity associated with the given access token. Null is returned if
     * the access token is invalid or [[login()]] is unsuccessful.
     */
    public function loginByDigest($digest,$realm, $type = null)
    {
        /* @var $class IdentityInterface */
        $class = $this->identityClass;
        $identity = $class::findIdentityByDigest($digest,$realm, $type);
        if ($identity && $this->login($identity)) {
            return $identity;
        } else {
            return null;
        }
    }
}