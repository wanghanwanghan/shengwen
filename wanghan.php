<?php
function dd($input)
{
    echo "<pre>";
    print_r($input);
}

//1000以内的素数
for($i=2;$i<=1000;$i++)
{
    $primes=0;

    for ($k=1;$k<=$i;$k++)
    {
        if ($i%$k==0) $primes++;

        if ($primes=='3') break;
    }

    if ($primes=='2')
    {
        //echo "$i<br/>";
    }
}

//1,2,3,5,8,13,21,34,55,89找出规律求出第30个数字是啥
function fibonacci($pos)
{
    //求出第n位数字
    if($pos<=0) return 0;

    if($pos==1 || $pos==2) return 1;

    return fibonacci($pos-1)+fibonacci($pos-2);
}

//PHP是弱语言类型，主要分为三类：
//1、标量类型：integer、string、float、boolean
//2、复合类型：array、object
//3、特殊类型：resource、null

//*****************************************************************************************************************
$db=new PDO('mysql:host=localhost;dbname=shengwen','root','root');

try
{
    //PDO链接mysql
    foreach ($db->query('select * from zbxl_level') as $row)
    {
        //print_r($row);
    }

    $db=null;

}catch (PDOException $e)
{
    echo $e->getMessage();
}
//*****************************************************************************************************************
$db=new mysqli('localhost','root','root','shengwen');

if ($db->connect_errno)
{
    echo "链接失败,$db->connect_error";
    exit();
}

$sql='select * from zbxl_level';

$query=$db->query($sql);

while ($row=$query->fetch_array())
{
    //print_r($row);
}

//释放结果集+关闭MySQL连接
$query->free_result();
$db->close();
//*****************************************************************************************************************

//三范式
//1NF:字段不可分;
//2NF:有主键，非主键字段依赖主键;
//3NF:非主键字段不能相互依赖;
//解释:
//1NF:原子性 字段不可再分,否则就不是关系数据库;
//2NF:唯一性 一个表只说明一个事物;
//3NF:每列都与主键有直接关系，不存在传递依赖;

//http状态码
//1XX	100-101	信息提示
//2XX	200-206	成功
//3XX	300-305	重定向
//4XX	400-415	客户端错误
//5XX	500-505	服务器错误

//200 OK 服务器成功处理了请求（这个是我们见到最多的）
//301/302 Moved Permanently（重定向）请求的URL已移走。Response中应该包含一个Location URL, 说明资源现在所处的位置
//304 Not Modified（未修改）客户的缓存资源是最新的， 要客户端使用缓存
//403 Forbidden
//404 Not Found 未找到资源
//501 Internal Server Error服务器遇到一个错误，使其无法对请求提供服务

$count=5;
function get_count()
{
    static $count=0;
    return $count++;
}
//echo $count;
//echo "<br/>";
//++$count;
//echo get_count();
//echo "<br/>";
//echo get_count();

//*****************************************************************************************************************
//php取出文件扩展名
$str='dir/upload.image.jpg';
function getExt2($filename)
{
    $ext = strrchr($filename,'.');
    return $ext;
}
//*****************************************************************************************************************
function getExt3($filename)
{
    $pos = strrpos($filename, '.');
    $ext = substr($filename, $pos);
    return $ext;
}
//*****************************************************************************************************************
function getExt4($filename)
{
    $arr = pathinfo($filename);
    $ext = $arr['extension'];
    return $ext;
}
//*****************************************************************************************************************

//print_r(getExt4($str));

$res=array_filter(['','123','321']);

dd($res);


