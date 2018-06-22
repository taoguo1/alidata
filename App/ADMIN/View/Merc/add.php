<style type="text/css">
.pageFormContent dt{width: 150px; padding-left: 120px;}
</style>
<div class="pageContent">
    <form method="post" action="<?php echo \Core\Lib::getUrl('Merc','add', 'act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>应用名称：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="app_name" class="required" value="" />
                    </dd>
                </dl>
            </div>

            <div class="unit">
                <dl>
                    <dt>数据库连接地址：</dt>
                    <dd>
                        <input type="text"  style="width:500px;" name="db_ip" class="required" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>数据库名：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="db_name" class="required" value=""  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>数据库用户：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="db_user" class="required"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>数据库密码：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="db_password" class="required" value="" />
                    </dd>
                </dl>
            </div>


            <div class="unit">
                <dl>
                    <dt>数据库端口：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="db_port" class="required" value="3306"/>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>数据库表前缀：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="db_prefix" class="required" value="dzz_" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>oss路径：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="oss_enddomain" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>EXChange：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="ex_service"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">（API服务器）。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>签名code：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="sign_code"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>还款手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="repayment_poundage"  value="" />
                    </dd>
                </dl>
            </div>

            <div class="unit">
                <dl>
                    <dt>oem还款手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="oem_repayment_poundage"  value="" />
                    </dd>
                </dl>
            </div>

            <div class="unit">
                <dl>
                    <dt>充值手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="deposit_poundage"  value="" />
                    </dd>
                </dl>
            </div>


            <div class="unit">
                <dl>
                    <dt>消费手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="money_out_poundage"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">每笔1元。</p>
                </dl>
            </div>

            <div class="unit">
                <dl>
                    <dt>提现手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="withdraw_poundage"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">每笔3元。</p>
                </dl>
            </div>

            


            <div class="unit">
                <dl>
                    <dt>验卡手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="validatecard_poundage" value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">每笔3元。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>套现交易费率：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="tx_in"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>套现出款费率：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="tx_out"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>套现分润：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="tx_agent_rate"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">分润 0.03%。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>还款分润</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="repayment_rate"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;"></p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>单日提现限额：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="max_withdraw_day"  value="" />
                    </dd>
                </dl>
            </div>
            <!--<div class="unit">
                <dl>
                    <dt>红包每日最高：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="bonus_day_max"  value="" />
                    </dd>
                </dl>
            </div>-->
            <div class="unit">
                <dl>
                    <dt>保证金倍数：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="deposit_co"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">还款金额/10 * 1.3。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>最大还款金额：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="max_r_amount"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">不能超过10万。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>最小还款金额：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="min_r_amount"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">不能小于1000元。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>单笔最大还款金额：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="max_r_sin_amount"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">不能超过2万。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>微信通知的URL：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="setnotifyurl"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>通道进账url：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="channel_in_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>通道出账url：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="channel_out_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>通道进账查询url：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="channel_in_query_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>通道出账查询url：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="channel_out_query_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>商户号：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="merchant_id"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>境外消费url：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="abroad_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>境外消费收益：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="jwpay_earnings"  value="" />
                    </dd>
                </dl>
            </div>
             <div class="unit">
                <dl>
                    <dt>消费手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="xf_service_charge"  value="" />
                    </dd>
                </dl>
            </div>
            
                        <div class="unit">
                <dl>
                    <dt>身份鉴权手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="sfvalue"  value="" />
                    </dd>
                </dl>
            </div>
                        <div class="unit">
                <dl>
                    <dt>套现入款手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="txlnvalue"  value="" />
                    </dd>
                </dl>
            </div>
                        <div class="unit">
                <dl>
                    <dt>套现出款手续费：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="txoutvalue"  value="" />
                    </dd>
                </dl> 
            </div>
            
            <div class="unit">
                <dl>
                    <dt>卡测评收益：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="kcp_earnings"  value="" />
                    </dd><p style="color: #CC0000; padding-left: 10px;">RMB。</p>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>商户密匙：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="merchant_key"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>出款秘钥：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="a_deposit_key"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>入款秘钥：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="deposit_key"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>支付路径：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="payment_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>苹果下载地址：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="ios_down_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>安卓下载地址：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="android_down_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>版本号：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="version_number"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>最新版本号：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="new_version_number"  value="" />
                    </dd>
                </dl>
            </div>
            
            <!--------------------->
            <div class="unit">
                <dl style="height: 200px;">
                    <dt style="line-height: 200px;">redis服务器地址：</dt>
                    <dd>
                    	<textarea name="redis_config" row="6" cols="150"  class="required" style="height: 198px;">{"token":{"host":"r-2ze9669a14bccd64.redis.rds.aliyuncs.com","port":6379,"username":"r-2ze9669a14bccd64","password":"Dzz123456","select":0,"timeout":0,"expire":0,"persistent":false,"prefix":""},"plan":{"host":"r-2ze1295484976154.redis.rds.aliyuncs.com","port":"6379","username":"r-2ze1295484976154","password":"Dzz123456","select":0,"timeout":0,"expire":0,"persistent":false,"prefix":""},"msg":{"host":"r-2zea43bc81032aa4.redis.rds.aliyuncs.com","port":"6379","username":"r-2zea43bc81032aa4","password":"Dzz123456","select":0,"timeout":0,"expire":0,"persistent":false,"prefix":""}}</textarea>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl style="height: 100px;">
                    <dt style="line-height: 100px;">OSS accessKeyId等信息：</dt>
                    <dd>
                    	<textarea name="oss_config" row="6" cols="150"  class="required" style="height: 98px;">{"accessKeyId":"LTAI8oArxIFvDPY9","accessKeySecret":"JaBYs9S2m1F3TKIHE9Yn5GtubZGRoG","endpoint":"oss-cn-beijing.aliyuncs.com","bucket":"dzz-ydjx"}</textarea>
                    </dd>
                </dl>
            </div>
            
            <div class="unit">
                <dl style="height: 100px;">
                    <dt style="line-height: 100px;">激光推送 appKey等：</dt>
                    <dd>
                    	<textarea name="jpush_config" row="5" cols="150"  class="required" style="height: 98px;">{"appKey":"8cb3c6e434a8a2dd5a4c6728","masterSecret":"416f581df58b4aa30ef4ebbc","apnsProduction":true}</textarea>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl style="height: 100px;">
                    <dt style="line-height: 100px;">短信accessKeyId等信息：</dt>
                    <dd>
                    	 <textarea name="sms_config" row="5" cols="150"  class="required" style="height: 98px;">{"SignName":"一点就行智能管家","accessKeyId1":"LTAIVa23X09jpolC","accessKeySecret":"Rq1RRAvsWo9od76zkcsQm2nJFZSnxp"}
                         </textarea>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>信用卡办理链接：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="credit_card_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>保险链接：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="insurance_url"  value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>小额贷款链接：</dt>
                    <dd>
                        <input type="text" style="width:500px;" name="loan_url" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>是否显示：</dt>
                    <dd>
                        <select name="is_show" class="required">
                            <option value="">请选择状态 &nbsp; </option>
                            <option value="1">显示</option>
                            <option value="-1">隐藏</option>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required">
                            <option value="">请选择状态 &nbsp; </option>
                            <option value="1">启用</option>
                            <option value="-1">禁用</option>
                        </select>
                    </dd>
                </dl>
            </div>
            <div style="height: 200px;"></div>
            	
        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="submit">保存</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="close">取消</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>