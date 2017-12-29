<?php
/**
 * 实体类的基类
 * User: guangzheng
 * Date: 17-12-20
 * Time: 下午4:40
 */
class Model
{
	/**
	 * 魔术方法设置属性
	 * @param $key
	 * @param $val
	 */
	public function __set($key,$val)
	{
		$this->$key = $val;
	}

	/**
	 * 魔术方法获取属性
	 * @param $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	/**
	 * 将当前对象转换成数组返回
	 * @return array
	 */
	public function getValues()
	{
		$array = get_object_vars($this);
		foreach ($array as $key => $value)
		{
			if(is_object($value))
			{
				$functionName = 'get'.ucfirst($key);
				$array[$key] = $this->$functionName();
			}
		}
		return $array;
	}
}