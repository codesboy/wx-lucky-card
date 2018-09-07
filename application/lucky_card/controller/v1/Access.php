<?php
namespace app\api\controller\v1;

use think\facade\Request;


// 微信公众号接入接口
class Access{

    public function index(){
        // return 'Access.php';

        $signature =Request::param('signature');
        $nonce = Request::param('nonce');
        $token = 'VfhtMxqEaBqGD';
        $timestamp = Request::param('timestamp');
        $echostr = Request::param('echostr');

        $arr=[$nonce,$timestamp,$token];
        sort($arr);

        $str = sha1(implode($arr));
        if($str == $signature){
            return $echostr;
        }


    }
}
