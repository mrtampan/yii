<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CountryForm extends Model {
    public $code;
    public $name;
    public $population;
    
    public function rules(){
        return [
            [[
                'name', 'code','population'
            ], 'required']
        ];
    }
}