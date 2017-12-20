<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader,
    Doctrine\DBAL\Logging\EchoSQLLogger,
    Doctrine\Common\Cache\ArrayCache;
class Doctrine{
    public $em = null; 	// Doctrine实体管理器
    private $config = null;    //Doctrine配置信息
    private $conn;		//数据库配置信息
    private $entityPath = null; //entity所在目录

    /**
     * Doctrine constructor.
     */
	public function __construct($array = array('default'))
	{
		require_once APPPATH."/vendor/autoload.php";
        //配置数据库信息
        $sign = $array[0];
        $dbConfig = json_decode(DB_CONFIG,true);
        $array = $dbConfig[$sign];
        $this->entityPath = $array['entityPath'];
		$this->linkDatabase($array);
		$this->em = $this->getEntityManager();
	}
	/**
	* 链接数据库
	*/
	protected  function  linkDatabase($array)
	{
		//数据库配置信息
		$conn = array(
		    'driver' => 'pdo_mysql',
		    'user' =>    $array['user'],
		    'password' => $array['password'],
		    'host' =>     $array['host'],
		    'dbname' =>   $array['dbname'],
		    'charset' => $array['charset']
		);
		$isDevMode = IS_DEVMODE;
		$this->config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/models/".$this->entityPath), $isDevMode);
		$this->conn = $conn;
	}
	/**
	* 获取实体类管理句柄
	*/
	protected function getEntityManager()
	{
		$cache = new ArrayCache;
		$this->config->setMetadataCacheImpl($cache);
		$driverImpl = $this->config->newDefaultAnnotationDriver(array(__DIR__.'/models/'.$this->entityPath));
		$this->config->setMetadataDriverImpl($driverImpl);
		$this->config->setQueryCacheImpl($cache);

		$this->config->setQueryCacheImpl($cache);

		// Proxy configuration
		$this->config->setProxyDir(__DIR__.'/models/proxies');
		$this->config->setProxyNamespace('Proxies');

		// Set up logger
		if(SET_LOG )
		{
			$logger = new EchoSQLLogger;
			$this->config->setSQLLogger($logger);	
		}
		$this->config->setAutoGenerateProxyClasses( TRUE );

		// obtaining the entity manager
		return   EntityManager::create($this->conn, $this->config);
	}
	/**
	*  加载Entity实体类文件
	* @param $class  类文件名称
	*                 $prefix 目录
	*/
	public function requireEntity($class, $prefix = null)
	{
		$filepath = APPPATH.'models/'.$this->entityPath.$prefix.DIRECTORY_SEPARATOR.$class.'.php';
		if (file_exists($filepath))
		{
			require_once($filepath);
		}else
		{
			exit('This entity class does not exist : '.$filepath);
		}
	}
}