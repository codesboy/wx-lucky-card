<?php
namespace app\lucky_card\model;
//
class LuckyCustomes extends Base{
    // protected $hidden=['id','delete_time','user_id'];
    protected $autoWriteTimestamp = true;

    public static function getCustomerByPhone($phone){
        $custom=self::getByPhone($phone);//查询数据
        // $res = self::getLastSql();

        return $custom;
    }
}
