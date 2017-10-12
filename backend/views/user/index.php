<style>
    .container{
        width: 100%;
    }
    table{
        table-layout: fixed;width:1200px  /*<!-- 设置表格中超出部分隐藏的前提，规定表格的宽的 并且设置table-layout: fixed属性 -->*/
    }
    th,td{ /*让单元格中的内容自动居中*/
        text-align: center;
        vertical-align: middle!important;
        white-space: nowrap;text-overflow: ellipsis;overflow: hidden; <!--设置内容超出单元格部分隐藏  -->
    }
</style>
<table  class="table <!--应用表格样式-->
              table-hover <!--鼠标悬停状态作出响应-->
              table-condensed <!--让表格更加紧凑-->
              table-bordered <!--表格和其中的每个单元格增加边框--> ">
    <a href="<?=\yii\helpers\Url::to(['user/add'])?>" class="btn btn-primary">添加管理员</a >&nbsp;&nbsp;
    <tr class="danger <!--第一行背景色设置-->">
        <th  class="col-lg-1">编号</th>
        <th  class="col-lg-2">用户名</th>
        <th  class="col-lg-2">邮箱</th>
        <th  class="col-lg-0.5">账号状态</th>
        <th  class="col-lg-2">创建时间</th>
        <th  class="col-lg-2">更新时间</th>
        <th  class="col-lg-2">最近登录</th>
        <th  class="col-lg-2">管理员</th>
        <th  class="col-lg-2">登录IP</th>
        <th  class="col-lg-1">操作</th>
    </tr>
    <?php foreach ($model as $k=>$v):
        if($k/2==0){  ////////更换列表背景颜色
            echo  '<tr class="info">';
        }else{
            echo  '<tr class="success">';
        }
        ?>
        <th class="col-lg-1"><?=$v->id?></th>
        <th class="col-lg-2" ><?=$v->username?></th>
        <th class="col-lg-2"><?=$v->email?></th>
        <th class="col-lg-0.5"><?=$v->status=1?'正常':'冻结'?></th>
        <th class="col-lg-2"><?=date('Y-m-d H:i:s',$v->created_at)?></th>
        <th class="col-lg-2"><?=date('Y-m-d H:i:s',$v->updated_at)?></th>
        <th class="col-lg-2"><?=date('Y-m-d H:i:s',$v->last_login_time)?></th>
        <th class="col-lg-2"><?=\backend\models\User::getNowUserRole($v->id)==null?'NO':implode(" ",\backend\models\User::getNowUserRole($v->id)); ?></th>
        <th class="col-lg-2"><?=$v->last_login_ip?></th>
        <th class="col-lg-1">
            <?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$v->id],['class'=>'btn btn-info btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$v->id],['class'=>'btn btn-danger btn-xs'])?>
        </th>
        </tr>
    <?php endforeach;  ?>
</table>
