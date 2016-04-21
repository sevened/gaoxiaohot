<?php
class question_page{

	function set($total, $num, $page, $url, $type, $prev='《上一页', $next='下一页》') {
		$page = $page ? $page : 1;
		if($num>0){
			$tal = ceil($total/$num);
			if($tal<=1) return;
			$sep = (preg_match('/\?/',$url)) ? '&' : '?';
			$url = $url.$sep;
			$ret = '';
			if ($page>1){
				$ret.= '<div class="prev_page tool"><a href="'.$url.'page='.($page-1).'">'.$prev.'</a></div>';
			}else{
				$ret.= '<div class="prev_page tool"></div>';
			}
			if($type=='radio'){
				$ret .= '<div class="keep_question tool"><a href="javascript:;" id="radio_keep">继续答题</a></div>';
			}elseif($type=='checkbox'){
				$ret .= '<div class="keep_question tool"><a href="javascript:;" id="checkbox_keep">继续答题</a></div>';
			}elseif($type=='textarea'){
				$ret .= '<div class="keep_question tool"><a href="javascript:;" id="textarea_keep">继续答题</a></div>';
			}
			
			if ($page<$tal){
				$ret.= '<div class="next_page tool"><a href="'.$url.'page='.($page+1).'">'.$next.'</a></div>';
			}else{
				$ret.= '<div class="next_page tool"></div>';
			}
			return $ret;
		}
	}
}