<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('ReportsCreditCardCV','index')?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tr>
                    <td>
                        <select class="combox" name="year">
                            <option value="">选择年份</option>
                            <?php for($i=2017;$i<=date("Y");$i++){?>
                                <option value="<?=$i?>"<?php if(\Core\Lib::request('year')==$i){?> selected<?php }?>><?=$i?>年</option>
                            <?php }?>
                        </select>
                    </td>
                    <td>
                        <select class="combox" name="month">
                            <option value="">选择月份</option>
                            <option value="1"<?php if(\Core\Lib::request('month')=="1"){?> selected<?php }?>>1月</option>
                            <option value="2"<?php if(\Core\Lib::request('month')=="2"){?> selected<?php }?>>2月</option>
                            <option value="3"<?php if(\Core\Lib::request('month')=="3"){?> selected<?php }?>>3月</option>
                            <option value="4"<?php if(\Core\Lib::request('month')=="4"){?> selected<?php }?>>4月</option>
                            <option value="5"<?php if(\Core\Lib::request('month')=="5"){?> selected<?php }?>>5月</option>
                            <option value="6"<?php if(\Core\Lib::request('month')=="6"){?> selected<?php }?>>6月</option>
                            <option value="7"<?php if(\Core\Lib::request('month')=="7"){?> selected<?php }?>>7月</option>
                            <option value="8"<?php if(\Core\Lib::request('month')=="8"){?> selected<?php }?>>8月</option>
                            <option value="9"<?php if(\Core\Lib::request('month')=="9"){?> selected<?php }?>>9月</option>
                            <option value="10"<?php if(\Core\Lib::request('month')=="10"){?> selected<?php }?>>10月</option>
                            <option value="11"<?php if(\Core\Lib::request('month')=="11"){?> selected<?php }?>>11月</option>
                            <option value="12"<?php if(\Core\Lib::request('month')=="12"){?> selected<?php }?>>12月</option>
                        </select>
                    </td>
                    <?php if(empty(\Core\Lib::request('month'))){?>
                        <td>
                            <input type="radio" value="week" name="type"<?php if(\Core\Lib::request('type')=='week'){?> checked<?php }?>>按周查看
                            <input type="radio" value="day" name="type"<?php if(\Core\Lib::request('type')=='day' || \Core\Lib::request('type')==''){?> checked<?php }?>>按天查看
                        </td>
                    <?php }?>
                    <td>
                        <label>用户：</label>
                        <input type="text"  class="user" style="" name="user.real_name" value="<?=\Core\Lib::request('user_real_name')?>" placeholder="全部" lookupGroup="user" readonly/>
                        <input type="hidden"  class="user" name="user.id" value="<?=\Core\Lib::request('user_id')?>" lookupGroup="user" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                        &nbsp;
                        <a title="删除"  href="javascript:void(0)" onclick="$('.user').val('')" class="btnDel" style="float: right">删除</a>
                    </td>
                    <td class="tdcombox">
                        <select class="combox" name="status">
                            <option value="1" <?php if(\Core\Lib::request('status')=="1"){?> selected<?php }?> >成功</option>
                            <option value="-1" <?php if(\Core\Lib::request('status')=="-1"){?> selected<?php }?> >失败</option>
                            <option value="-2" <?php if(\Core\Lib::request('status')=="-2"){?> selected<?php }?> >银行处理中</option>
                        </select>
                    </td>
                    <td>
                        <div class="buttonActive">
                            <div class="buttonContent">
                                <button type="submit">查询</button>
                            </div>
                        </div>
                    </td>

                </tr>
            </table>

        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" rel="reload" title="刷新" onclick="navTabPageBreak()"><span>刷新</span></a></li>
        </ul>
    </div>
    <?php
    if(\Core\Lib::request('month')){
        ?>
        <table class="list" width="100%" layoutH="85">
            <thead>
            <tr>
                <th width="30" align="center">
                    <?=$year;?>年
                </th>
                <th width="30" align="center">
                    合计
                </th>
                <?php for($i = 0;$i <= 23;$i++){ ?>
                    <th width="30" align="center">
                        <?=$i?>时
                    </th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php
            for($j = 1;$j <= \Core\Lib::getMonthLastDay($year,\Core\Lib::request('month'));$j++){
                ?>
                <tr target="id" rel="<?=$j?>">
                    <td align="center"><a href=""><strong style="color:#ff0000;"><?=$j?>日</strong></a></td>
                    <td align="center"><?=$data['day'][$j]['sum']?></td>
                    <?php
                    for($i = 0;$i <= 23;$i++){
                        ?>
                        <td align="center">
                            <?php
                            echo $data['day'][$j][$i];
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            <?php }?>

            <tr target="id" rel="<?=$j?>">
                <td align="center"><strong>合计</strong></td>
                <td align="center"><?=$data['day']['sumday'];?></td>
                <?php for($i = 0;$i <= 23;$i++){ ?>
                    <td align="center"><?php
                        echo array_sum($data['fday'][$i]);
                        ?></td>
                <?php }?>
            </tr>

            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>合计：<?php echo $data['day']['sumday']; ?></span>
            </div>
        </div>
        <?php
    }else{
        ?>
        <table class="list" width="100%" layoutH="85">
            <thead>
            <tr>
                <th width="30" align="center">
                    <?=$year;?>年
                </th>
                <th width="30" align="center">
                    合计
                </th>
                <?php if(\Core\Lib::request('type') == 'week'){for($i = 0;$i <= 6;$i++){ ?>
                    <th width="30" align="center">
                        <?=\Core\Lib::getWeek($i)?>
                    </th>
                <?php } }else{for($i = 1;$i <= 31;$i++){ ?>
                    <th width="30" align="center">
                        <?=$i?>日
                    </th>
                <?php } } ?>
            </tr>
            </thead>
            <tbody>
            <?php for($j = 1;$j <= 12;$j++){?>
                <tr target="id" rel="<?=$j?>">
                    <td align="center"><a href=""><strong style="color:#ff0000;"><?=$j?>月</strong></a></td>
                    <td align="center"><?=$data['day'][$j]['sum']?></td>
                    <?php
                    if(\Core\Lib::request('type') == 'week') {
                        for ($i = 0; $i <= 6; $i++) {
                            ?>
                            <td align="center">
                                <?php
                                echo $data['day'][$j][$i];
                                ?>
                            </td>
                            <?php
                        }
                    }else{for ($i = 1; $i <= 31; $i++) {
                        ?>
                        <td align="center">
                            <?php
                            echo $data['day'][$j][$i];
                            ?>
                        </td>
                    <?php } } ?>
                </tr>
            <?php }?>

            <tr target="id" rel="<?=$j?>">
                <td align="center"><strong>合计</strong></td>
                <td align="center"><?=$data['day']['sumday'];?></td>
                <?php
                if(\Core\Lib::request('type') == 'week'){for($i = 0;$i <= 6;$i++){ ?>
                    <td align="center"><?php
                        echo array_sum($data['fday'][$i]);
                        ?></td>
                    <?php
                }
                }else{
                    for ($i = 1; $i <= 31; $i++) {?>
                        <td align="center">
                            <?php
                            echo array_sum($data['fday'][$i]);
                            ?>
                        </td>
                        <?php
                    }
                } ?>
            </tr>

            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>合计：<?php echo $data['day']['sumday']; ?></span>
            </div>
        </div>
        <?php
    }
    ?>
</div>
