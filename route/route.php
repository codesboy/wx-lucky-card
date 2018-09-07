<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 微信公众号接入
Route::get('luckycard/:version/access','lucky_card/:version.Access/index');


// 客户报名
Route::post('luckycard/:version/signup','lucky_card/:version.LuckyCustomes/getCustomer')->allowCrossDomain();

// 更新中奖状态
Route::post('luckycard/:version/changeluckystatus','lucky_card/:version.LuckyCustomes/updateStatus')->allowCrossDomain();

// 获取是否开启抽奖状态
Route::get('luckycard/:version/getisstart','lucky_card/:version.LuckyCustomes/isStart')->allowCrossDomain();

// 添加客户
Route::post('luckycard/:version/addcustome','lucky_card/:version.LuckyCustomes/AddCustome')->allowCrossDomain();

// 查询所有客户数据
Route::get('luckycard/:version/getall','lucky_card/:version.LuckyCustomes/getAll')->allowCrossDomain();

