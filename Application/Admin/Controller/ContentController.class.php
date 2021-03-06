<?php 
namespace Admin\Controller;
use Think\Controller;


/**
 * 文章类
 */
class ContentController extends CommonController
{
	public function index(){
        $conds = array();
        if($_GET['title']){
        	$conds['title'] = $_GET['title'];
        }
        if($_GET['catid']){
            $conds['catid'] = intval($_GET['catid']);
        }

        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 5;
		$news = D("News")->getNews($conds,$page,$pageSize);
		$newsCount = D("News")->getNewsCount($conds);

        $position = D("Position")->getNormalPositions();

		$res = new \Think\Page($newsCount,$pageSize);

		$pageRes = $res->show();
		
		$this->assign('positions',$position);

		$this->assign('news',$news);

		$this->assign('pageRes',$pageRes);

		$this->assign('result',array(
            'catid' => $conds['catid'],
            'title' => $conds['title'],

		));

		$webSiteMenu = D("Menu")->getNavMenus();
		$this->assign('webSiteMenu',$webSiteMenu);
		$this->display();
	}

	//添加文章

	public function add(){
		
		if($_POST){

			if(!isset($_POST['title']) || !$_POST['title']){
				 return show(0,'文章标题不能为空');
			}
			
			if(!isset($_POST['catid']) || !$_POST['catid']){
                 return show(0,'所属栏目不能为空');
			}

			if(!isset($_POST['content']) || !$_POST['content']){
                 return show(0,'文章内容不能为空');
			}
            $res = D("News")->getNewsByTitle($_POST['title']);
            if($res){
                return show(0,'文章标题重复');
            }
            if($_POST['news_id']){

            	 return $this->save($_POST);

            }
			$newsId = D("News")->insert($_POST);
			if($newsId){
                 $newsContentData['content'] = $_POST['content'];
                 $newsContentData['news_id'] = $newsId;
                 $contentId = D("NewsContent")->insert($newsContentData);
                 if($contentId){
                       show(1,'新增成功');
                 }else{
                 	   show(1,'主表插入成功,副标插入失败');
                 }
			}else{
				show(0,'插入失败');
			}
		}else{

            $webSiteMenu = D("Menu")->getNavMenus();
			$this->assign('webSiteMenu',$webSiteMenu);
			$this->display();
			
		}
		
	}

	//修改文章
	public function edit(){

		$newsId = $_GET['id'];
        if(!$newsId){

           $this->redirect('/admin.php?c=content');

        }
        $news = D("News")->find($newsId);

        if(!$news){

        	$this->redirect('/admin.php?c=content');

        }
        $newsCon = D("NewsContent")->find($newsId);

        if($newsCon){

        	$news['content'] = $newsCon['content'];

        }
        $webSiteMenu = D("Menu")->getNavMenus();
        $this->assign('webSiteMenu',$webSiteMenu);
        $this->assign('news',$news);
		$this->display();
	}

	//更新文章
	public function save($data){

		$newsId = $data['news_id'];

		unset($data['news_id']);

        try {
	        	$id = D("News")->UpdataNewsById($newsId,$data);
				$newsContentData['content'] = $data['content'];
				$conId = D("NewsContent")->UpdataNewsContentById($newsId,$newsContentData);

				if($id===false || $conId===false){

					return show(0,'更新失败');

				}
			    return show(1,'更新成功');

        } catch (Exception $e) {

        	return show(0,$e->getMessage());
        }
		
	}

	//删除功能（改变栏目状态）
	public function setStatus(){
		
      try {
      	  		if($_POST){
					$id = $_POST['id'];
					$status = $_POST['status'];

					$res = D("News")->UpdataStatusById($id,$status);
					if($res){
						return show(1,'操作成功');
					}
					    return show(0,'操作失败'); 
				}


      } catch (Exception $e) {
      	  
      	  return show(0,$e->getMessage());
      }

         return show(0,'没有提交数据');

	}


	public function push(){

		$jumpUrl = $_SERVER['HTTP_REFERER'];
		$positionId = intval($_POST['position_id']);
		$newsId = $_POST['push'];

		if(!$positionId){
           return show(0,'推荐位不存在');
		}

		if(!$newsId || !is_array($newsId)){
           return show(0,'请选择推荐的文章ID进行推荐');
		}
        try {
	        	$news = D("News")->getNewsByNewsIdIn($newsId);
				if(!$news){
		 			return show(0,'没有相关内容');
				}

				foreach($news as $new){
		            $data = array(
		            	'position_id' => $positionId,
		            	'title' => $new['title'],
		            	'thumb' => $new['thumb'],
		            	'news_id' => $new['news_id'],
		            	'create_time' => $new['create_time'],
		            	'status' => 1, 
		            );
		            $res = D("PositionContent")->getPositionById($data['news_id'],$data['position_id']);
                    if($res){
                       return show(0,'该文章已推荐');
                    }
		            $position = D("PositionContent")->insert($data);

				}

        } catch (Exception $e) {
        	
        	return show(0,$e->getMessage());
        }

        return show(1,'推荐成功',array('jump_url' => $jumpUrl));
		

	}

}

 ?>	