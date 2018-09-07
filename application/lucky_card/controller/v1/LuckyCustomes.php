<?php
namespace app\lucky_card\controller\v1;

use think\facade\Request;
use app\lucky_card\validate\AddCustomes;
use app\lucky_card\model\LuckyCustomes as LuckyCustomesModel;
use app\lucky_card\model\LuckyItems as LuckyItemsModel;
use think\Exception;
// 微信公众号接入接口
class LuckyCustomes{

    public function index(){
        return 'Access.php';



    }

    public function signup(){
        $validate=new AddCustomes();
        // dump($validate);die;
        $validate->doCheck();

        $data = $validate->getDataByRule(input('post.'));//获取用户提交的信息
        $custom = LuckyCustomesModel::create($data);

        return json($custom);
    }

    // 根据手机号查询用户信息
    public function getCustomer($phone){
        $validate=new AddCustomes();
        // dump($validate);die;
        $validate->doCheck();
        $phone = $validate->getDataByRule(input('post.'))['phone'];
        $custom=LuckyCustomesModel::getCustomerByPhone($phone);


        // $custom=LuckyCustomesModel::where('phone', $phone)->find();;
        if(!$custom){
            $lucky_item=$this->getRandItem();
            if(!$lucky_item){

                // return json(false);
                exit;
            }
            $lucky_item_id=$lucky_item->id;
            $item = $lucky_item->item;

            $custom = new LuckyCustomesModel([
                'phone'=>$phone,
                'lucky_status'=>1,
                'lucky_item'=>$item
            ]);
            $custom->save();
            $this->updateItems($lucky_item_id);
            return json([
                'phone'=>$custom->phone,
                'lucky_item'=>$custom->lucky_item,
                'lucky_status'=>$custom->lucky_status
            ]);

            die();
        }
        return json([
            'phone'=>$custom->phone,
            'lucky_item'=>$custom->lucky_item,
            'lucky_status'=>$custom->lucky_status
        ]);
    }

    // 更新中奖状态
    public function updateStatus()
    {
        $validate=new AddCustomes();
        // dump($validate);die;
        $validate->doCheck();
        $phone = input('post.');
        $res = LuckyCustomesModel::where('phone', $phone['phone'])->update(['lucky_status' => 2,'update_time'=>time()]);
        // return LuckyCustomesModel::getLastSql();
        return 1;
    }




    private function time()
    {
        $checkDayStr = date('Y-m-d ', time());
        $w = date("w");
        // $w = 5;
        $timeBegin1 = strtotime("2018-9-7" . "9:30" . ":00");
        $timeEnd1 = strtotime("2018-9-8" . "18:00" . ":00");
        $curr_time = time();
        if ($curr_time >= $timeBegin1 && $curr_time <= $timeEnd1 && $w!='6') {
            return 1;
        }
        return 0;
    }


    // 根据时间设置是否开启抽奖
    public function isStart(){
        $time = $this->time();

        if($time==1){
            return json(true);
        }else{
            return json(false);
        }
    }


    // 随机抽取一个项目
    private function getRandItem(){
        $items = LuckyItemsModel::where('count','>',0)->select();
        if(count($items)){
            $a = rand(0,count($items)-1);
            return $items[$a];
        }else{
            exit;
        }

    }

    // 更新项目数量
    private function updateItems($id){
        $item = LuckyItemsModel::get($id);
        if($item->count>=1){
            $item->count-=1;
            $item->save();
        }
    }

    // 添加客户
    public function AddCustome(){
        $validate=new AddCustomes();
        // dump($validate);die;
        $validate->doCheck();

        $data = input('post.');//获取用户提交的信息

        // $name = $data['name'];
        $custom = LuckyCustomesModel::create($data);
        dump($custom);
    }

    // 查询所有客户
    public function getAll(){
        $res = LuckyCustomesModel::all();
        return $res;
    }
}
