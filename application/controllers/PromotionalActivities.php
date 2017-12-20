<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 蓝景移动客户服务中心促销活动页面，不包含直接打折信息
 * 此接口只返回移动专题列表数据
 * @author 任广正
 * @date 2017-12-12
 */
class PromotionalActivities extends MY_Controller
{
    /**
     * 移动专题促销活动列表
     * @param [limit] 每页显示条数，默认显示所有
     * @param [offset] 偏移量，从第几条开始显示
     * @return id 专题主表主键
     * @return activityImg 封面图
     * @return activityName 专题名称
     * @return clicks 点击量
     * @return releaseTime 发布时间，同时也是最近更新时间
     * @return url 专题详情地址
     * @return count 总条数
     * @return state 成功返回1
     */
    public function index_get()
    {
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        //选择db_h5库
        $this->load->library('doctrine', array("db_h5"), 'db_h5');
        //实例化一个queryBuilder对象执行查询
        $this->load->library('queryBuilder', array($this->db_h5));
        //查询专题主表
        $criteria['from'] = array('scene', 's');
        //查询字段
        $criteria['select'] = array('s.sceneid_bigint as id', 's.thumbnail_varchar as activityImg', 's.scenename_varchar as activityName', 's.hitcount_int as clicks', 's.publishTime as releaseTime', 's.scenecode_varchar as url');
        //拼接查询条件
        $where['s.publishTime'] = array('neq', '');//已发布的
        $where['s.showstatus_int'] = array('eq', 1);//正开放的
        $where['s.delete_int'] = array('eq', 0);//未删除的
        $criteria['where'] = $where;
        //id倒序显示
        $criteria['order'] = array('s.publishTime', 'DESC');
        if ($limit !== FALSE) $criteria['limit'] = intval($limit);
        if ($offset !== FALSE) $criteria['offset'] = intval($offset);
        //执行查询
        $data = $this->querybuilder->getTotal($criteria);
        foreach ($data as $key => $val)
        {
            $data[$key]['url'] = MOBILE_TOPIC_URL . 'v-' . $val['url'];
            $data[$key]['activityImg'] = MOBILE_TOPIC_URL . 'Uploads/' . $val['activityImg'];
            $data[$key]['releaseTime'] = date('Y-m-d H:i:s', $val['releaseTime']);
        }
        //查询总数
        $count = $this->querybuilder->getTotalNum($criteria);
        $result['state'] = 1;
        $result['activityList'] = $data;
        $result['count'] = $count;
        $this->response($result);
    }
    /* -------------------------- 接口成功返回值示例 start --------------------------
    object(stdClass)#67 (3) {
      ["state"]=> int(1)
      ["activityList"]=> array(7) {
        [0]=> object(stdClass)#68 (6) {
          ["id"]=> int(20) //专题主表主键
          ["activityImg"]=> string(50) "http://192.168.9.141:8013/Uploads/default_thum.jpg" //封面图
          ["activityName"]=> string(16) "2018促销活动" //专题名称
          ["clicks"]=> int(2) //点击量
          ["releaseTime"]=> string(19) "2017-12-13 15:30:05" //发布时间，同时也是最近更新时间
          ["url"]=> string(38) "http://192.168.9.141:8013/v-U81255K4B2" //专题详情地址
        }
        ...
      }
      ["count"]=> int(7) //总条数
    }
     -------------------------- 接口成功返回值示例 end -------------------------- */
}
