<div class="pageContent" style="padding:5px">
	<div class="tabs">
		<div class="tabsHeader">
			<div class="tabsHeaderContent">
				<ul>
					<li><a href="javascript:;"><span>开发帮助</span></a></li>		
				</ul>
			</div>
		</div>
		<div class="tabsContent">
			<div>
				<div layoutH="50" style="float:left; display:block;margin-top:0px overflow:auto; width:190px; border:solid 1px #CCC; line-height:21px; background:#fff">
				    <ul class="tree treeFolder" >
						<li><a href="javascript">数据库操作方法</a>
							<ul>
								<?php foreach($data as $v){?>
								<li><a href="<?php echo \Core\Lib::getUrl('Help','detail','id='.$v['id'])?>" style='font-size: 14px;' target="ajax" rel="jbsxBox"><?php echo $v['title'];?></a></li>
								<?php }?>
							</ul>
						</li>
				     </ul>
				</div>
				
				<div id="jbsxBox" class="unitBox" style="margin-left:200px;">
					<div class="pageContent" layoutH="50" style="border:1px #cccccc solid;width:99%; background:#ffffff">
					<div style="padding: 10px;"><?php echo $content;?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="tabsFooter">
			<div class="tabsFooterContent"></div>
		</div>
	</div>
	
</div>


	

