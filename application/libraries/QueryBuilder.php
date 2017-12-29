<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 使用queryBuilder构建DQL语句
 * @function getTotal() 查询多条
 * @function getTotalNum() 查询总数
 * @function 批量修改 $queryBuilder->update('table','t')->where($where)->set($data);
 * @author 任广正
 * @date 2017-12-11 20:38:54
 */
class QueryBuilder
{
	private $CI;
	public $qb;//QueryBuilder对象
	private $aliasDoctrine;//加载实体类使用

	/**
	 * 构造函数
	 * @param $doctrine 如果你需要切库的话请传入重新实例化后的doctrine对象
	 */
	public function __construct($doctrine=array())
	{
		$this->CI = &get_instance();
		if(empty($doctrine))
		{
			//构建一个QueryBuilder对象
			$this->aliasDoctrine = $this->CI->doctrine;
		}else
		{
			//构建一个指定数据库的QueryBuilder对象
			$this->aliasDoctrine = $doctrine[0];

		}
		$this->qb = $this->aliasDoctrine->em->createQueryBuilder();
	}
	/**
	 * 按条件查询
	 * $criteria['from'] = array('articleModel','t');
	 * $criteria['select'] = array('t.id','t.title');
	 * $where['t.id'] = array('eq',1[,'or']);
	 * $where['t.cate_id'] = array('in',array(1, 2, 3)[,'or']);
	 * $where['t.title'] = array('like','%test%'[,'or']);
	 * $where['t.id'] = array('between',array(1,10)[,'or']);
	 * $criteria['where'] = $where;
	 * $criteria['order'] = array('t.id','DESC');
	 * $criteria['join'] = array('CateModel', 'c', 't.cate_id  = c.id');//默认左查询
	 * $criteria['innerJoin'] = array('CateModel', 'c', 't.cate_id  = c.id');
	 * $criteria['offset'] = 0;
	 * $criteria['limit'] = 15;
	 * $criteria['group'] = 't.cate_id';
	 * @param $criteria
	 * @return array 二维数组
	 */
	public function getTotal($criteria = array())
	{
		$this->from($criteria['from']);
		$select = !isset($criteria['select']) ? $criteria['from'][1] : $criteria['select'];
		$this->select($select);
		$where = isset($criteria['where']) ? $criteria['where'] : array();
		$this->where($where);
		if(isset($criteria['order'])) $this->orderBy($criteria['order']);
		if(isset($criteria['join'])) $this->join($criteria['join']);
		if(isset($criteria['innerJoin'])) $this->innerJoin($criteria['innerJoin']);
		if(isset($criteria['offset'])) $this->offset($criteria['offset']);
		if(isset($criteria['limit'])) $this->limit($criteria['limit']);
		if(isset($criteria['group'])) $this->groupBy($criteria['group']);
		$result = $this->query();
		return $result;
	}
	/**
	 * 查询总数
	 * $criteria['from'] = array('articleModel','t');
	 * $where['t.id'] = array('eq',1[,'or']);
	 * $where['t.cate_id'] = array('in',array(1, 2, 3)[,'or']);
	 * $where['t.title'] = array('like','%test%'[,'or']);
	 * $where['t.id'] = array('between',array(1,10)[,'or']);
	 * $criteria['where'] = $where;
	 * $criteria['join'] = array('CateModel', 'c', 't.cate_id  = c.id');//默认左查询
	 * $criteria['innerJoin'] = array('CateModel', 'c', 't.cate_id  = c.id');
	 * $criteria['group'] = 't.cate_id';
	 * @param $criteria
	 * @return int
	 */
	public function getTotalNum($criteria = array())
	{
		$this->from($criteria['from']);
		$select = 'COUNT('.$criteria['from'][1].') totalNums';
		$this->select($select);
		$where = isset($criteria['where']) ? $criteria['where'] : array();
		$this->where($where);
		if(isset($criteria['join'])) $this->join($criteria['join']);
		if(isset($criteria['innerJoin'])) $this->innerJoin($criteria['innerJoin']);
		$this->offset(0);
		$this->limit(1);
		if(isset($criteria['group'])) $this->groupBy($criteria['group']);
		$result = $this->query();
		if(empty($result)) return 0;
		return intval($result[0]['totalNums']);
	}
	/**
	 * 批量更新
	 * <code>
	 *     $qb ->update('user', 'u')
	 *         ->where($where)
	 *         ->set(array('u.name'=>'admin','u.pwd'=>md5(123456)));
	 * </code>
	 * @param $update 实体类名
	 * @param $alias 别名
	 * @return 受影响的行数
	 */
	public function update($update=null, $alias=null)
	{
		//加载实体类
		$this->aliasDoctrine->requireEntity($update);
		$this->qb->update($update,$alias);
		return $this->qb->getQuery()->execute();
	}
	/**
	 * 构建修改字段
	 * @param $data 需要修改的数据(一维数组)
	 */
	public function set(array $data=array())
	{
		$i = 1000;
		foreach ($data as $key=>$val)
		{
			$i++;
			$this->qb->set($key,'?'.$i);
			//绑定参数
			$this->qb->setParameter($i,$val);
		}
		return $this;
	}
	/**
	 * 删除操作
	 * <code>
	 *     $qb->where($where)->delete('user','u);
	 * </code>
	 */
	public function delete($delete = null, $alias = null)
	{
		//加载实体类
		$this->aliasDoctrine->requireEntity($delete);
		if(empty($delete) || empty($alias)) return FALSE;
		$this->qb->delete($delete,$alias);
		return $this->qb->getQuery()->execute();
	}
	/**
	 * 构建from字段
	 * @param $from array(实体类名称,别名)
	 * @return no return
	 */
	private function from($from=array())
	{
		//加载实体类
		$this->aliasDoctrine->requireEntity($from[0]);
		$this->qb->from($from[0],$from[1]);
	}
	/**
	 * 构建select字段
	 * @param $select 支持字符串或数组
	 * 例1：'u.id,u.title'
	 * 例2：array('u.id','u.title')
	 * 例3：如果需要查询所有字段，直接传入别名即可
	 * @return no return
	 */
	private function select($select)
	{
		$this->qb->select($select);
	}
	/**
	 * 构建左关联查询语句
	 */
	private function join($join)
	{
		$this->qb->leftJoin($join[0], $join[1], 'WITH', $join[2]);
	}
	/**
	 * 执行查询
	 * @return array 二维数组
	 */
	private function query()
	{
		$data = $this->qb->getQuery()->getArrayResult();
		unset($this->qb);
		$this->qb = $this->aliasDoctrine->em->createQueryBuilder();
		return $data;
	}
	/**
	 * 构建where条件
	 * $where['t.id'] = array('eq',1);
	 * $where['t.id'] = array('neq',1);
	 * $where['t.id'] = array('lt',1);
	 * $where['t.id'] = array('lte',1);
	 * $where['t.id'] = array('gt',1);
	 * $where['t.id'] = array('gte',1);
	 * $where['t.cate_id'] = array('in',array(1, 2, 3)[,'or']);
	 * $where['t.cate_id'] = array('notIn',array(1, 2, 3)[,'or']);
	 * $where['t.title'] = array('like','%test%'[,'or']);
	 * $where['t.title'] = array('notLike','%test%'[,'or']);
	 * $where['t.id'] = array('between',array(1,10)[,'or']);
	 * @return no return
	 */
	public function where(array $where=array())
	{
		if(empty($where)) return;
		//使用Expr类，帮助构建表达式
		$expr = $this->qb->expr();
		$i = 0;
		foreach ($where as $key=>$val)
		{
			$i++;
			if(!isset($val[2]))
			{
				//默认 and 条件查询
				if(strtolower($val[0]) == 'between')
				{
					$this->between($key,$val[1]);
					continue;
				}
				$condition = $expr->andX($expr->{$val[0]}($key, '?'.$i));
				$this->qb->andWhere($condition);
			}else
			{
				//其他查询条件 or in ...
				switch (strtolower($val[2]))
				{
					case 'or':
						if(strtolower($val[0]) == 'between')
						{
							$this->between($key,$val[1],$val[2]);
							continue;
						}
						$condition = $expr->orX($expr->{$val[0]}($key, '?'.$i));
						$this->qb->orWhere($condition);
						break;
					default:
						$condition = $expr->$val[2]($expr->{$val[0]}($key, '?'.$i));
						$this->qb->andWhere($condition);
						break;
				}
			}
			//绑定参数
			$this->qb->setParameter($i,$val[1]);
		}
		return $this;
	}
	/**
	 * 构建between语句
	 */
	private function between($field,$between=array(),$con='and')
	{
		$condition = $this->qb->expr()->between($field, '?1', '?2');
		if($con != 'and')
		{
			$this->qb->orWhere($condition);
		}else
		{
			$this->qb->andWhere($condition);
		}
		//绑定参数
		$this->qb->setParameter(1,$between[0]);
		$this->qb->setParameter(2,$between[1]);
	}
	/**
	 * 构建 order by 语句
	 */
	public function orderBy($order)
	{
		$this->qb->orderBy($order[0],$order[1]);
	}
	/**
	 * 构建innerJoin语句
	 */
	private function innerJoin($join)
	{
		$this->qb->innerJoin($join[0], $join[1], 'WITH', $join[2]);
	}
	/**
	 * 构建offset语句
	 */
	public function offset($offset=0)
	{
		$this->qb->setFirstResult(intval($offset));
	}
	/**
	 * 构建limit语句
	 */
	public function limit($limit=20)
	{
		$this->qb->setMaxResults(intval($limit));
	}
	/**
	 * 构建groupBy语句
	 */
	private function groupBy($group)
	{
		$this->qb->groupBy($group);
	}
	/**
	 * 执行查询
	 * @return array 数组下是一个一个对象，对象属性需使用getter方法获取
	 */
	public function getObjectResult()
	{
		return $this->qb->getQuery()->getResult();
	}

	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		unset($this->CI,$this->qb,$this->aliasDoctrine);
	}
}

/* End of file QueryBuilder.php */
/* Location: ./application/libraries/QueryBuilder.php */