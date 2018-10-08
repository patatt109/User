<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 28/11/16 18:38
 */

namespace Modules\User\Validators;


use Phact\Translate\Translator;
use Phact\Validators\Validator;

class PasswordValidator extends Validator
{
    use Translator;

    protected $_minLength = 6;

    public function __construct($minLength = 6)
    {
        $this->_minLength = $minLength;
    }

    public function validate($value)
    {
        $errors = [];
        if ($value && mb_strlen($value) < $this->_minLength) {
            $errors[] = sprintf(self::t('User.main', 'Password cannot be shorter than %s characters'), $this->_minLength);
        }
        return empty($errors) ? true : $errors;
    }
}