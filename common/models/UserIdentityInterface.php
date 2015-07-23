<?php

namespace common\models;
/**
 * UserIdentityInterface is the interface that should be implemented by a class providing identity information.
 *
 * This interface can typically be implemented by a user model class. For example, the following
 * code shows how to implement this interface by a User ActiveRecord class:
 *
 * ~~~
 * class User extends ActiveRecord implements UserIdentityInterface
 * {
 *     public static function findIdentity($id)
 *     {
 *         return static::findOne($id);
 *     }
 *
 *     public static function findIdentityByAccessToken($token, $type = null)
 *     {
 *         return static::findOne(['access_token' => $token]);
 *     }
 * 
 *     public static function findIdentityByCredential($username,$password, $type = null)
 *     {
 *         return static::findOne(['username' => $username]);
 *     }
 * 
 *     public static function findIdentityByDigest($digest, $type = null)
 *     {
 *         will define later
 *     }
 *
 *     public function getId()
 *     {
 *         return $this->id;
 *     }
 *
 *     public function getAuthKey()
 *     {
 *         return $this->authKey;
 *     }
 *
 *     public function validateAuthKey($authKey)
 *     {
 *         return $this->authKey === $authKey;
 *     }
 * }
 * ~~~
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
interface UserIdentityInterface extends \yii\web\IdentityInterface
{
    /**
     * Finds an identity by the given credential
     * @param string $username the username
     * @param string $password password
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\common\filters\auth\HttpBasicAuth]] will set this parameter to be `common\filters\auth\HttpBasicAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByCredential($username,$password, $type = null);
    
    /**
     * Finds an identity by the given secrete token.
     * @param array $digest Http digest data in array format
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\common\filters\auth\HttpDigestAuth]] will set this parameter to be `common\filters\auth\HttpDigestAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByDigest($digest,$realm, $type = null);
}
