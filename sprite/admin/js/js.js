function del($id) {
	var msg = "您真的确定要删除吗？";
	if (confirm(msg)==true){
		window.location = "/admin/product.del?cid="+$id;
	}else{
		return false;
	} 
} 