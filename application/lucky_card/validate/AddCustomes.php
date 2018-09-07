<?php
namespace app\lucky_card\validate;

class AddCustomes extends BaseValidate{
    protected $rule=[
        'phone'=>'require|isMobile'
    ];
}
