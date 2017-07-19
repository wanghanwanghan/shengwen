<?php

namespace App\Http\Controllers;

use App\Http\Model\ConfirmTypeModel;
use App\Http\Model\CustModel;
use App\Http\Model\LevelModel;
use App\Http\Model\LogModel;
use App\Http\Model\PhoneBelongModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\StaffModel;
use App\Http\Model\VocalPrintModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use League\Flysystem\Exception;
use phpDocumentor\Reflection\Types\Array_;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //为字符串的指定位置添加指定字符中的调用函数
    public function mb_substr_replace($string, $replacement, $start, $length=NULL) {
        if (is_array($string)) {
            $num = count($string);
            // $replacement
            $replacement = is_array($replacement) ? array_slice($replacement, 0, $num) : array_pad(array($replacement), $num, $replacement);
            // $start
            if (is_array($start)) {
                $start = array_slice($start, 0, $num);
                foreach ($start as $key => $value)
                    $start[$key] = is_int($value) ? $value : 0;
            }
            else {
                $start = array_pad(array($start), $num, $start);
            }
            // $length
            if (!isset($length)) {
                $length = array_fill(0, $num, 0);
            }
            elseif (is_array($length)) {
                $length = array_slice($length, 0, $num);
                foreach ($length as $key => $value)
                    $length[$key] = isset($value) ? (is_int($value) ? $value : $num) : 0;
            }
            else {
                $length = array_pad(array($length), $num, $length);
            }
            // Recursive call
            return array_map(__FUNCTION__, $string, $replacement, $start, $length);
        }
        preg_match_all('/./us', (string)$string, $smatches);
        preg_match_all('/./us', (string)$replacement, $rmatches);
        if ($length === NULL) $length = mb_strlen($string);
        array_splice($smatches[0], $start, $length, $rmatches[0]);
        return join($smatches[0]);
    }
    //为字符串的指定位置添加指定字符
    public function insert_something(&$str, array $offset, $delimiter = '-') {
        foreach ($offset as $i => $v) {
            $str = $this->mb_substr_replace($str, $delimiter, $i + $v, 0);
        }
        return $str;
    }

    //中文字符串包含 source源字符串target要判断的是否包含的字符串
    public function hasstring($source,$target)
    {
        preg_match_all("/$target/sim", $source, $strResult, PREG_PATTERN_ORDER);
        return !empty($strResult[0]);
    }

    //手机号码归属地查询
    public function is_local_phone($phone)
    {
        //先到数据库中查
        $res=PhoneBelongModel::where(['phone'=>$phone])->get()->toArray();

        //是否找到
        if (empty($res))
        {
            //没找到
            $res_json=file_get_contents('http://apis.juhe.cn/mobile/get?phone='.$phone.'&dtype=json&key=e12158fa89c0f7a14b363d8cde0ad6c6');
            $res_arry=json_decode($res_json,true);
            $province=$res_arry['result']['province'];
            $city=$res_arry['result']['city'];
            $company=$res_arry['result']['company'];
            PhoneBelongModel::create(['province'=>$province,'city'=>$city,'company'=>$company,'phone'=>$phone]);

            //判断是否属于本地
            if ($this->hasstring($province,Config::get('constant.province')))
            {
                if ($city=='')
                {
                    //省名和市名如果是一样的，city也许是空，比如北京，上海，天津，重庆
                    return ['local'=>'yes'];
                }else
                {
                    if ($this->hasstring($city,Config::get('constant.city')))
                    {
                        return ['local'=>'yes'];
                    }else
                    {
                        return ['local'=>'no'];
                    }
                }
            }else
            {
                return ['local'=>'no'];
            }
        }else
        {
            //找到了
            foreach ($res as $row)
            {
                //判断是否属于本地
                if ($this->hasstring($row['province'],Config::get('constant.province')))
                {
                    if ($row['city']=='')
                    {
                        //省名和市名如果是一样的，city也许是空，比如北京，上海
                        return ['local'=>'yes'];
                    }else
                    {
                        if ($this->hasstring($row['city'],Config::get('constant.city')))
                        {
                            return ['local'=>'yes'];
                        }else
                        {
                            return ['local'=>'no'];
                        }
                    }
                }else
                {
                    return ['local'=>'no'];
                }
            }
        }
    }

    //ip地址查询
    public function is_local_IP_address($ip)
    {
        if (Redis::get('is_local_IP_address')!='')
        {
            $res_arry=json_decode(Redis::get('is_local_IP_address'),true);
            return $res_arry['result'];
        }

        $res_json=file_get_contents('http://apis.juhe.cn/ip/ip2addr?ip='.$ip.'&dtype=json&key=ffb7c65113fddc659264139050eaccf2');
        $res_arry=json_decode($res_json,true);
        if ($res_arry['error_code']!='0' || $res_arry['resultcode']!='200')
        {
            return ['area'=>'查询失败','location'=>'loading...'];
        }else
        {
            $this->redis_set('is_local_IP_address',$res_json,3600);
            return $res_arry['result'];
        }
    }

    //curl操作
    public function mycurl($url,$data)
    {
        $curl=curl_init();//初始化
        curl_setopt($curl,CURLOPT_URL,$url);//设置请求地址
        curl_setopt($curl,CURLOPT_POST,true);//设置post方式请求
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);//几秒后没链接上就自动断开
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
        $data=json_encode($data);//转换成json
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//提交的数据
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);//返回值不直接显示
        $res=curl_exec($curl);//发送请求
        if(curl_errno($curl))//判断是否有错
        {
            $msg=null;
            $msg=curl_error($curl);
            curl_close($curl);//释放
            return ['error'=>'1','msg'=>$msg];
        }else
        {
            curl_close($curl);//释放
            return ['error'=>'0','msg'=>$res];
        }
    }

    //设置有存活时间的redis，参数一：键，参数二：值，参数三：存活时间，单位秒
    public function redis_set($key,$value,$time='')
    {
        if ($time!='')
        {
            Redis::set($key,$value);
            Redis::expire($key,$time);
        }else
        {
            Redis::set($key,$value);
        }
    }

    //链接mongodb
    public function mymongo()
    {
        static $mongo=null;

        if ($mongo==null)
        {
            $con="mongodb://127.0.0.1:27017";
            $option['connectTimeoutMS']='1000';
            $mongo=new \MongoClient($con,$option);

            return $mongo;
        }else
        {
            return $mongo;
        }
    }

    //系统操作日志，参数一：执行了什么操作，参数二：操作细节
    public function system_log($action,$detail)
    {
        $log_info=null;

        foreach (Session::get('user') as $row)
        {
            $log_info['log_account']=$row['staff_account'];
        }

        $log_info['log_todo']=$action;

        $log_info['log_detail']=$detail;

        LogModel::create($log_info);
    }

    //修改或删除声纹文件，参数一：客户主键，参数二：操作modify、delete，参数三：条件['phone'=>'13800138000']
    public function voice_file_ModifyOrDelete($pid,$oper,$cond='')
    {
        $phone=CustModel::find($pid)->cust_review_num;
        $id=CustModel::find($pid)->cust_id;

        if ($oper=='delete')
        {
            $mkdir=date('Ymd',time());

            system('mkdir '.Config::get('constant.voice_remove_path').$mkdir);

            system('mv '.Config::get('constant.voice_path').$phone.'_'.$id.'*'.' '.Config::get('constant.voice_remove_path').$mkdir.'/');

            return;

        }elseif ($oper=='modify')
        {
            foreach ($cond as $key=>$value)
            {
                if ($key=='phone')
                {
                    //改录音文件
                    system('cd '.Config::get('constant.voice_path').';'.'rename '.$phone.' '.$value.' *');

                    //改模型文件文件
                    system('cd '.Config::get('constant.model_path').';'.'rename '.$phone.' '.$value.' *');

                    return;
                }
            }





        }else
        {
            return '参数错误';
        }


    }

    //查询所有父节点
    public function select_allproject_parent($pid)
    {
        while (1)
        {
            $tmp=ProjectModel::find($pid);
            if ($tmp->project_parent=='0')
            {
                $name[$tmp->project_id]=$tmp->project_name;
                break;
            }else
            {
                $name[$tmp->project_id]=$tmp->project_name;
                $pid=$tmp->project_parent;
            }
        }

        return $name;
    }

    //是否有权限查看当前地区数据
    public function check_project_select_permission($pid)
    {
        //得到一个主键，查看这个地区，当前登录的用户是否有
        //如果有，返回true
        //如果没有，则遍历出该主键的所有父节点，当前登录的用户是否含有某一个父节点
        //如果有，返回true
        //如果没有，返回false
        $user=Session::get('user');
        $user_pid=$user[0]['staff_num'];
        $user_project=StaffModel::find($user_pid)->staff_project;

        //直接判断当前用户是否含有
        if (in_array($pid,explode(',',$user_project)))
        {
            return true;
        }

        //找到该节点的所有父节点
        $name=$this->select_allproject_parent($pid);

        //当前登录用户的所属地区时候含有当前这个$pid的父节点
        foreach (explode(',',$user_project) as $row)
        {
            if (in_array($row,array_keys($name)))
            {
                return true;
            }
        }

        //如果也不含有
        return false;
    }

    //判断当前用户是不是有权限添加到当前选择的区域，参数是要插入地区的主键
    public function before_insert_check_projectlevel($id,$type=0)
    {
        //把当前用户的所有区域都遍历出来，然后查看是不是含有传进来的区域，如果没有就返回无权限添加
        $user_project=Session::get('user');
        $user_project=explode(',',$user_project[0]['staff_project']);

        //一个一个遍历出所有的子节点
        foreach ($user_project as $one)
        {
            $res_one=$this->get_all_children($one);

            if (empty($res_one))
            {
                //是空就说明当前节点没有子节点
            }else
            {
                foreach ($res_one as $myv)
                {
                    $res_all[]=$myv['project_id'];
                }
            }

            $res_all[]=$one;
        }

        $res_all=array_unique($res_all);

        if ($type=='1')
        {
            return $res_all;
        }

        //检查子节点
        if (in_array($id,$res_all))
        {
            return true;
        }else
        {
            return false;
        }
    }

    //取出所有该节点的子节点
    public function get_all_children($parent_id)
    {
        if (Redis::get('all_project')=='')
        {
            //取出所有地区放入redis
            $all_project=ProjectModel::get(['project_id','project_name','project_parent'])->toArray();
            $this->redis_set('all_project',json_encode($all_project),60);
        }else
        {
            $all_project=json_decode(Redis::get('all_project'),true);
        }

        //查询子节点
        return $this->get_children($all_project,$parent_id);
    }

    //无限分类函数
    public function get_children($arrCat,$parent_id=0,$level=0)
    {
        static $arrTree=[];
        if (empty($arrCat)) return FALSE;
        $level++;
        foreach ($arrCat as $key=>$value)
        {
            if ($value['project_parent']==$parent_id)
            {
                $value['level']=$level;
                $arrTree[]=$value;
                unset($arrCat[$key]); //注销当前节点数据，减少已无用的遍历
                $this->get_children($arrCat,$value['project_id'],$level);
            }
        }
        return $arrTree;
    }

    //无限分类函数
    public function infinite($list,$profix,$parentid=0)
    {
        //第一次进来children里是最顶级父类
        $children=$this->findchildren($list,$profix,$parentid);

        if(empty($children))
        {
            return null;
        }

        foreach ($children as $k => $v)
        {
            $jstree=$this->infinite($list,$profix,$v[$profix.'_id']);

            if($jstree != null)
            {
                $children[$k]['children']=$jstree;
            }
        }
        return $children;
    }
    //无限分类函数的附属函数
    public function findchildren($arr,$profix,$id)
    {
        $children=[];

        foreach ($arr as $v)
        {
            if($v[$profix.'_parent']==$id)
            {
                $children[]=$v;
            }
        }
        return $children;
    }

    //修改一维或多维数组的键名，参数一：需要修改的数组，参数二：['从什么'=>'改成什么']
    public function change_arr_key($arr,$example)
    {
        $res = [];
        foreach ($arr as $key => $value)
        {
            if (is_array($value))
            {
                if (array_key_exists($key,$example))
                {
                    $key = $example[$key];
                }
                $res[$key] = $this->change_arr_key($value,$example);
            }else
            {
                if (array_key_exists($key,$example))
                {
                    $res[$example[$key]] = $value;
                }else
                {
                    $res[$key] = $value;
                }
            }
        }
        return $res;
    }

    //判断是不是中国字
    public function check_chinese_word($word)
    {
        if (preg_match('/[\xe0-\xef][\x80-\xbf]/',$word))
        {
            //检查空格
            if (strpos($word,' ')===false)
            {
                return '1';
            }else
            {
                return '0';
            }
        }else
        {
            return '0';
        }
    }

    //判断身份证是否正确
    public function is_idcard($id)
    {
        $id = strtoupper($id);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id))
        {
            return FALSE;
        }
        if(15==strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth))
            {
                return FALSE;
            } else {
                return TRUE;
            }
        }
        else      //检查18位
        {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)) //检查生日日期是否正确
            {
                return FALSE;
            }
            else
            {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1))
                {
                    return FALSE;
                } //phpfensi.com
                else
                {
                    return TRUE;
                }
            }
        }
    }

    //判断身份证是否正确
    public function validation_filter_id_card($id_card){
        if(strlen($id_card)==18){
            return $this->idcard_checksum18($id_card);
        }elseif((strlen($id_card)==15)){
            $id_card=$this->idcard_15to18($id_card);
            return $this->idcard_checksum18($id_card);
        }else{
            return false;
        }
    }
    //计算身份证校验码，根据国家标准GB 11643-1999
    public function idcard_verify_number($idcard_base){
        if(strlen($idcard_base)!=17){
            return false;
        }
        //加权因子
        $factor=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        //校验码对应值
        $verify_number_list=array('1','0','X','9','8','7','6','5','4','3','2');
        $checksum=0;
        for($i=0;$i<strlen($idcard_base);$i++){
            $checksum += substr($idcard_base,$i,1) * $factor[$i];
        }
        $mod=$checksum % 11;
        $verify_number=$verify_number_list[$mod];
        return $verify_number;
    }
    //将15位身份证升级到18位
    public function idcard_15to18($idcard){
        if(strlen($idcard)!=15){
            return false;
        }else{
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if(array_search(substr($idcard,12,3),array('996','997','998','999')) !== false){
                $idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
            }else{
                $idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
            }
        }
        $idcard=$idcard.$this->idcard_verify_number($idcard);
        return $idcard;
    }
    //18位身份证校验码有效性检查
    public function idcard_checksum18($idcard){
        if(strlen($idcard)!=18){
            return false;
        }
        $idcard_base=substr($idcard,0,17);
        if($this->idcard_verify_number($idcard_base)!=strtoupper(substr($idcard,17,1))){
            return false;
        }else{
            return true;
        }
    }

    //判断纯数字，纯字母，数字字母混合，参数一：字符串，参数二：'phonenumber'，''，参数三：位数
    public function check_something($str,$type,$length)
    {
        if ($type=='phonenumber')
        {
            if (preg_match("/^1[34578]{1}\d{9}$/",$str))
            {
                return '1';
            }else
            {
                return '0';
            }

        }elseif ($type=='w')
        {

        }elseif ($type=='nw' || $type=='wn')
        {

        }else
        {

        }
    }

    public function arr2str($arr)
    {
        $res='0';
        foreach ($arr as $row)
        {
            $res=$res.','.$row['id'];
        }

        return $res;
    }

    public function arr2str_new($arr)
    {
        foreach ($arr as $row)
        {
            if (!isset($res))
            {
                $res=$row['id'];
            }else
            {
                $res=$res.','.$row['id'];
            }
        }

        return $res;
    }

    //laravel的链接查询没研究会，先用原生的吧...
    public function mypdo($sql)
    {
        $dbh=new \PDO('mysql:host=127.0.0.1;dbname=shengwen','root','root');

        $res=$dbh->query($sql);

        $res->setFetchMode(\PDO::FETCH_ASSOC);

        $final=null;

        while ($row=$res->fetch())
        {
            $final[]=$row;
        }

        return $final;
    }

    //传入的参数是数组里面包裹着对象
    public function obj2arr($obj)
    {
        $arr=null;

        foreach ($obj as $row)
        {
            //$row是对象
            $arr[]=get_object_vars($row);
        }

        return $arr;
    }

    //产生不重复的随机数
    public function myrand()
    {
        for ($i=1;$i<=Config::get('confirm_type.repeat');$i++)
        {
            $new=rand(100000,999999);
            $rand_num[]=rand(100000,999999);
        }
    }























}
