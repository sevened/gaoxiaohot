<?php
class model_category extends model{
	protected $_table = 'category';
    protected $_fields = array('cid','category_name','img','add_time','status');
    protected $_id = 'cid';	
	protected static $_data = array();
}