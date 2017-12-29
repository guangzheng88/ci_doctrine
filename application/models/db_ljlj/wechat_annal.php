<?php
/**
 * db_ljlj.wechat_annal 微信收款表实体类
 * @author Your name
 * @date 2017-12-22 10:11:44
 * @Entity @Table(name="wechat_annal")
 */
include_once APPPATH.'models/Model.php';
class Wechat_annal extends Model
{
	/**
	 * 主键
	 * @Id
	 * @GeneratedValue
	 * @Column(type="integer")
	 */
	protected $id;

	/**
	 * 中信支付平台分配的appid
	 * @Column(type="string")
	 */
	protected $appid = '';

	/**
	 * 中信支付平台分配的商品
	 * @Column(type="string")
	 */
	protected $mch_appid = '';

	/**
	 * 微信appid
	 * @Column(type="string")
	 */
	protected $wx_appid = '';

	/**
	 * 微信用户在商品appid下的唯一标识
	 * @Column(type="string")
	 */
	protected $openid = '';

	/**
	 * 关联product_conume主键
	 * @Column(type="integer")
	 */
	protected $consume_id = 0;

	/**
	 * 销售合同号
	 * @Column(type="string")
	 */
	protected $contract_number = '';

	/**
	 * 支付号
	 * @Column(type="string")
	 */
	protected $out_trade_no = '';

	/**
	 * 微信订单号
	 * @Column(type="string")
	 */
	protected $transaction_id = '';

	/**
	 * 通道统一订单号
	 * @Column(type="string")
	 */
	protected $pass_trade_no = '';

	/**
	 * 支付金额
	 * @Column(type="decimal")
	 */
	protected $value = 0.00;

	/**
	 * 手续费
	 * @Column(type="decimal")
	 */
	protected $charge = 0.00;

	/**
	 * 支付结果1成功0失败默认1
	 * @Column(type="boolean")
	 */
	protected $pay_result = 1;

	/**
	 * 退款单号
	 * @Column(type="string")
	 */
	protected $out_refund_no = '';

	/**
	 * 微信退款单号
	 * @Column(type="string")
	 */
	protected $refund_id = '';

	/**
	 * 通道统一退款单号
	 * @Column(type="string")
	 */
	protected $pass_refund_no = '';

	/**
	 * 是否关注公众号1关注,0未关注,默认0
	 * @Column(type="boolean")
	 */
	protected $is_subscribe = 0;

	/**
	 * 付款银行
	 * @Column(type="string")
	 */
	protected $bank_type = '';

	/**
	 * 退款渠道 ORIGINAL:原路退款 BALANCE:退回到余额
	 * @Column(type="string")
	 */
	protected $refund_channel = '';

	/**
	 * 记录生成时间
	 * @Column(type="datetime")
	 */
	protected $add_time;

	/**
	 * 记录最新修改时间
	 * @Column(type="datetime")
	 */
	protected $modify_time;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->add_time = new \DateTime();
		$this->modify_time = new \DateTime();
	}
	public function getId()
	{
		return $this->id;
	}
	public function setId($id)
	{
		$this->id = $id;
	}
	public function getAppid()
	{
		return $this->appid;
	}
	public function setAppid($appid)
	{
		$this->appid = $appid;
	}
	public function getMch_appid()
	{
		return $this->mch_appid;
	}
	public function setMch_appid($mch_appid)
	{
		$this->mch_appid = $mch_appid;
	}
	public function getWx_appid()
	{
		return $this->wx_appid;
	}
	public function setWx_appid($wx_appid)
	{
		$this->wx_appid = $wx_appid;
	}
	public function getOpenid()
	{
		return $this->openid;
	}
	public function setOpenid($openid)
	{
		$this->openid = $openid;
	}
	public function getConsume_id()
	{
		return $this->consume_id;
	}
	public function setConsume_id($consume_id)
	{
		$this->consume_id = $consume_id;
	}
	public function getContract_number()
	{
		return $this->contract_number;
	}
	public function setContract_number($contract_number)
	{
		$this->contract_number = $contract_number;
	}
	public function getOut_trade_no()
	{
		return $this->out_trade_no;
	}
	public function setOut_trade_no($out_trade_no)
	{
		$this->out_trade_no = $out_trade_no;
	}
	public function getTransaction_id()
	{
		return $this->transaction_id;
	}
	public function setTransaction_id($transaction_id)
	{
		$this->transaction_id = $transaction_id;
	}
	public function getPass_trade_no()
	{
		return $this->pass_trade_no;
	}
	public function setPass_trade_no($pass_trade_no)
	{
		$this->pass_trade_no = $pass_trade_no;
	}
	public function getValue()
	{
		return $this->value;
	}
	public function setValue($value)
	{
		$this->value = $value;
	}
	public function getCharge()
	{
		return $this->charge;
	}
	public function setCharge($charge)
	{
		$this->charge = $charge;
	}
	public function getPay_result()
	{
		return $this->pay_result;
	}
	public function setPay_result($pay_result)
	{
		$this->pay_result = $pay_result;
	}
	public function getOut_refund_no()
	{
		return $this->out_refund_no;
	}
	public function setOut_refund_no($out_refund_no)
	{
		$this->out_refund_no = $out_refund_no;
	}
	public function getRefund_id()
	{
		return $this->refund_id;
	}
	public function setRefund_id($refund_id)
	{
		$this->refund_id = $refund_id;
	}
	public function getPass_refund_no()
	{
		return $this->pass_refund_no;
	}
	public function setPass_refund_no($pass_refund_no)
	{
		$this->pass_refund_no = $pass_refund_no;
	}
	public function getIs_subscribe()
	{
		return $this->is_subscribe;
	}
	public function setIs_subscribe($is_subscribe)
	{
		$this->is_subscribe = $is_subscribe;
	}
	public function getBank_type()
	{
		return $this->bank_type;
	}
	public function setBank_type($bank_type)
	{
		$this->bank_type = $bank_type;
	}
	public function getRefund_channel()
	{
		return $this->refund_channel;
	}
	public function setRefund_channel($refund_channel)
	{
		$this->refund_channel = $refund_channel;
	}
	public function getAdd_time()
	{
		return $this->add_time->format('Y-m-d H:i:s');
	}
	public function setAdd_time($add_time)
	{
		$this->add_time = new \DateTime($add_time);
	}
	public function getModify_time()
	{
		return $this->modify_time->format('Y-m-d H:i:s');
	}
	public function setModify_time($modify_time)
	{
		$this->modify_time = new \DateTime($modify_time);
	}
}
