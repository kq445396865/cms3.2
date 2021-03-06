<?php 
namespace Common\Model;
use Think\Model;




class NewsModel extends Model{

	private $_db = '';
	public function __construct(){
		$this->_db = M("news");
	}

	public function select($data=array(),$limit=100){
         $conditions = $data;
         return $this->_db->where($conditions)->order('news_id desc')->limit($limit)->select();
	}

	public function insert($data = array()){
         if(!$data || !is_array($data)){
             return 0;
         }

         $data['create_time'] = time();
         $data['username'] = getLoginUserName();

         return $this->_db->add($data);
	}

    //获取文章列表
	public function getNews($data,$page,$pageSize=10){
		$conditions = $data;
		if(isset($data['title']) && $data['title']){
              $conditions['title'] = array('like','%'.$data['title'].'%');
		}
		if(isset($data['catid']) && $data['catid']){
              $conditions['caiid'] = intval($data['catid']);
		}
        $conditions['status'] = array('neq',-1);
		$offset = ($page - 1) * $pageSize;
        return $this->_db->where($conditions)->order('news_id desc')->limit($offset,$pageSize)->select();

	}
    //获取文章总数
	public function getNewsCount($data=array()){
		$conditions = $data;
		if(isset($data['title']) && $data['title']){
              $conditions['title'] = array('like','%'.$data['title'].'%');
		}
		if(isset($data['catid']) && $data['catid']){
              $conditions['caiid'] = intval($data['catid']);
		}

		$conditions['status'] = array('neq',-1);
		return $this->_db->where($conditions)->count();
         
	}


	public function find($id){

		if(!$id || !is_numeric($id)){

              return array();
		}

		return $this->_db->where('news_id='.$id)->find();
	}


	public function UpdataNewsById($id,$data){

		if(!$id || !is_numeric($id)){
              
              throw_exception("ID不合法");

		}
		if(!$data || !is_array($data)){
              
              throw_exception("更新数据不合法");

		}

		return $this->_db->where('news_id='.$id)->save($data);

	}



	public function UpdataStatusById($id,$status){

              //判断id是否存在或者是数字     
              if(!$id || !is_numeric($id)){
              	
                  throw_exception('ID不合法');
              }

              if(!is_numeric($status)){

                  throw_exception('status不能为非数字');
              }

              $data['status'] = $status;

              return $this->_db->where('news_id='.$id)->save($data);
    }


    public function UpdataCount($id,$count){
    	if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
    	}
    	if(!is_numeric($count)){
            throw_exception('count不能为非数字');
    	}
    	$data['count'] = $count;

    	return $this->_db->where('news_id='.$id)->save($data);
    }


    public function getNewsByNewsIdIn($newsId){

         if(!is_array($newsId)){
             throw_exception('参数不合法');
         }

         $data = array(

          'news_id' => array('in',implode(',',$newsId)), 
        );

         return $this->_db->where($data)->select();

    }


    public function getNewsByTitle($title){
         if($title){
            $conditions['title'] = array('like','%'.$title.'%');
         }
         return $this->_db->where($conditions)->find();
    }

}


 ?>