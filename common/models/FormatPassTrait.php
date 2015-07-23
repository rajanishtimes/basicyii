<?php
namespace common\models;
/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

trait FormatPassTrait {
    /**
     * Format Password for Http Digest H1 part
     *
     * @return User|null
     */
    public function formatPassword($password)
    {
        return md5(implode(':',[$this->username,REALM,$password]));
    }
    
    /**
     * Format Password for Http Digest H1 part
     *
     * @return User|null
     */
    public function formatPasswordWithCredential($username,$password)
    {
        return md5(implode(':',[$username,REALM,$password]));
    }
}