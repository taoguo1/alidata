<style type="text/css">
.pageFormContent dt{width: 150px; padding-left: 120px;}
</style>
<div class="pageContent">
    <form method="post" action="<?php echo \Core\Lib::getUrl('Merc','addTable?appid='.$appid, 'act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>数据表名称</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="app_name" class="required" value="" />
                    </dd>
                </dl>
            </div>

        </div>
    </form>
</div>