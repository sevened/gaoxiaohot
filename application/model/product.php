<?php
class model_product extends model{
	protected $_table = 'classify';
    protected $_fields = array('cid','name','file_name','type','description','file_path','video_link','add_time');
    protected $_id = 'cid';	
	protected static $_data = array();
}