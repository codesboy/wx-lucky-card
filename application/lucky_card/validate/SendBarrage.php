<?php
namespace app\lucky_card\validate;

class SendBarrage extends BaseValidate{
    protected $rule=[
        'msg'=>'require|isNotEmpty'
    ];


}
