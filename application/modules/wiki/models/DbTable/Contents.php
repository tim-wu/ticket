<?php

/**
 * Description of Content
 *
 * @author Ron
 */
class Wiki_Model_DbTable_Contents extends Wiki_Model_DbTable_Abstract{
    protected $_name = 'wiki_contents';
    protected $_sequence = false;  
    protected $_referenceMap = array(  
        'TopicsRef' => array(  
            'columns'       => 'tid',  
            'refTableClass' => 'Wiki_Model_DbTable_Topics',  
            'refColumns'    => 'id'  
        )  
    );
    /**
     *
     * @var Zend_Db_Adapter_Abstract 
     */
    protected $_db;
    protected $__tid;
    protected $__uid;
    protected $__create_time;
    protected $__content;
    protected $__preversion_id;
    protected $__attachment;
    protected $__is_default;
    protected $__status;
    
    public function init(){
        $this->_db = Zend_Registry::get("db");
    }
    public function SetAsDefault($contentId,$topicId) {
        $this->__is_default = 0;
        $where = $this->_db->quoteInto('is_default = 1 And tid = ?', $topicId);
        $this->change($where);
        $this->__is_default = 1;
        $where = $this->_db->quoteInto('id = ?', $contentId);
        $this->change($where);
    }
    public function GetOneById($Id){
        $rowset = $this->find($Id);
        return $rowset[0];
    }

    public function Revert($contentId,$uid) {
        $row = $this->GetOneById($contentId);
        $tid = $row->tid;
        $preversion_id = $contentId;
        $content = $row->content;
        $insertId = $this->CreateContent($tid, $uid, $content, $preversion_id, TRUE);
        return $insertId;
    }
    
    public function CreateContent($tid,$uid,$content,$preversion_id,$is_set_default){
        $this->__tid = $tid;
        $this->__content = $content;
        $this->__uid = $uid;
        $this->__is_default = 0;
        $this->__status = 1;
        $this->__preversion_id = $preversion_id;
        $this->__create_time = date('Y-m-d H:i:s');
        $this->create();
        if($is_set_default){
            $insertId = $this->_db->lastInsertId();
            $insertId != FALSE ? $this->SetAsDefault($insertId, $tid) : die('insert error');
        }
        return $insertId;
    }

    public function Clear($tid=46) {
        $select = $this->select();
        $select->from($this->_name, array(new Zend_Db_Expr('COUNT(*)')))
                ->where('tid=?', $tid);
        //$st = $select->query(PDO::FETCH_ASSOC)->fetch();
//        $select->from($this->_name, array('id'))
//                ->where('tid=?', $tid)
//                ->order('id DESC')
//                ->limit(20);
//        $data = $this->fetchAll($select);
//        $ids = array();
//        foreach ($data as $row) {
//            $ids[]=$row['id'];
//        }
//        $where = $this->_db->quoteInto('NOT IN(?)',$ids);
        //$this->delete($where);
    }
    
}
