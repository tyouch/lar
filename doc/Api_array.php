<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/6/6
 * Time: 10:02
 */


/** 6.1 获取短信验证码(OGW00041) */
/** 第三方公司发起向客户注册手机发送手机短信验证码。
一个手机号，一分钟之内只能发送一次获取短信验证码。
暂用于自动投标授权撤销、自动还款授权撤销两接口的发请求前的发送短信验证码。 */
$OGW00041 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00041',
  'MERCHANTID' => ' //字段名称：商户唯一标识 | 类型：C(20) | 可空：否 | 备注：由银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC）手机：APP 微信：WX',
  'TRSTYPE' => ' //字段名称：操作类型 | 类型：C(3) | 可空：否 | 备注：1：自动投标撤销 2：自动还款授权撤销 0：默认',
  'ACNO' => ' //字段名称：银行账号 | 类型：C(32) | 可空：是 | 备注：E账户账号，即关联账号. P2p商户必填',
  'MOBILE_NO' => ' //字段名称：手机号 | 类型：N(18) | 可空：是 | 备注：注册的时候必填，11位手机号',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00041)) */




/** 6.2 账户开立(OGW00042) （必选，跳转我行页面处理） */
/** 由第三方公司发起，跳转到银行官网完成进行该操作。交易提交我行10分钟后，可通过该接口查询银行处理结果。客户在页面流程操作共不可超过20分钟，否则请求超时。 */
$OGW00042 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端:OGW00042 移动端：OGW00090',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(32) | 可空：否 | 备注：银行统一提供',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC）手机：APP 微信：WX',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：6:账户开立',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：姓名 | 类型：C(128) | 可空：是 | 备注：',
  'IDTYPE' => ' //字段名称：证件类型 | 类型：C(4) | 可空：是 | 备注：1010：居民身份证',
  'IDNO' => ' //字段名称：证件号码 | 类型：C(32) | 可空：是 | 备注：只支持身份证',
  'MOBILE' => ' //字段名称：手机号码 | 类型：N(18) | 可空：是 | 备注：11位手机号',
  'EMAIL' => ' //字段名称：用户邮箱 | 类型：C(50) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C(128) | 可空：是 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'CUSTMNGRNO' => ' //字段名称：客户经理编号 | 类型：C(50) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00042)) */




/** 6.3 账户开立结果查询(OGW00043) */
/** 第三方公司发起账户开立结果查询， 原交易提交我行10分钟后，可通过该接口查询银行处理结果。
如超过25分钟状态仍是R状态的，则认为交易是失败的，无需再过来查询。
30分钟仍是未成功的状态可置交易为失败，无需再查询。 */
$OGW00043 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00043',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(32) | 可空：否 | 备注：银行统一提供',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC）手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原交易流水号 | 类型：C(28) | 可空：否 | 备注：原账户开立交易报文 头的“渠道流水号”',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00043)) */




/** 6.4 绑卡(OGW00044)（可选，跳转我行页面处理） */
/** 客户新开的关联账号（E账户）未绑卡可通过此接口进行E账户的绑卡（华兴或其他银行的个人储蓄卡），
此接口只允许未绑卡的用户绑卡，如已绑卡则不允许再通过此接口进行绑卡。
此接口不提供回查接口。客户在页面流程操作共不可超过20分钟，否则请求超时。 */
$OGW00044 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端：OGW00044 移动端：OGW00091',
  'MERCHANTID' => ' //字段名称：商户唯一标识 | 类型：C(32) | 可空：否 | 备注：由银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(6) | 可空：否 | 备注：个人电脑:PC（不送则默认PC）手机：APP 微信：WX',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：TransType.4=互联网借贷投标
TransType.5=互联网借贷还款
TransType.6=账户开立
TransType.7=充值
TransType.8=提现
TransType.9=自动投标授权
TransType.10=自动还款授权
TransType.1=绑卡
TransType.2=债券转让',
  'ACNO' => ' //字段名称：银行账号 | 类型：N(32) | 可空：否 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C(128) | 可空：是 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00044)) */




/** 6.5 单笔专属账户充值(OGW00045) （跳转我行页面处理） */
/** 由第三方公司发起，跳转到银行官网完成进行该操作。交易提交我行5分钟后，可通过该接口查询银行处理结果。
客户在页面流程操作共不可超过20分钟，否则请求超时。 */
$OGW00045 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端：OGW00044 移动端：OGW00091',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(6) | 可空：否 | 备注：个人电脑:PC（不送则默认PC）手机：APP 微信：WX',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(20) | 可空：否 | 备注：7:充值',
  'ACNO' => ' //字段名称：银行账号 | 类型：N(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：账号户名 | 类型：C(20) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：交易金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(20) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C(20) | 可空：是 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00045)) */




/** 6.6 单笔充值结果查询 (OGW00046) */
/** 由第三方公司发起。
交易提交我行5分钟后，可通过该接口查询银行处理结果。后续查询的频率按5分钟的时间递增。
状态为S和P都可认为是交易成功，涉及显示给客户看的余额值请以我行余额查询的值为准。 */
$OGW00046 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00046',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(6) | 可空：否 | 备注：个人电脑:PC（不送则默认PC）手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原充值交易流水号 | 类型：C(28) | 可空：否 | 备注：原充值交易报文头的“渠道流水号”',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00046)) */




/** 6.7 单笔提现(OGW00047) （跳转我行页面处理） */
/** 由第三方公司发起，跳转到银行官网完成进行该操作。交易提交我行5分钟后，可通过该接口查询银行处理结果。
客户在页面流程操作共不可超过20分钟，否则请求超时。 */
$OGW00047 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00046',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：TransType.4=互联网借贷投标
TransType.5=互联网借贷还款
TransType.6=账户开立
TransType.7=充值
TransType.8=提现
TransType.9=自动投标授权
TransType.10=自动还款授权',
  'ACNO' => ' //字段名称：银行账号 | 类型：N(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：交易金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(128) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C(128) | 可空：是 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00047)) */




/** 6.8 单笔提现结果查询(OGW00048) */
/** 由第三方发起，交易提交我行5分钟后，可通过该接口查询银行处理结果。 */
$OGW00048 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00048',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'TRANSDT' => ' //字段名称：原提现交易日期 | 类型：D | 可空：是 | 备注：YYYYMMDD',
  'OLDREQSEQNO' => ' //字段名称：原提现交易流水号 | 类型：C（28） | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00048)) */




/** 6.9 账户余额查询(OGW00049) */
/**  */
$OGW00049 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00049',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'BUSTYPE' => ' //字段名称：业务类型 | 类型：C(5) | 可空：是 | 备注：意为充值到哪类账户，是E账号活期户还是专户可不送此字段或传空串',
  'ACNO' => ' //字段名称：银行账号 | 类型：N(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：银行户名 | 类型：C(128) | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00049)) */




/** 6.10 账户余额校检(OGW00050) */
/** 若是对接“账户余额查询接口”，则该接口无需对接。由第三方公司发起。 */
$OGW00050 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00050',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'ACNO' => ' //字段名称：银行账号 | 类型：N(32) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：交易金额 | 类型：M | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00050)) */




/** 6.11 单笔发标信息通知(OGW00051) */
/** 由第三方公司发起。发标的融资标的信息，可通过单笔通讯或者文件同步方式。这涉及到投标、流标等控制。具体方式根据协商确定。（首次投产时，建议第三方公司提供一个全量文件给银行一次性导入，以后就单笔通知）。如没收到银行的处理结果，可对同一标的多次发送，但发请求时，报文头的流水号需每次保证唯一性，当报文头返回错误码是“EAS020420026”时则代表原标的已成功，无需再重复发送。
此接口支持新标的通知和债券转让标的通知。
债券转让申请(OGW00063)我行返回成功后，第三方公司审核允许转让后需调用此接口进行转让标的发标申请。 */
$OGW00051 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00051',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：目前两者为一致',
  'INVESTID' => ' //字段名称：标的编号 | 类型：C (128) | 可空：否 | 备注：目前两者为一致',
  'INVESTOBJNAME' => ' //字段名称：标的名称 | 类型：C (512) | 可空：否 | 备注：',
  'INVESTOBJINFO' => ' //字段名称：标的简介 | 类型：C (1028) | 可空：是 | 备注：',
  'MININVESTAMT' => ' //字段名称：最低投标金额 | 类型：M | 可空：是 | 备注：',
  'MAXINVESTAMT' => ' //字段名称：最高投标金额 | 类型：M | 可空：是 | 备注：',
  'INVESTOBJAMT' => ' //字段名称：总标的金额 | 类型：M | 可空：否 | 备注：各个借款人列表中的BWAMT总和',
  'INVESTBEGINDATE' => ' //字段名称：招标开始日期 | 类型：D | 可空：否 | 备注：YYYYMMDD',
  'INVESTENDDATE' => ' //字段名称：招标到期日期 | 类型：D | 可空：否 | 备注：YYYYMMDD',
  'REPAYDATE' => ' //字段名称：还款日期 | 类型：D | 可空：是 | 备注：YYYYMMDD',
  'YEARRATE' => ' //字段名称：年利率 | 类型：I2 | 可空：否 | 备注：最大值为：999.999999',
  'INVESTRANGE' => ' //字段名称：期限 | 类型：N(10) | 可空：否 | 备注：整型，天数，单位为天',
  'RATESTYPE' => ' //字段名称：计息方式 | 类型：C(128) | 可空：是 | 备注：',
  'REPAYSTYPE' => ' //字段名称：还款方式 | 类型：C(128) | 可空：是 | 备注：',
  'INVESTOBJSTATE' => ' //字段名称：标的状态 | 类型：C(3) | 可空：否 | 备注：0 正常 1 撤销',
  'BWTOTALNUM' => ' //字段名称：借款人总数 | 类型：N(10) | 可空：否 | 备注：整型',
  'REMARK' => ' //字段名称：备注 | 类型：C(512) | 可空：是 | 备注：',
  'ZRFLAG' => ' //字段名称：是否为债券转让标的 | 类型：C(1) | 可空：是 | 备注：0 否，1 是',
  'REFLOANNO' => ' //字段名称：债券转让原标的 | 类型：C(64) | 可空：是 | 备注：当ZRFLAG=1时必填',
  'OLDREQSEQ' => ' //字段名称：原投标第三方交易流水号 | 类型：C(28) | 可空：是 | 备注：当ZRFLAG=1时必填',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'BWLIST' => 
  array (
    'BWACNAME' => ' //字段名称：借款人姓名 | 类型：C(128) | 可空：否 | 备注：',
    'BWIDTYPE' => ' //字段名称：借款人证件类型 | 类型：C(4) | 可空：是 | 备注：身份证：1010',
    'BWIDNO' => ' //字段名称：借款人证件号码 | 类型：C(32) | 可空：是 | 备注：18位身份证',
    'BWACNO' => ' //字段名称：借款人账号 | 类型：N(32) | 可空：否 | 备注：',
    'BWACBANKID' => ' //字段名称：借款人账号所属行号 | 类型：N(64) | 可空：是 | 备注：12位联行号，12位数字',
    'BWACBANKNAME' => ' //字段名称：借款人账号所属行名 | 类型：C(256) | 可空：是 | 备注：',
    'BWAMT' => ' //字段名称：借款人金额 | 类型：M | 可空：否 | 备注：',
    'MORTGAGEID' => ' //字段名称：借款人抵押品编号 | 类型：C(128) | 可空：是 | 备注：',
    'MORTGAGEINFO' => ' //字段名称：借款人抵押品简单描述 | 类型：C(1024) | 可空：是 | 备注：',
    'CHECKDATE' => ' //字段名称：借款人审批通过日期 | 类型：C(8) | 可空：是 | 备注：',
    'REMARK' => ' //字段名称：备注（其它未尽事宜） | 类型：C(1028) | 可空：是 | 备注：',
    'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
    'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
  ),
);
/** Usage: HXBandApi::request($OGW00051)) */




/** 6.12 单笔投标 (OGW00052)（跳转我行页面处理） */
/** 由第三方公司发起，跳转到银行官网完成进行该操作。进行资金冻结。交易提交我行5分钟后，可通过该接口查询银行处理结果。客户在页面流程操作共不可超过20分钟，否则请求超时。
债券转让的标的，当第三方公司完成转让标申请（单笔发标信息通知）后，再通过此接口让投资人对转让标进行投标，目前转让标只允许把转让的整个标的整体一次进行投标，暂不支持转让标的多次投标（第三方公司需进行控制，如不控制多次投标出现的问题由第三方公司负责。），否则会在放款时失败。 */
$OGW00052 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端：OGW00052 移动端：OGW00094',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：TransType.4=互联网借贷投标
TransType.5=互联网借贷还款
TransType.6=账户开立
TransType.7=充值
TransType.8=提现
TransType.9=自动投标授权
TransType.10=自动还款授权',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：',
  'ACNO' => ' //字段名称：投资人账号 | 类型：C(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：投资人账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：投标金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C（128） | 可空：是 | 备注：不提供此地址，则客户处理完后无法跳转到商户指定页面。',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00052)) */




/** 6.13 单笔投标结果查询(OGW00053) */
/** 由第三方公司发起。5分钟后，可通过该接口查询银行处理结果。 */
$OGW00053 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00053',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原投标交易流水号 | 类型：C（28） | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00053)) */




/** 6.14 投标优惠返回（可选）(OGW00054)

 */
/** 放款后才能发起，借款人划扣优惠金额，分别划入投资人账户 */
$OGW00054 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00054',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：',
  'BWACNAME' => ' //字段名称：还款账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：还款账号 | 类型：C(32) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：优惠总金额 | 类型：M | 可空：否 | 备注：此值等于明细列表的优惠金额之和。',
  'TOTALNUM' => ' //字段名称：优惠总笔数 | 类型：C(10) | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'FEEDBACKLIST' => 
  array (
    'SUBSEQNO' => ' //字段名称：子流水号 | 类型：C（32） | 可空：否 | 备注：用于对账，必须唯一',
    'OLDREQSEQNO' => ' //字段名称：原投标流水号 | 类型：C（28） | 可空：否 | 备注：',
    'ACNO' => ' //字段名称：投资人账号 | 类型：C(32) | 可空：否 | 备注：',
    'ACNAME' => ' //字段名称：投资人账号户名 | 类型：C(128) | 可空：否 | 备注：',
    'AMOUNT' => ' //字段名称：优惠金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
    'REMARK' => ' //字段名称：备注 | 类型：C(128) | 可空：是 | 备注：',
    'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(200) | 可空：是 | 备注：备用字段3',
  ),
);
/** Usage: HXBandApi::request($OGW00054)) */




/** 6.15 投标优惠返回结果查询（可选）(OGW00055) */
/** 由第三方公司发起。当收不到返回时（5分钟后），可通过该接口查询银行处理结果。 */
$OGW00055 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00055',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原投标优惠返回交易流水号 | 类型：C（28） | 可空：否 | 备注：',
  'SUBSEQNO' => ' //字段名称：子流水号 | 类型：C（32） | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00055)) */




/** 6.16 自动投标授权(OGW00056) （可选，跳转我行页面处理） */
/** 由第三方公司发起，跳转到银行官网完成进行该操作。交易提交我行5分钟后，可通过该接口查询银行处理结果。
客户在页面流程操作共不可超过20分钟，否则请求超时。
客户账户为正常状态才能授权成功。 */
$OGW00056 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端：OGW00056 移动端：OGW00183',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：TransType.4=互联网借贷投标
TransType.5=互联网借贷还款
TransType.6=账户开立
TransType.7=充值
TransType.8=提现
TransType.9=自动投标授权
TransType.10=自动还款授权
TransType.1=绑卡
TransType.2=债券转让',
  'ACNO' => ' //字段名称：投资人账号 | 类型：C(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：投资人账号户名 | 类型：C(128) | 可空：是 | 备注：',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C（128） | 可空：是 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00056)) */




/** 6.17 自动投标授权结果查询（可选）(OGW00057) */
/** 由第三方公司发起。交易提交我行5分钟后，可通过该接口查询银行处理结果。 */
$OGW00057 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00057',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原自动投标授权交易流水号 | 类型：C（28） | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00057)) */




/** 6.18 自动投标授权撤销（可选）(OGW00058) */
/** 由第三方公司发起。调此接口前需先调用“获取短信验证码(OGW00041)”发送短信验证码后再发起此接口进行授权撤销。 */
$OGW00058 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00058',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OTPSEQNO' => ' //字段名称：动态密码唯一标识 | 类型：X(32) | 可空：否 | 备注：',
  'OTPNO' => ' //字段名称：动态密码 | 类型：X(10) | 可空：否 | 备注：',
  'ACNO' => ' //字段名称：投资人账号 | 类型：C(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：投资人账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00058)) */




/** 6.19 自动单笔投标（可选）(OGW00059) */
/** 发起此接口的前提为客户有自动投标授权。 */
$OGW00059 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00059',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：',
  'ACNO' => ' //字段名称：投资人账号 | 类型：C(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：投资人账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：投标金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00059)) */




/** 6.20 单笔撤标(OGW00060) */
/** 标的放款前，由第三方公司发起。撤标金额须与投标金额一致，不可部分撤标。 */
$OGW00060 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00060',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：与原投标时的借款编号一致。',
  'OLDREQSEQNO' => ' //字段名称：原投标流水号 | 类型：C（28） | 可空：否 | 备注：',
  'ACNO' => ' //字段名称：投资人账号 | 类型：C(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：投资人账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'CANCELREASON' => ' //字段名称：撤标原因 | 类型：C(128) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00060)) */




/** 6.21 银行主动单笔撤标（必须）(OGW0014T) */
/** 标的放款前，银行发送 */
$OGW0014T = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW0014T',
  'RETURNCODE' => ' //字段名称：响应码 | 类型：C(64) | 可空：否 | 备注：000000标识成功',
  'RETURNMSG' => ' //字段名称：响应信息 | 类型：C(128) | 可空：否 | 备注：交易成功',
  'OLDREQSEQNO' => ' //字段名称：原投标流水号 | 类型：C(28） | 可空：否 | 备注：',
);
/** Usage: HXBandApi::request($OGW0014T)) */




/** 6.22 债券转让申请(OGW00061) （可选，跳转我行页面处理） */
/** 由第三方公司发起。此接口只能在标的放款后发起。
投资人在原投资的标的放款后把原投资项目剩余的金额全额转让，可通过此接口向我行发起申请，客户在跳转到我行系统进行授权，我行登记此申请记录，返回结果到第三方公司。
对于债券转让的后续流程：
1) 第三方公司审核通过后向我行发起转让标申请（发标），我行检查已有授权后，则接收此转让标申请，返回商户结果。
2) 商户收到成功结果后把此标的公开，其他用户可对此转让标进行投标。
3) 投标结束后，商户向我行发起此转让标的放款指令，后此债券转让完成，原标的借款人还款的本金和收益由发给转让人替换成发放给转让标的投标人。(目前暂只支持全额一次转让，接收人也只能是一个人)。
债券转让的放款不支持扣取账号管理费和保证金。
对于已成功完成债券转让标放款的，接收这转让标的用户可再次进行债券转让，发起转让申请上送的投标流水为此用户投的转让标的放款流水。 */
$OGW00061 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端：OGW00061 移动端：OGW00096',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：TransType.4=互联网借贷投标
TransType.5=互联网借贷还款
TransType.6=账户开立
TransType.7=充值
TransType.8=提现
TransType.9=自动投标授权
TransType.10=自动还款授权
TransType.1=绑卡
TransType.2=债券转让',
  'OLDREQSEQNO' => ' //字段名称：原投标流水 | 类型：C(28) | 可空：否 | 备注：',
  'OLDREQNUMBER' => ' //字段名称：原标的编号 | 类型：C(128) | 可空：否 | 备注：',
  'OLDREQNAME' => ' //字段名称：原标的名称 | 类型：C(512） | 可空：否 | 备注：',
  'ACCNO' => ' //字段名称：转让人银行账号 | 类型：N(32) | 可空：否 | 备注：E账户（关联账号）',
  'CUSTNAME' => ' //字段名称：转让人名称 | 类型：C(64) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：剩余金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'PREINCOME' => ' //字段名称：预计剩余收益 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C（128） | 可空：是 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00061)) */




/** 6.23 债券转让结果查询(OGW00062) */
/** 由第三方公司发起。交易提交我行5分钟后，可通过该接口查询银行处理结果。
客户在页面流程操作共不可超过20分钟，否则请求超时。 */
$OGW00062 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00062',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原债券转让申请流水 | 类型：C(28) | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00062)) */




/** 6.24 流标(OGW00063) */
/** 标的放款前，由第三方公司发起。流标完成后，不允许再次流标。 */
$OGW00063 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00063',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：与原投标时的借款编号一致。',
  'CANCELREASON' => ' //字段名称：流标原因 | 类型：C(128) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00063)) */




/** 6.25 银行主动流标（必须）(OGW0015T) */
/** 标的放款前，由银行主动发起。 */
$OGW0015T = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW0015T',
  'RETURNCODE' => ' //字段名称：响应码 | 类型：C(64) | 可空：否 | 备注：000000标识成功',
  'RETURNMSG' => ' //字段名称：响应信息 | 类型：C(128) | 可空：否 | 备注：交易成功',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：',
);
/** Usage: HXBandApi::request($OGW0015T)) */




/** 6.26 流标结果查询 (OGW00064) */
/** 第三方公司发起。当收不到返回时（5~10分钟后），可通过该接口查询银行处理结果。 */
$OGW00064 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00064',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原流标交易流水号 | 类型：C(28） | 可空：否 | 备注：',
  'OLDTTJNL' => ' //字段名称：原投标流水号 | 类型：C(28) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00064)) */




/** 6.27 放款(OGW00065) */
/** 由第三方公司发起。当第三方公司认为标的已满，即可发起此接口，将投标人冻结资金放款至借款人账户。此接口适用于正常标的放款和转让标的放款。（债券转让当转让标的投资人投标完成后再发起转让标的放款，放款后原投资人转让的投标记录将不可再收到标的的还款收益，此还款收益将由接收此转让标的的投资人取得，也就是说还款收益明细此时的投资人应该传转让标的的投资人账号）
放款交易提交后，需在5分钟后发起放款结果查询，如收到的批次状态（RETURN_STATUS）为失败（F）或是直接异常返回时才能重新发起放款请求。一个标的只能放款一次，放款完成后不允许再次放款。
转让标的的放款不支持扣取账号管理费和风险保证金，此两值传0即可。 */
$OGW00065 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00065',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：与原投标时的借款编号一致。',
  'BWACNAME' => ' //字段名称：借款人姓名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：借款人账号 | 类型：C(32) | 可空：否 | 备注：',
  'ACMNGAMT' => ' //字段名称：账户管理费 | 类型：M | 可空：是 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'GUARANTAMT' => ' //字段名称：风险保证金 | 类型：M | 可空：是 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00065)) */




/** 6.28 放款结果查询 (OGW00066) */
/** 第三方公司发起。交易提交我行5~10分钟后，可通过该接口查询银行处理结果。 */
$OGW00066 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00066',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原放款交易流水号 | 类型：C(28） | 可空：否 | 备注：',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：',
  'OLDTTJNL' => ' //字段名称：原投标流水号 | 类型：C(28) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00066)) */




/** 6.29 借款人单标还款 (OGW00067) （必须，跳转我行页面处理） */
/** 由第三方公司发起，跳转到银行官网完成进行该操作。借款人单笔还款接口是和还款明细提交接口配套使用的。交易提交我行5分钟后，可通过该接口查询银行处理结果。客户在页面流程操作共不可超过20分钟，否则请求超时。 */
$OGW00067 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端：OGW00067 移动端：OGW00095',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：TransType.4=互联网借贷投标
TransType.5=互联网借贷还款
TransType.6=账户开立
TransType.7=充值
TransType.8=提现
TransType.9=自动投标授权
TransType.10=自动还款授权',
  'DFFLAG ' => ' //字段名称：还款类型 | 类型：C(1) | 可空：否 | 备注：1=正常还款 2=垫付后，借款人还款',
  'OLDREQSEQNO' => ' //字段名称：原垫付请求流水号 | 类型：C（28） | 可空：是 | 备注：垫付还款时必需',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：',
  'BWACNAME' => ' //字段名称：还款账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：还款账号 | 类型：C(32) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：还款金额 | 类型：C(18) | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00，',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C（128） | 可空：否 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'FEEAMT' => ' //字段名称：手续费 | 类型：M | 可空：是 | 备注：扣借款人的平台手续费',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00067)) */




/** 6.30 借款人单标还款结果查询(OGW00068) */
/** 由第三方公司发起。当收不到异步通知时（3~5分钟后），可通过该接口查询银行处理结果。 */
$OGW00068 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00068',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原借款人单标还款交易流水号 | 类型：C(28） | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00068)) */




/** 6.31 自动还款授权 (OGW00069) （可选，跳转我行页面处理） */
/** 由第三方公司发起，跳转到银行官网完成进行该操作。
此操作可在标的放款前或放款后都能发起授权。有自动还款授权后才能发起自动单笔还款。
客户在页面流程操作共不可超过20分钟，否则请求超时。客户账户为正常状态才能授权成功。 */
$OGW00069 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：PC端：OGW00069 移动端：OGW00175',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'TTRANS' => ' //字段名称：交易类型 | 类型：C(2) | 可空：否 | 备注：TransType.4=互联网借贷投标
TransType.5=互联网借贷还款
TransType.6=账户开立
TransType.7=充值
TransType.8=提现
TransType.9=自动投标授权
TransType.10=自动还款授权',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：',
  'BWACNAME' => ' //字段名称：还款账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：还款账号 | 类型：C(32) | 可空：否 | 备注：',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'RETURNURL' => ' //字段名称：返回商户URL | 类型：C（128） | 可空：是 | 备注：不提供此地址，则客户在我行页面处理完后无法跳转到商户指定页面。',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBankApi::getFormData($OGW00069)) */




/** 6.32 自动还款授权结果查询（可选）(OGW00070) */
/** 由第三方公司发起。请求收不到响应时，可通过该接口查询处理结果。
交易提交我行5分钟后，可通过该接口查询银行处理结果。 */
$OGW00070 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00070',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原自动还款授权交易流水号 | 类型：C(28） | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00070)) */




/** 6.33 自动还款授权撤销（可选）(OGW00071) */
/** 由第三方公司发起。
调此接口前需先调用“获取短信验证码(OGW00041)”发送短信验证码后再发起此接口进行授权撤销。 */
$OGW00071 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00071',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OTPSEQNO' => ' //字段名称：动态密码唯一标识 | 类型：X(32) | 可空：否 | 备注：',
  'OTPNO' => ' //字段名称：动态密码 | 类型：X(10) | 可空：否 | 备注：',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：',
  'BWACNAME' => ' //字段名称：还款账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：还款账号 | 类型：C(32) | 可空：否 | 备注：',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00071)) */




/** 6.34 自动单笔还款（可选）(OGW00072) */
/** 由第三方公司发起。如交易未收到返回结果，可通过借款人单标还款结果查询结果查得。 */
$OGW00072 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00072',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：',
  'BWACNAME' => ' //字段名称：还款账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：还款账号 | 类型：C(32) | 可空：否 | 备注：',
  'FEEAMT' => ' //字段名称：手续费 | 类型：M | 可空：是 | 备注：扣借款人的平台手续费',
  'AMOUNT' => ' //字段名称：还款金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00072)) */




/** 6.35 单标公司垫付还款(OGW00073) */
/** 由第三方发起。标的已放款后可发起，由公司的垫资账号代替借款人账号还款，后续借款人还款时需调用借款人单标还款（OGW0067）来还款，借款人还款金额会偿还公司垫付款。 */
$OGW00073 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00073',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'MERCHANTNAME' => ' //字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：',
  'BWACNAME' => ' //字段名称：借款人姓名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：借款人账号 | 类型：C(32) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：还款金额 | 类型：M | 可空：否 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'FEEAMT' => ' //字段名称：手续费 | 类型：M | 可空：是 | 备注：扣借款人的平台手续费',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00073)) */




/** 6.36 还款收益明细提交(OGW00074) */
/** 第三方公司将还款的明细推送给银行。还款明细提交接口是和借款人单笔还款配套使用的。同一还款流水只能提交一次收益明细提交，且还款收益明细提交金额总和等于借款人单笔还款上送金额（或是公司垫资还款的总金额）。当没有接受到银行的明确接受成功时，第三方公司可调用还款收益结果查询处理结果，如是银行没收到请求或是请求的批次状态为失败则可再重新提交交易。但注意报文头的请求流水要做变动。
此处送的投资人账号应该在原标的投资人账号列表中。
对于债券转让转让标的已成功放款的，此收益人应该是最终接收此转让标的投资人；但如果未完成转让标的的放款的，收益人还是转让标的原投资人。 */
$OGW00074 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00074',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原还款交易流水号 | 类型：C(28） | 可空：否 | 备注：',
  'DFFLAG' => ' //字段名称：还款类型 | 类型：C(1) | 可空：否 | 备注：1=正常还款 2=垫付后，借款人还款',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：',
  'BWACNAME' => ' //字段名称：还款账号户名 | 类型：C(128) | 可空：否 | 备注：',
  'BWACNO' => ' //字段名称：还款账号 | 类型：C(32) | 可空：否 | 备注：',
  'TOTALNUM' => ' //字段名称：总笔数 | 类型：C(10) | 可空：否 | 备注：整型',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'REPAYLIST' => 
  array (
    'SUBSEQNO' => ' //字段名称：子流水号 | 类型：C(32） | 可空：否 | 备注：用于对账，必须唯一，建议在开关加商户号',
    'ACNO' => ' //字段名称：投资人账号 | 类型：C(32) | 可空：否 | 备注：',
    'ACNAME' => ' //字段名称：投资人账号户名 | 类型：C(128) | 可空：否 | 备注：',
    'INCOMEDATE' => ' //字段名称：该收益所属截止日期 | 类型：C(8) | 可空：是 | 备注：YYYYMMDD',
    'AMOUNT' => ' //字段名称：还款总金额 | 类型：M | 可空：否 | 备注：总金额=本次还款本金+本次还款收益+本次还款费用 数值类型（15，2），整数15位，小数点后2位。例：3.00',
    'PRINCIPALAMT' => ' //字段名称：本次还款本金 | 类型：M | 可空：是 | 备注：投资人收款金额=（还款总金额-本次还款费用）；当投资人收款金额>0时，投资人会有资金入账；',
    'INCOMEAMT' => ' //字段名称：本次还款收益 | 类型：M | 可空：是 | 备注：本次还款费用>0时，则该还款费用清算到p2p公司在华兴开立的费用结算户中（p2p需告之银行该唯一指定的企业费用账户，配置到参数中）。',
    'FEEAMT' => ' //字段名称：本次还款费用 | 类型：M | 可空：是 | 备注：数值类型（15，2），整数15位，小数点后2位。例：3.00',
    'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
  ),
);
/** Usage: HXBandApi::request($OGW00074)) */




/** 6.37 还款收益结果查询 (OGW00075) */
/** 第三方公司发起。交易提交我行5~10分钟后，可通过该接口查询银行处理结果。 */
$OGW00075 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00075',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OLDREQSEQNO' => ' //字段名称：原流水号 | 类型：C(28） | 可空：否 | 备注：原还款收益提交交易流水号',
  'LOANNO' => ' //字段名称：借款编号 | 类型：C(64) | 可空：否 | 备注：',
  'SUBSEQNO' => ' //字段名称：子流水号 | 类型：C(32） | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00075)) */




/** 6.38 单笔奖励或分红（可选）(OGW00076) */
/** 根据第三方公司指令，将第三方公司计算户的钱，分发给客户。 */
$OGW00076 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00076',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：由华兴银行统一分配',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'ACNO' => ' //字段名称：收款账号 | 类型：C(32) | 可空：否 | 备注：',
  'ACNAME' => ' //字段名称：收款户名 | 类型：C(128) | 可空：否 | 备注：',
  'AMOUNT' => ' //字段名称：交易金额 | 类型：M | 可空：否 | 备注：',
  'REMARK' => ' //字段名称：备注 | 类型：C(60) | 可空：是 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00076)) */




/** 6.39 日终对账请求(OGW00077) */
/** 由第三方公司发起，如没收到结果请再次发起请求。 */
$OGW00077 = array (
  'TRANSCODE' => ' //字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00077',
  'MERCHANTID' => ' //字段名称：商户唯一编号 | 类型：C(20) | 可空：否 | 备注：',
  'APPID' => ' //字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
  'OPERFLAG' => ' //字段名称：对账类型 | 类型：C(2) | 可空：否 | 备注：0 金额类对账',
  'CHECKDATE' => ' //字段名称：对账日期 | 类型：D | 可空：否 | 备注：',
  'EXT_FILED1' => ' //字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
  'EXT_FILED2' => ' //字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
  'EXT_FILED3' => ' //字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
);
/** Usage: HXBandApi::request($OGW00077)) */




