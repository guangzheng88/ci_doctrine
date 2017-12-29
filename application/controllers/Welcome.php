<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 项目 demo
 * 仅供参考!!!
 * @function createEntities($table='',$dbname='default') 生成实体类
 * @function queryBuilder() 使用queryBuilder实现基本列表查询
 * @function add() 新增一条数据
 * @function updateById($id) 更新一条数据
 * @function deleteById($id) 删除一条数据
 * @function update() 修改多条数据
 * @function delete() 删除多条数据
 * @author: 任广正
 * @date: 17-12-12 下午5:10
 */
class Welcome extends CI_Controller
{
	private $tableComment;//表注释
	private $tableName;//表名
	private $dbName;//数据库名

	public function __construct()
	{
		parent::__construct();
		//只有在开发环境demo才可以被使用
		if(ENVIRONMENT != 'development')
		{
			exit('404 Not Found.check the api url.');
		}
	}
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		exit('Welcome to '.BASE_URL);
	}
	/**
	 * 添加一条数据
	 */
	public function add()
	{
		//加载实体类
		$this->doctrine->requireEntity('wechat_annal');
		//创建实体对象
		$entityObj = new Wechat_annal();
		//设置相应属性
		$entityObj->setAppid('appid007');
		$entityObj->setBank_type('CBI');
		$entityObj->setCharge(3.05);
		$entityObj->setContract_number('151200001210');
		$entityObj->setMch_appid('mchappid001');
		$entityObj->setOut_trade_no('W-1512-2017-001');
		//持久化对象到数据库
		$this->doctrine->em->persist($entityObj);
		try
		{
			$this->doctrine->em->flush();
		}catch(Exception $e)
		{
			exit('新增失败');
		}
		//获取自增ID
		$pKey = $entityObj->getId();
		if(is_numeric($pKey))
		{
			echo '新增数据成功，自增ID为:'.$pKey;
		}else
		{
			echo '新增数据失败';
		}
	}
	/**
	 * 更新操作
	 * @return no return
	 */
	public function updateById($id)
	{
		//加载实体类
		$this->doctrine->requireEntity('wechat_annal');
		//通过接收到的id查询需要修改的数据
		$data = $this->doctrine->em->find('wechat_annal',intval($id));
		if($data == NULL) exit('未查询到数据');
		//重置要修改的字段
		$data->setAppid('update_appid_007');
		$data->setModify_time(date('Y-m-d H:i:s'));
		$result = $this->doctrine->em->flush();
		//打印结果为NULL
		var_dump($result);
	}
	/**
	 * 删除操作
	 */
	public function deleteById($id)
	{
		//加载实体类
		$this->doctrine->requireEntity('wechat_annal');
		//通过接收到的id查询需要修改的数据
		$data = $this->doctrine->em->find('wechat_annal',intval($id));
		if($data == NULL) exit('未查询到数据');
		//执行删除
		$this->doctrine->em->remove($data);
		$result = $this->doctrine->em->flush();
		//打印结果为NULL
		var_dump($result);
	}
	/**
	 * 使用queryBuilder更新多条数据
	 * @return 受影响的行数
	 */
	public function update()
	{
		//实例化一个queryBuilder对象
		$this->load->library('queryBuilder','','queryBuilder');
		//修改数据
		$data['w.appid'] = 'test_update_appid';
		$data['w.modify_time'] = date('Y-m-d H:i:s');
		$where['w.id'] = array('gt',1001379);//id大于1001379
		//注意最后调用update方法
		$result = $this->queryBuilder->where($where)->set($data)->update('wechat_annal','w');
		var_dump($result);
	}
	/**
	 * 使用queryBuilder删除多条数据
	 */
	public function delete()
	{
		//实例化一个queryBuilder对象
		$this->load->library('queryBuilder','','queryBuilder');
		//删除条件
		$where['w.add_time'] = array('lte','2017-06-27 23:11:01');
		$this->queryBuilder->where($where);
		$where['w.add_time'] = array('gte','2017-06-27 00:11:01');
		$where['w.id'] = array('lte','8','or');
		$result = $this->queryBuilder->where($where)->delete('wechat_annal','w');
		var_dump($result);
	}
	/**
	 * 使用queryBuilder实现基本列表查询
	 * 更多where条件请查看 libraries/QueryBuilder.php
	 */
	public function queryBuilder()
	{
		$criteria['from'] = array('wechat_annal','w');
		$criteria['select'] = array('w');
		$criteria['where']['w.id'] = array('gt',0);//大于0
		$criteria['where']['w.appid'] = array('neq','');//不等于空
		$criteria['where']['w.consume_id'] = array('eq',110,'or');//或等于110
		$criteria['order'] = array('w.id','desc');
		$criteria['offset'] = 0;
		$criteria['limit'] = 10;
		//实例化一个queryBuilder对象执行查询
		$this->load->library('queryBuilder','','queryBuilder');
		//查询总数
		$count = $this->queryBuilder->getTotalNum($criteria);
		//查询数据
		$data = $this->queryBuilder->getTotal($criteria);
		echo '<pre>总条数 = '.$count;
		echo '<hr>查询到的数据为:';
		var_dump($data);
	}
	/**
	 * 建立新项目后，首先需要配置constants.php
	 * 配置好数据库配置，通过访问此方法生成实体类
	 * 生成的实体类可满足大部分要求，但不可完全依赖此，请根据项目需求做适当调整
	 * 示例:http://localhost/welcome/createEntities/wechat_annal/db_ljlj
	 * @param table 表名
	 * @param dbname 选库，默认为default配置，这里的不是真正数据库的名字，而是你数据库配置的下标
	 */
	public function createEntities($table='',$dbname='default')
	{
		$this->tableName = $this->security->xss_clean($table);
		$dbname = $this->security->xss_clean($dbname);
		if(empty($table)) exit('error : The table name must has');
		$tableStructure = $this->getTableStructure($dbname);
		$content = $this->getContent($tableStructure);
		$filename = $this->write($content);
		echo 'Successful : '.$filename;
	}
	/**
	 * 获取表结构
	 */
	private function getTableStructure($dbname)
	{
		//获取数据库配置
		$dbConfig = json_decode(DB_CONFIG,true);
		if(!isset($dbConfig[$dbname])) exit('error : constants dbconfig error');
		$config['hostname'] = $dbConfig[$dbname]['host'];
		$config['username'] = $dbConfig[$dbname]['user'];
		$config['password'] = $dbConfig[$dbname]['password'];
		$config['database'] = $dbConfig[$dbname]['dbname'];
		$config['dbdriver'] = 'mysqli';
		$config['char_set'] = $dbConfig[$dbname]['charset'];
		$this->dbName = $config['database'];
		$this->load->database($config);
		//查看字段及注释
		$sql = 'show full columns from '.$this->tableName;
		$result = $this->db->query($sql);
		if(!$result) exit('error : database query error');
		$data = $result->result_array();
		//查看关于表的注释
		unset($this->db);
		$sql = 'select * from TABLES where TABLE_SCHEMA="'.$config['database'].'" and TABLE_NAME="'.$this->tableName.'"';
		$config['database'] = 'information_schema';
		$this->load->database($config);
		$result = $this->db->query($sql);
		$comData = $result->result_array();
		$this->tableComment = $comData[0]['TABLE_COMMENT'];
		return $data;
	}
	/**
	 * 生成实体类内容
	 */
	private function getContent($tableStructure)
	{
		$text = '';
		$text .= "<?php\n";
		$text .="/**\n";
		$text .=" * ".$this->dbName.'.'.$this->tableName.' '.$this->tableComment."实体类\n";
		$text .=" * @author Your name\n";
		$text .=" * @date ".date('Y-m-d H:i:s')."\n";
		$text .= " * @Entity @Table(name=\"".$this->tableName."\")\n";
		$text .=" */\n";
		$text .="include_once APPPATH.'models/Model.php';\n";
		$text .= "class ".ucfirst($this->tableName)." extends Model\n";
		$text .= "{\n";
		$i = 0;
//        var_dump($tableStructure);
		$ctStr = '';
		foreach ($tableStructure as $key=>$val)
		{
			preg_match("/\w+(-\r?\n?\w+)?/", $val['Type'],$type);//匹配单词
			if(!empty($type)) $type = $type[0];
			switch ($type)
			{
				case 'int':
					$fieldType = 'integer';
					break;
				case 'varchar':
				case 'char':
					$fieldType = 'string';
					break;
				default:
					$fieldType = $type;
					break;
			}
			$tableStructure[$key]['fieldType'] = $fieldType;
			$i++;
			if($i == 1)
			{
				$text .= "\t/**\n";
				if(!empty($val['Comment']))
				{
					$text .= "\t * ".$val['Comment']."\n";
				}
				$text .= "\t * @Id\n";
				$text .= "\t * @GeneratedValue\n";
				$text .= "\t * @Column(type=\"".$fieldType."\")\n";
				$text .= "\t */\n";
			}else
			{
				$text .= "\t/**\n";
				if(!empty($val['Comment']))
				{
					$text .= "\t * ".$val['Comment']."\n";
				}
				$text .= "\t * @Column(type=\"".$fieldType."\")\n";
				$text .= "\t */\n";

			}
			$ctVal = is_numeric($val['Default']) ? $val['Default'] : "'".$val['Default']."'";
			if(!empty($val['Comment']) && $fieldType != 'date' && $fieldType != 'datetime' && $i != 1)
			{
				$text .= "\tprotected \$".$val['Field']." = ".$ctVal.";\n";
			}else
			{
				$text .= "\tprotected \$".$val['Field'].";\n";
			}
			if($fieldType == 'date' || $fieldType == 'datetime')
			{
				$ctStr .= "\t\t\$this->".$val['Field']." = new \\DateTime();\n";
			}
			$text .= "\n";
			unset($type);
		}
        $text .= "\t/**\n";
        $text .= "\t * constructor\n";
        $text .= "\t */\n";
        $text .= "\tpublic function __construct()\n";
        $text .= "\t{\n";
        $text .= $ctStr;
        $text .= "\t}\n";
		foreach ($tableStructure as $key=>$val)
		{
			$text .= "\tpublic function get".ucfirst($val['Field'])."()\n";
			$text .= "\t{\n";
			if($val['fieldType'] == 'date')
			{
				$text .= "\t\treturn \$this->".$val['Field']."->format('Y-m-d');\n";
				$text .= "\t}\n";
				$text .= "\tpublic function set".ucfirst($val['Field'])."(\$".$val['Field'].")\n";
				$text .= "\t{\n";
				$text .= "\t\t\$this->".$val['Field']." = new \DateTime(\$".$val['Field'].");\n";

			}else if($val['fieldType'] == 'datetime')
			{
				$text .= "\t\treturn \$this->".$val['Field']."->format('Y-m-d H:i:s');\n";
				$text .= "\t}\n";
				$text .= "\tpublic function set".ucfirst($val['Field'])."(\$".$val['Field'].")\n";
				$text .= "\t{\n";
				$text .= "\t\t\$this->".$val['Field']." = new \DateTime(\$".$val['Field'].");\n";
			}else
			{
				$text .= "\t\treturn \$this->".$val['Field'].";\n";
				$text .= "\t}\n";
				$text .= "\tpublic function set".ucfirst($val['Field'])."(\$".$val['Field'].")\n";
				$text .= "\t{\n";
				$text .= "\t\t\$this->".$val['Field']." = \$".$val['Field'].";\n";
			}
			$text .= "\t}\n";
		}
		$text .= "}\n";
		return $text;
	}

	/**
	 * 写文件
	 */
	private function write($content)
	{
		//创建目录
		$dir = APPPATH.'models/'.$this->dbName;
		$res = $this->mkDirs($dir);
		if(!$res == true) exit('error : 创建目录失败，请检查是否拥有权限');
		//获取文件名
		$filename = $dir.'/'.lcfirst($this->tableName).'.php';
		if(!file_exists($filename))
		{
//\t     echo '<textarea style="width:600px;height:600px;">';
//             echo $content;
//             echo '</textarea>';
//             exit();
			$myfile = fopen($filename,"w") or die("权限不足");
			fwrite($myfile,$content);
			fclose($myfile);
			chmod($filename,0666);
		}else
		{
			exit("error : file_exists : ".$filename);
		}
		return $filename;
	}
	/**
	 * 递归创建目录
	 */
	private function mkDirs($dir){
		if(!is_dir($dir)){
			if(!$this->mkDirs(dirname($dir))){
				return false;
			}
			if(!@mkdir($dir,0555)){
				return false;
			}
			chmod($dir,0777);
		}
		return true;
	}
}
