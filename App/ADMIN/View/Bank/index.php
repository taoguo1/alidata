<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Bank')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>银行名字：</label> <input type="text" name="name" value="<?=\Core\Lib::request('name')?>" /></li>
                <li>
                    <label>是否支持：</label>
                    <select name="ybskb">
                        <option value="">全部</option>
                        <?php
                        foreach ($dictionary['userPush'] as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('ybskb')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>状态：</label>
                    <select name="status">
                        <option value="">全部</option>
                        <?php
                        foreach ($dictionary['userState'] as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('status')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                
                 <!--  <li>
                    <label>类型：</label>
                    <select name="bank_type">
                        <option value="">全部</option>
                        <?php
                        foreach ($dictionary['cardType'] as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('bank_type')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li> -->
                
                <li><label>时间查询：</label>
                    <input type="text" class="date" size="10" name="start_date"
                           value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date"
                           value="<?= \Core\Lib::request('end_date') ?>"/>
                </li>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">查询</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
      <ul class="toolBar">
			<li><a class="add" rel="BankAdd" title="添加银行" href="<?=\Core\Lib::getUrl('Bank', 'add');?>" width="550" height="600" target="dialog"><span>添加</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Bank', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a class="edit"  title="编辑银行" rel="Bankedit" href="<?=\Core\Lib::getUrl('Bank', 'edit','id={id}');?>" target="dialog"><span>编辑</span></a></li>
			<li><a title="确定要更新吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Bank', 'updateConfig');?>" class="edit"><span>更新配置</span></a></li> 
		</ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
         <th align="center"  orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">ID</th>
            <th align="center" orderField="name" class="<?=($data['orderField']=='name')?$data['orderDirection']:'';?>">银行名字</th>
            <th align="center" orderField="code_hlb" class="<?=($data['orderField']=='code_hlb')?$data['orderDirection']:'';?>">银行编码</th>
            <!--<th align="center" orderField="code_yb" class="<?=($data['orderField']=='code_yb')?$data['orderDirection']:'';?>">code_yb</th>
            <th align="center" orderField="code_hlb" class="<?=($data['orderField']=='code_hlb')?$data['orderDirection']:'';?>">code_hlb</th>-->
            <th align="center" orderField="logo" class="<?=($data['orderField']=='logo')?$data['orderDirection']:'';?>">logo</th>
            <th align="center" orderField="back_image" class="<?=($data['orderField']=='back_image')?$data['orderDirection']:'';?>">back_image</th>
            <th align="center" orderField="ybskb" class="<?=($data['orderField']=='ybskb')?$data['orderDirection']:'';?>">是否支持</th>
            <th align="center" orderField="status" class="<?=($data['orderField']=='status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="bank_type" class="<?=($data['orderField']=='bank_type')?$data['orderDirection']:'';?>">卡类型</th>
            <th align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">时间</th>
          
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['name']?></td>
                <td align="center"><?=$v['code_hlb']?></td>
               <!-- <td align="center"><?=$v['code_yb']?></td>-->
               <td align="center"><img width="60" height="60" src="<?=OSS_ENDDOMAIN.'/'.$v['logo']?>" style="margin:5px 0;"></td>
      
               <td align="center">
                <?php if($v['back_image']){ ?>
               <img width="60" height="60" src="<?=OSS_ENDDOMAIN.'/'.$v['back_image']?>" style="margin:5px 0;">
                 <?php }else{ ?>
                  <img width="50" height="40" src="/Static/Admin/image/no_pic.png" style="margin:5px 0;">
                    <?php } ?>
                    </td>
                <td align="center"><?=($v['ybskb']==1)?'支持':'不支持'?></td>
                <td align="center"><?=($v['status']==1)?'正常':'禁用'?></td>
                <td align="center"><?=($v['bank_type']==1)?'信用卡':'储蓄卡'?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
     <div class="panelBar">
        <div class="pages">
            <span>显示</span> <select class="combox" name="numPerPage"
                                    onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
                <option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
            </select> <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>
