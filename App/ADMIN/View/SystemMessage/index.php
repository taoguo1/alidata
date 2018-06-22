<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('SystemMessage')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li><label>查找标题：</label> <input type="text" name="title" value="<?=\Core\Lib::request('title')?>" /></li>
                <li>
                    <label>信息级别：</label>
                    <select name="type">
                        <option value="">全部&nbsp;</option>
                        <?php foreach($type as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php if(\Core\Lib::request('type')==$k){echo 'selected';}?>><?php echo $v;?></option>
                        <?php } ?>
                    </select>
                </li>
               <li>
                    <label>发送状态：</label>
                    <select name="read_unread">
                        <option value="">全部&nbsp;</option>
                        <?php foreach($read_unread as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php if(\Core\Lib::request('read_unread')==$k){echo 'selected';}?>><?php echo $v;?></option>
                        <?php } ?>
                    </select>
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
			<li><a class="add" rel="articleAdd" title="添加信息" href="<?=\Core\Lib::getUrl('SystemMessage', 'add');?>" target="dialog" width="800" height="600"><span>添加</span></a></li>
			<li><a class="edit"  title="编辑信息" rel="articleEdit" href="<?=\Core\Lib::getUrl('SystemMessage', 'edit','id={id}');?>" target="dialog"  width="800" height="600"><span>编辑</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('SystemMessage', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('SystemMessage','delAll');?>" class="delete"><span>批量删除</span></a></li>			
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="60" align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
				<th width="260" align="center" orderField="title" class="<?=($data['orderField']=='title')?$data['orderDirection']:'';?>">标题</th>	
				<th align="center" orderField="content" class="<?=($data['orderField']=='content')?$data['orderDirection']:'';?>">描述内容</th>			
				<th width="260" align="center" orderField="user_type" class="<?=($data['orderField']=='user_type')?$data['orderDirection']:'';?>">用户类型</th>
				<th width="260"  align="center" orderField="type" class="<?=($data['orderField']=='type')?$data['orderDirection']:'';?>">信息级别</th>	
				<th width="150" align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">添加时间</th>
				<th align="center" orderField="content" >选择推送</th>

				
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?=$v['title']?></td>	
				<td align="center"><?=$v['describe']?></td> 			
				<td align="center"><?=($v['user_type']==1)?'<span style="color:#00acec">用户</span>':'<span style="color:#00acec">代理商'.'</span>'?></td>
				<td align="center"><?php if($v['type'] == 1){echo '<span style="color:red">紧急</span>';}else if($v['type'] == 2){echo '<span style="color:red">重要</span>';}else{echo '一般';}?></td>        
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s',$v['create_time']);?></td>
				<td align="center" class=""><a href="<?=\Core\Lib::getUrl('SystemMessage', 'oemContact?id='.$v['id']);?>"  width="1100" height="600"  target="dialog" >推送</a></td>
               
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
