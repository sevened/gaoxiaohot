<?php
class c_category extends action{
	
	//类型
	function a_category(){
		$fuhao='?';
		$num=10;
		$url='/admin/category.category';
		$page = I('G.page', 1);
		$bnum=($page-1)*$num;
		$totalprolist = D("category")->getList();
		$total=count($totalprolist);
		$pages = H("page")->newpage($total, $num, $page, $url,$fuhao);
		$sql = "SELECT * FROM `a_category` ORDER BY `add_time` DESC limit ".($page-1)*$num.",".$num;
		$prolist = D('product')->getAll($sql);
		$data = array(
			'total' =>$total,
			'pages' => $pages,
			'prolist' => $prolist
		);
		$this->assign($data);
		$this->display('admin/category_list');
	}
	//添加类型
	function a_addcategory(){
		$post = I("P.");
		if($post['form_t']=='add' && $post['submit']){//添加图片提交操作
			$category='category';
			$file_arr = $this->fileUpload($category);
			if(!$post['name']) err("请填写类型");
			if(!$file_arr['file_name']) err("请选择图片");
			$arr = array(
				'category_name' => $post['name'],
				'status' => $post['staust'],
				'img' => $file_arr['file_path'],
				'add_time' => time()
			);
			if(D('category')->add($arr)){
				suc("添加成功","/admin/category.category");
			}else{
				err("添加失败","/admin/category.category");
			}
		}elseif(I("G.cid")>0 && !$post['form_t']){
			$cid = trim(I("G.cid"));
			$category = D("category")->queryOne('*', array(array('cid', $cid)));
			$this->assign("category", $category);
			$this->assign("cid", $cid);
			$this->assign('form_t','edit');
			$this->display('admin/category_form');
		}elseif($post['cid']>0 && $post['form_t']=='edit' && $post['submit']){
			$category='category';
			$file_arr = $this->fileUpload($category);
			$cid = trim($post['cid']);
			if(!$post['name']) err("请填写类型");
			if(!$file_arr['file_name']){
				$update = array(
					'cid' => $cid,
					'category_name' => $post['name'],
					'status' => $post['status'],
	
				);
			}else{
				$res = D("category")->queryOne('img', array(array('cid', $cid)));
				$update = array(
					'cid' => $cid,
					'category_name' => $post['name'],
					'status' => $post['status'],
					'img' => $file_arr['file_path'],
	
				);
			}
			
			if(D('category')->edit($update)){
				if($file_arr['file_name']){
						$file_dir = ROOT.$res['file_path'];
						if(file_exists($file_dir)){
							unlink($file_dir);
						}
				}
				suc("修改成功","admin/category.category");
			}else{
				err("修改失败","admin/category.category");
			}
		}else{
			$this->assign('form_t','add');
			$this->display('admin/category_form');
		}
	}
	
	function a_del(){
		$cid = trim(I("G.cid"));
		$res = D("category")->queryOne('img', array(array('cid', $cid)));
		if(D('category')->del($cid)){
				$file_dir = ROOT.$res['img'];
				if(file_exists($file_dir)){
					unlink($file_dir);
				}
				suc("删除成功","admin/category.category");
			}else{
				err("删除失败","admin/category.category");
			}
	}
	
	function fileUpload($category){
		if ($_FILES["file"]["error"] > 0){
	  		return array('file_name'=>0);
	  	}
	  	$filename = $_FILES["file"]["name"];
		$arr = explode('.',$filename);
		$ex = end($arr);
		$datename = time();
		$date_dir = date('Ymd');
		$filedir = "upload/".$category.'/'.$date_dir;
        if (!is_dir($filedir)) {
            if (!mkdir($filedir, 0777, true)) {
                return false;
            }
            @chmod($filedir, 0777);
        }
		$file_dir = $filedir.'/'.$datename.'.'.$ex;
		move_uploaded_file($_FILES["file"]["tmp_name"],ROOT.$file_dir);
		return array('file_name'=>$filename,'file_path'=>$file_dir);
	}

}