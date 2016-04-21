<?php
class c_product extends action{
	function a_do(){
		$fuhao='?';
		$num=10;
		$url='/admin/product.do';
		$page = I('G.page', 1);
		$bnum=($page-1)*$num;
		$totalprolist = D("product")->getList();
		$total=count($totalprolist);
		$pages = H("page")->newpage($total, $num, $page, $url,$fuhao);
		$sql = "SELECT acl.*,ac.category_name FROM a_classify as acl left join a_category as ac on acl.type=ac.cid WHERE 1=1 ORDER BY acl.add_time DESC limit ".($page-1)*$num.",".$num;
		$prolist = D('product')->getAll($sql);
		$data = array(
			'total' =>$total,
			'pages' => $pages,
			'prolist' => $prolist,
			'calist' => $calist
		);
		$this->assign($data);
		$this->display('admin/product_list');
	}
	
	function a_form(){
		$post = I("P.");
		if($post['form_t']=='add' && $post['submit']){//添加图片提交操作
			$file_arr = $this->fileUpload();
			if(!$post['name']) err("请填写标题");
			if(!$post['description']) err("请填写描述");
			if(!$post['type']) err("请选择类型");
			if(!$post['video_link']) err("请填写视频地址");
			if(!$file_arr['file_name']) err("请选择图片");
			$arr = array(
				'name' => $post['name'],
				'file_name' => $file_arr['file_name'],
				'type' => $post['type'],
				'description' => $post['description'],
				'file_path' => $file_arr['file_path'],
				'video_link' => $post['video_link'],
				'add_time' => time()
			);
			if(D('product')->add($arr)){
				suc("添加成功","/admin/product.do");
			}else{
				err("添加失败","/admin/product.do");
			}
		}elseif(I("G.cid")>0 && !$post['form_t']){
			$cid = trim(I("G.cid"));
			$product = D("product")->queryOne('*', array(array('cid', $cid)));
			$calist = D('category')->getList();
			$this->assign('calist',$calist);
			$this->assign("product", $product);
			$this->assign("cid", $cid);
			$this->assign('form_t','edit');
			$this->display('admin/product_form');
		}elseif($post['cid']>0 && $post['form_t']=='edit' && $post['submit']){
			$file_arr = $this->fileUpload();
			$cid = trim($post['cid']);
			if(!$post['name']) err("请填写标题");
			if(!$post['description']) err("请填写描述");
			if(!$post['type']) err("请选择类型");
			if(!$post['video_link']) err("请填写视频地址");
			if(!$file_arr['file_name']){
				$update = array(
				'cid' => $cid,
				'name' => $post['name'],
				'description' => $post['description'],
				'type' => $post['type'],
				'video_link' => $post['video_link'],
				'add_time' => time()
			);
			}else{
				$res = D("product")->queryOne('file_path', array(array('cid', $cid)));
				$update = array(
					'cid' => $cid,
					'name' => $post['name'],
					'description' => $post['description'],
					'type' => $post['type'],
					'file_name' => $file_arr['file_name'],
					'file_path' => $file_arr['file_path'],
					'video_link' => $post['video_link'],
					'add_time' => time()
				);
			}
			
			if(D('product')->edit($update)){
				if($file_arr['file_name']){
						$file_dir = ROOT.$res['file_path'];
						if(file_exists($file_dir)){
							unlink($file_dir);
						}
				}
				suc("修改成功","admin/product.do");
			}else{
				err("修改失败","admin/product.do");
			}
		}else{
			$calist = D('category')->getList();
			$this->assign('calist',$calist);
			$this->assign('form_t','add');
			$this->display('admin/product_form');
		}
	}
	
	function a_del(){
		$cid = trim(I("G.cid"));
		$res = D("product")->queryOne('file_path', array(array('cid', $cid)));
		if(D('product')->del($cid)){
				$file_dir = ROOT.$res['file_path'];
				if(file_exists($file_dir)){
					unlink($file_dir);
				}
				suc("删除成功","admin/product.do");
			}else{
				err("删除失败","admin/product.do");
			}
	}
	
	function fileUpload(){
		if ($_FILES["file"]["error"] > 0){
	  		return array('file_name'=>0);
	  	}
	  	$filename = $_FILES["file"]["name"];
		$arr = explode('.',$filename);
		$ex = end($arr);
		$datename = time();
		$date_dir = date('Ymd');
		$filedir = "upload/".$date_dir;
		!is_dir($filedir)?mkdir($filedir):null;
		$file_dir = $filedir.'/' .$datename.'.'.$ex;
		move_uploaded_file($_FILES["file"]["tmp_name"],ROOT.$file_dir);
		return array('file_name'=>$filename,'file_path'=>$file_dir);
	}

}