<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\swiftmailer\Mailer;
use yii\swiftmailer\Message;


/**
 * Login form
 */
class ForgetForm extends Model
{
    /**
     * @var string Username and/or email
     */
    //public $email;
    public $username;

    /**
     * @var \amnah\yii2\user\models\User
     */
    protected $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            /*["email", "required"],
            ["email", "email"],
            ["email", "validateEmail"],
            ["email", "filter", "filter" => "trim"],//*/
            
            ["username", "required"],
            ["username", 'common\validators\UsernameValidator'],
            ["username", "validateUser"],
            ["username", "filter", "filter" => "trim"],
        ];
    }

    /**
     * Validate email exists and set user property
     */
    public function validateEmail()
    {
        if (!$this->getUser()) {
            $this->addError("email", Yii::t("app", "Email not found"));
        }
    }
    
    /**
     * Validate email exists and set user property
     */
    public function validateUser()
    {
        if (!$this->getUser()) {
            $this->addError("username", Yii::t("app", "Username not found"));
        }
    }

    /**
     * Get user based on email
     *
     * @return \amnah\yii2\user\models\User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //"email" => Yii::t("app", "Email"),
            "username" => Yii::t("app", "Username"),
        ];
    }

    /**
     * Send forgot email
     *
     * @return bool
     */
    public function sendForgotEmail()
    {
        // validate
        if ($this->validate()) {

            // get user
            $user = $this->getUser();
            
            $user->generatePasswordResetToken();
            $user->updatedBy = $user->Id;
            $user->save();

            // modify view path to module views
            $mailer           = Yii::$app->mailer;
            $oldViewPath      = $mailer->viewPath;

            // send email
            $subject = Yii::t("app", "[What's HOT CMS] Forgot password");
            $message  = Yii::$app->mailer->compose('passwordResetToken', compact("subject", "user"))
                ->setTo($user->email)
                ->setSubject($subject);

            // check for messageConfig before sending (for backwards-compatible purposes)
            \yii::trace(Yii::$app->params["adminEmail"]);
            \yii::trace(Yii::$app->mailer->messageConfig["from"]);
            if (empty(Yii::$app->mailer->messageConfig["from"])) {
                $message->setFrom(Yii::$app->params["adminEmail"]);
            }
            
            $result = $message->send();

            return $result;
        }

        return false;
    }
}