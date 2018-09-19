<?php

namespace App\Http\Controllers;

use App\Http\Model\zfsq_model\Publish_baoxian;
use App\Http\Model\zfsq_model\Publish_chanpin;
use App\Http\Model\zfsq_model\Publish_hunjie;
use App\Http\Model\zfsq_model\Publish_jiatingzhuangxiu;
use App\Http\Model\zfsq_model\Publish_jichu;
use App\Http\Model\zfsq_model\Publish_shengyi;
use App\Http\Model\zfsq_model\Publish_wugong;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PublishInfoController extends Controller
{
    public function checkNamePhone($arr)
    {
        //姓名和电话不能是空
        isset($arr['shoujihao']) ? $phone=$arr['shoujihao'] : $phone=$arr['lianxidianhua'];

        if ($arr['xingming']=='' || $phone=='')
        {
            return false;
        }else
        {
            return true;
        }
    }

    public function ajax()
    {
        switch (Input::get('type'))
        {
            case 'add_jichu':

                $one=null;

                foreach (Input::get('key') as $row)
                {
                    $one[$row['name']]=trim($row['value']);
                }

                if (!$this->checkNamePhone($one)) return ['error'=>'1','msg'=>'姓名和手机号不能是空'];

                Publish_jichu::create($one);

                return ['error'=>'0','msg'=>'插入成功'];

                break;

            case 'select_info_07':

                $cond=null;
                foreach (Input::get('key') as $row)
                {
                    $cond[$row['name']]=trim($row['value']);
                }

                $sql='select xingming,jueseleixing,xingbie,lianxidianhua,congshihangye,id from zbxl_zfsq_jichu where 1=1 ';

                $bind=[];

                $sql.="and jueseleixing=? ";
                array_push($bind,$cond['jueseleixing']);

                $sql.="and congshihangye=? ";
                array_push($bind,$cond['congshihangye']);

                $sql=trim($sql);

                $res=DB::select($sql,$bind);

                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $data[]=$this->object2array($row);
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }else
                {
                    return ['error'=>'1','msg'=>'查无数据'];
                }

                break;

            case 'publish_hunjie':

                $one=null;

                foreach (Input::get('key') as $row)
                {
                    $one[$row['name']]=trim($row['value']);
                }

                if (!$this->checkNamePhone($one)) return ['error'=>'1','msg'=>'姓名和手机号不能是空'];

                Publish_hunjie::create($one);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'select_info_06':

                $cond=null;
                foreach (Input::get('key') as $row)
                {
                    $cond[$row['name']]=trim($row['value']);
                }

                $sql='select xingming,xingbie,shengao,tizhong,chushengnianyue,id from zbxl_zfsq_hunjie where 1=1 ';
                $bind=[];

                if ($cond['chushengnianyue']!='')
                {
                    $chushengnianyue=explode('-',$cond['chushengnianyue']);

                    $start=$chushengnianyue[0];
                    $stop=$chushengnianyue[1];

                    $sql.="and chushengnianyue between ? and ? ";
                    array_push($bind,$start,$stop);
                }

                if ($cond['xingbie']!='')
                {
                    $xingbie=$cond['xingbie'];

                    $sql.="and xingbie=? ";
                    array_push($bind,$xingbie);
                }

                if ($cond['xueli']!='')
                {
                    $xueli=$cond['xueli'];

                    $sql.="and xueli=? ";
                    array_push($bind,$xueli);
                }

                if ($cond['hunyinzhuangtai']!='')
                {
                    $hunyinzhuangtai=$cond['hunyinzhuangtai'];

                    $sql.="and hunyinzhuangtai=? ";
                    array_push($bind,$hunyinzhuangtai);
                }

                if ($cond['shouruqingkuang']!='')
                {
                    $shouruqingkuang=$cond['shouruqingkuang'];

                    $sql.="and shouruqingkuang=? ";
                    array_push($bind,$shouruqingkuang);
                }

                if ($cond['nianlingduan_peiou']!='')
                {
                    $nianlingduan_peiou=$cond['nianlingduan_peiou'];

                    $sql.="and nianlingduan_peiou=? ";
                    array_push($bind,$nianlingduan_peiou);
                }

                if ($cond['zhufangyaoqiu_peiou']!='')
                {
                    $zhufangyaoqiu_peiou=$cond['zhufangyaoqiu_peiou'];

                    $sql.='and zhufangyaoqiu_peiou like ? ';
                    array_push($bind,'%'.$zhufangyaoqiu_peiou.'%');
                }

                if ($cond['hunyinzhuangtai_peiou']!='')
                {
                    $hunyinzhuangtai_peiou=$cond['hunyinzhuangtai_peiou'];

                    $sql.="and hunyinzhuangtai_peiou=? ";
                    array_push($bind,$hunyinzhuangtai_peiou);
                }

                if ($cond['shouruqingkuang_peiou']!='')
                {
                    $shouruqingkuang_peiou=$cond['shouruqingkuang_peiou'];

                    $sql.="and shouruqingkuang_peiou=? ";
                    array_push($bind,$shouruqingkuang_peiou);
                }

                $sql=trim($sql);

                $res=DB::select($sql,$bind);

                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $data[]=$this->object2array($row);
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }else
                {
                    return ['error'=>'1','msg'=>'查无数据'];
                }

                break;

            case 'publish_wugong':

                $one=null;

                foreach (Input::get('key') as $row)
                {
                    $one[$row['name']]=trim($row['value']);
                }

                if (!$this->checkNamePhone($one)) return ['error'=>'1','msg'=>'姓名和手机号不能是空'];

                Publish_wugong::create($one);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'select_info_01':

                $cond=null;
                foreach (Input::get('key') as $row)
                {
                    $cond[$row['name']]=trim($row['value']);
                }

                $sql='select xingming,xingbie,nianling,shoujihao,weixin,id from zbxl_zfsq_wugong where 1=1 ';
                $bind=[];

                if ($cond['nianling']!='')
                {
                    $nianling=explode('-',$cond['nianling']);

                    $start=$nianling[0];
                    $stop=$nianling[1];

                    $sql.="and nianling between ? and ? ";
                    array_push($bind,$start,$stop);
                }

                if ($cond['xiwangwugonghangye']!='')
                {
                    $xiwangwugonghangye=$cond['xiwangwugonghangye'];

                    $sql.="and xiwangwugonghangye=? ";
                    array_push($bind,$xiwangwugonghangye);
                }

                if ($cond['xiwangwugonggongzhong']!='')
                {
                    $xiwangwugonggongzhong=$cond['xiwangwugonggongzhong'];

                    $sql.="and xiwangwugonggongzhong=? ";
                    array_push($bind,$xiwangwugonggongzhong);
                }

                if ($cond['xiwangwugongdidian']!='')
                {
                    $xiwangwugongdidian=$cond['xiwangwugongdidian'];

                    $sql.="and xiwangwugongdidian=? ";
                    array_push($bind,$xiwangwugongdidian);
                }

                if ($cond['xiwangxinzifanwei']!='')
                {
                    $xiwangxinzifanwei=$cond['xiwangxinzifanwei'];

                    $sql.="and xiwangxinzifanwei=? ";
                    array_push($bind,$xiwangxinzifanwei);
                }

                $sql=trim($sql);

                $res=DB::select($sql,$bind);

                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $data[]=$this->object2array($row);
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }else
                {
                    return ['error'=>'1','msg'=>'查无数据'];
                }

                break;

            case 'publish_shengyi':

                $one=null;

                foreach (Input::get('key') as $row)
                {
                    $one[$row['name']]=trim($row['value']);
                }

                if (!$this->checkNamePhone($one)) return ['error'=>'1','msg'=>'姓名和手机号不能是空'];

                Publish_shengyi::create($one);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'select_info_02':

                $cond=null;
                foreach (Input::get('key') as $row)
                {
                    $cond[$row['name']]=trim($row['value']);
                }

                $sql='select xingming,xingbie,nianling,shoujihao,weixin,id from zbxl_zfsq_shengyi where 1=1 ';
                $bind=[];

                if ($cond['nianling']!='')
                {
                    $nianling=explode('-',$cond['nianling']);

                    $start=$nianling[0];
                    $stop=$nianling[1];

                    $sql.="and nianling between ? and ? ";
                    array_push($bind,$start,$stop);
                }

                if ($cond['shengyisuoshuhangye']!='')
                {
                    $shengyisuoshuhangye=$cond['shengyisuoshuhangye'];

                    $sql.="and shengyisuoshuhangye=? ";
                    array_push($bind,$shengyisuoshuhangye);
                }

                if ($cond['shengyisuoshuleibie']!='')
                {
                    $shengyisuoshuleibie=$cond['shengyisuoshuleibie'];

                    $sql.="and shengyisuoshuleibie=? ";
                    array_push($bind,$shengyisuoshuleibie);
                }

                $sql=trim($sql);

                $res=DB::select($sql,$bind);

                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $data[]=$this->object2array($row);
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }else
                {
                    return ['error'=>'1','msg'=>'查无数据'];
                }

                break;

            case 'publish_baoxian':

                $one=null;

                foreach (Input::get('key') as $row)
                {
                    $one[$row['name']]=trim($row['value']);
                }

                if (!$this->checkNamePhone($one)) return ['error'=>'1','msg'=>'姓名和手机号不能是空'];

                Publish_baoxian::create($one);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'select_info_03':

                $cond=null;
                foreach (Input::get('key') as $row)
                {
                    $cond[$row['name']]=trim($row['value']);
                }

                $sql='select xingming,xingbie,nianling,shoujihao,weixin,id from zbxl_zfsq_baoxian where 1=1 ';
                $bind=[];

                if ($cond['xiwanggoumaibaoxiangongsi']!='')
                {
                    $xiwanggoumaibaoxiangongsi=$cond['xiwanggoumaibaoxiangongsi'];

                    $sql.="and xiwanggoumaibaoxiangongsi=? ";
                    array_push($bind,$xiwanggoumaibaoxiangongsi);
                }

                if ($cond['xiwanggoumaibaoxianleibie']!='')
                {
                    $xiwanggoumaibaoxianleibie=$cond['xiwanggoumaibaoxianleibie'];

                    $sql.="and xiwanggoumaibaoxianleibie=? ";
                    array_push($bind,$xiwanggoumaibaoxianleibie);
                }

                $sql=trim($sql);

                $res=DB::select($sql,$bind);

                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $data[]=$this->object2array($row);
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }else
                {
                    return ['error'=>'1','msg'=>'查无数据'];
                }

                break;

            case 'publish_chanpin':

                $one=null;

                foreach (Input::get('key') as $row)
                {
                    $one[$row['name']]=trim($row['value']);
                }

                if (!$this->checkNamePhone($one)) return ['error'=>'1','msg'=>'姓名和手机号不能是空'];

                Publish_chanpin::create($one);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'select_info_04':

                $cond=null;
                foreach (Input::get('key') as $row)
                {
                    $cond[$row['name']]=trim($row['value']);
                }

                $sql='select xingming,beizhu,QQ,shoujihao,weixin,id from zbxl_zfsq_chanpin where 1=1 ';
                $bind=[];

                if ($cond['chanpinleixing']!='')
                {
                    $chanpinleixing=$cond['chanpinleixing'];

                    $sql.="and chanpinleixing=? ";
                    array_push($bind,$chanpinleixing);
                }

                $sql=trim($sql);

                $res=DB::select($sql,$bind);

                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $data[]=$this->object2array($row);
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }else
                {
                    return ['error'=>'1','msg'=>'查无数据'];
                }

                break;

            case 'publish_jiatingzhuangxiu':

                $one=null;

                foreach (Input::get('key') as $row)
                {
                    $one[$row['name']]=trim($row['value']);
                }

                if (!$this->checkNamePhone($one)) return ['error'=>'1','msg'=>'姓名和手机号不能是空'];

                Publish_jiatingzhuangxiu::create($one);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'select_info_05':

                $cond=null;
                foreach (Input::get('key') as $row)
                {
                    $cond[$row['name']]=trim($row['value']);
                }

                $sql='select xingming,beizhu,QQ,shoujihao,weixin,id from zbxl_zfsq_jiatingzhuangxiu where 1=1 ';
                $bind=[];

                if ($cond['zhuangxiusuozaiweizhi']!='')
                {
                    $zhuangxiusuozaiweizhi=$cond['zhuangxiusuozaiweizhi'];

                    $sql.="and zhuangxiusuozaiweizhi=? ";
                    array_push($bind,$zhuangxiusuozaiweizhi);
                }

                if ($cond['zhuangxiuleixing']!='')
                {
                    $zhuangxiuleixing=$cond['zhuangxiuleixing'];

                    $sql.="and zhuangxiuleixing=? ";
                    array_push($bind,$zhuangxiuleixing);
                }

                if ($cond['shifouxiwangfenqi']!='')
                {
                    $shifouxiwangfenqi=$cond['shifouxiwangfenqi'];

                    $sql.="and shifouxiwangfenqi=? ";
                    array_push($bind,$shifouxiwangfenqi);
                }

                $sql=trim($sql);

                $res=DB::select($sql,$bind);

                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $data[]=$this->object2array($row);
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }else
                {
                    return ['error'=>'1','msg'=>'查无数据'];
                }

                break;

        }
    }







    public function show_jichu($id)
    {
        $res=Publish_jichu::find($id);

        return view('zhongfushequ.publish_info.jichu_xiangxi',compact('res'));
    }

    public function hunjie()
    {
        return view('zhongfushequ.publish_info.hunjie');
    }

    public function show_hunjie($id)
    {
        $res=Publish_hunjie::find($id);

        return view('zhongfushequ.publish_info.hunjie_xiangxi',compact('res'));
    }

    public function wugong()
    {
        return view('zhongfushequ.publish_info.wugong');
    }

    public function show_wugong($id)
    {
        $res=Publish_wugong::find($id);

        return view('zhongfushequ.publish_info.wugong_xiangxi',compact('res'));
    }

    public function shengyi()
    {
        return view('zhongfushequ.publish_info.shengyi');
    }

    public function show_shengyi($id)
    {
        $res=Publish_shengyi::find($id);

        return view('zhongfushequ.publish_info.shengyi_xiangxi',compact('res'));
    }

    public function baoxian()
    {
        return view('zhongfushequ.publish_info.baoxian');
    }

    public function show_baoxian($id)
    {
        $res=Publish_baoxian::find($id);

        return view('zhongfushequ.publish_info.baoxian_xiangxi',compact('res'));
    }

    public function chanpin()
    {
        return view('zhongfushequ.publish_info.chanpin');
    }

    public function show_chanpin($id)
    {
        $res=Publish_chanpin::find($id);

        return view('zhongfushequ.publish_info.chanpin_xiangxi',compact('res'));
    }

    public function jiatingzhuangxiu()
    {
        return view('zhongfushequ.publish_info.jiatingzhuangxiu');
    }

    public function show_jiatingzhuangxiu($id)
    {
        $res=Publish_jiatingzhuangxiu::find($id);

        return view('zhongfushequ.publish_info.jiatingzhuangxiu_xiangxi',compact('res'));
    }


}
