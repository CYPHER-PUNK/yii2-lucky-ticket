<?php
/**
 * @author Ruslan Fadeev
 * created: 19.02.2015 16:55
 */

namespace app\components;

use yii\db\ActiveRecord;
use yii\validators\Validator;

class IsEvenValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = '{attribute} должно быть четным';
    }

    /**
     * @param ActiveRecord $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if ($model->$attribute & 1) {
            $model->addError($attribute, $this->message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = json_encode(
            str_replace('{attribute}', 'Значение «' . $model->getAttributeLabel($attribute) . '»', $this->message),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        return <<<JS
if (value % 2 != 0) {
    messages.push($message);
}
JS;
    }
}
