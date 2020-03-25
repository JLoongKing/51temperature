<?php  
namespace app\index\controller;  
use \think\Request;
use \think\Db;
use think\Controller; 
//控制器自定义基类，自定义返回界面，以及普通增删改查 
Abstract class Base  extends Controller  
{  

    //自定义返回界面
    function review($viewname=''){
        if($viewname==''){
            $this->error("没有这个界面！");
        }
        $view = new \think\View();
        return $view->fetch($viewname);
    }
    
    //自定义增加方法,$tablename:表名，$data:数据：
    //            格式为：$data = ['key1' => value1, 'key2' => 'value2'];
    function add(){
        $tablename= Request::instance()->post('tablename');
        $data= Request::instance()->post('data/a');
        //$data=json_decode($data,true); 
        if($tablename==''){
            return json(["code"=>"401","info"=>"表名为空"]);
        }
        if($data==''){
            return json(["code"=>"401","info"=>"数据为空"]);
        }
        $model=Db::table($tablename)->insert($data);
        return json(["code"=>"400","info"=>"上传成功！"]);
    } 
    
    //自定义删除方法,$tablename:表名，$fieldname:字段名，$fieldvalue:字段值
    function delete(){
        $tablename= Request::instance()->post('tablename');
        $fieldname= Request::instance()->post('fieldname');
        $fieldvalue= Request::instance()->post('fieldvalue');
        if($tablename==''||$fieldname==''||$fieldvalue==''){
            return json(["code"=>"1401","info"=>"字段为空"]);
        }
        $model=Db::table($tablename)->where($fieldname,$fieldvalue)->delete();
        return json($model);
    }

    //自定义修改方法,$tablename:表名，$fieldname:字段名，$fieldvalue:字段值，$data:更新数据
    //             数据格式为：['name' => 'thinkphp']
    function update(){
        $tablename= Request::instance()->post('tablename');
        $fieldname= Request::instance()->post('fieldname');
        $fieldvalue= Request::instance()->post('fieldvalue');
        $data= Request::instance()->post('data/a');
        if($tablename==''||$fieldname==''||$fieldvalue==''||$data==''){
            return json(["code"=>"1401","info"=>"字段为空"]);
        }
        $model=Db::table($tablename)->where($fieldname, $fieldvalue)->update($data);
        
        return json(["code"=>"400","info"=>"修改成功！"]);
    }
     //自定义修改方法,$tablename:表名，$field:条件，$data:更新数据
    //             数据格式为：['name' => 'thinkphp']
    function alert(){
        $tablename= Request::instance()->post('tablename');
        $field= Request::instance()->post('field');
        $data= Request::instance()->post('data/a');
        if($tablename==''||$field==''||$data==''){
            return json(["code"=>"1401","info"=>"字段为空"]);
        }
        $model=Db::table($tablename)->where($field)->update($data);
        
        return json(["code"=>"400","info"=>"修改成功！"]);
    }
   
   
     //自定义查找方法,$tablename:表名，$sql:查询条件,limit：分页条件
     function select(){
        $tablename= Request::instance()->post('tablename');
        $sql= Request::instance()->post('sql');
        //将条件由{"id":">,72"}改变为["id"=>">,72"]数组格式
        $sql=json_decode($sql,true); 
        $limit= Request::instance()->post('limit');
        //表名为空没法查，必然返回错误
        if($tablename==''){
            return json(["code"=>"401","info"=>"表名为空"]);
        }
        //无查询条件，也返回错误
        if($sql==null&&$limit==""){
            return json(["code"=>"401","info"=>"条件为空"]);
        }
        //无条件的分页查询
        if($sql==null){
            $model=Db::table($tablename)->limit($limit)->select();
            return json($model);
        }
        //有条件的不分页查询
        if($limit==''){
            //获取所有的key值，value会是这种格式>,72"，
            //改变value为['like','%thinkphp']数组格式
            $keys=array_keys($sql);
            foreach ($keys as $key) {
                $sql[$key]=explode(",",$sql[$key]);
            }
            //进行查询
            $model=Db::table($tablename)->where($sql)->select();
            return json($model);
        }
        //有条件的分页查询
        //获取所有的key值，value会是这种格式{"id":">,72"}，
        //改变value为['like','%thinkphp']数组格式
        $keys=array_keys($sql);
        foreach ($keys as $key) {
            $sql[$key]=explode(",",$sql[$key]);
        }
        //进行查询
        $model=Db::table($tablename)->where($sql)->limit($limit)->select();
        return json($model);
    }
    //自定义查找方法,$tablename:表名，$sql:查询条件,limit：分页条件
    function selectorder(){
        $tablename= Request::instance()->post('tablename');
        $order= Request::instance()->post('order');
        $sql= Request::instance()->post('sql');
        //将条件由{"id":">,72"}改变为["id"=>">,72"]数组格式
        $sql=json_decode($sql,true); 
        $limit= Request::instance()->post('limit');
        //表名为空没法查，必然返回错误
        if($tablename==''){
            return json(["code"=>"401","info"=>"表名为空"]);
        }
        //有条件的分页查询
        //获取所有的key值，value会是这种格式{"id":">,72"}，
        //改变value为['like','%thinkphp']数组格式
        if($sql!=""){
              $keys=array_keys($sql);
        foreach ($keys as $key) {
            $sql[$key]=explode(",",$sql[$key]);
        }
        }
      
        //进行查询
        $model=Db::table($tablename)->where($sql)->order($order)->limit($limit)->select();
        return json($model);
    }
    
     //自定义查找方法,$tablename:表名，$sql:查询条件
     function selectnum(){
        $tablename= Request::instance()->post('tablename');
        $sql= Request::instance()->post('sql');
        //将条件由{"id":">,72"}改变为["id"=>">,72"]数组格式
        $sql=json_decode($sql,true); 
       
        //表名为空没法查，必然返回错误
        if($tablename==''){
            return json(["code"=>"401","info"=>"表名为空"]);
        }
        //无条件获取所有数量
        if($sql==null){
            $model=Db::table($tablename)->count();
            return json($model);
        }
        //获取所有的key值，value会是这种格式>,72"，
        //改变value为['like','%thinkphp']数组格式
        $keys=array_keys($sql);
        foreach ($keys as $key) {
            $sql[$key]=explode(",",$sql[$key]);
        }
        //进行查询
        $model=Db::table($tablename)->where($sql)->count();
        return json($model);
    
    }
      //自定义文件上传方法
    function upfile(){
     
        $img_path="/tp50/public/static/upfile";
        $images_name = '';  
        $img_name = time();  
        foreach($_FILES['image_data']['tmp_name'] as$k=>$v)  
        {  
            move_uploaded_file($v,$img_path.$img_name.$k.'.jpg');  
            $images_name  .=  $img_name.$k.'.jpg'.',';  
        }  
          
        return $images_name; //这个返回值必须要  
    }
} 
 