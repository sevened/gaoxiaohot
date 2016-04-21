<?php
class page{
	//$prev='‹‹', $next='››'
	function set($total, $num, $page, $url, $mod=0, $span='6', $prev='|<', $next='>|') {
		echo $url;die();
		$page = $page ? $page : 1;
		$tal = ceil($total/$num);
		if($tal<=1) return;
		$sep = (preg_match('/\?/',$url)) ? '&' : '?';
		$url = $url.$sep;
		$ret = '';
		if($mod=='1' || $mod=='3') $ret .= '<em>共'.$total.'条</em>';
		if ($page>1) $ret.= ' <a href="'.$url.'page='.($page-1).'" class="prev">'.$prev.'</a> ';
		$ns = '';
		if ($page > $span) $ns.= ' <a href="'.$url.'">1 ...</a> ';
		for($i=$page-$span+1; $i<$page+$span; $i++) {
			if ($i<=0 || $i>$tal) continue;
			if ($page == $i)
				$ns.= ' <strong>'.$i.'</strong> ';
			else
				$ns.= ' <a href="'.$url.'page='.$i.'">'.$i.'</a> ';
		}
		if ($tal-$page>$span-1) $ns.= ' <a href="'.$url.'page='.$tal.'">... '.$tal.'</a> ';
		$ret.= $ns;
		if ($page<$tal) $ret.= ' <a href="'.$url.'page='.($page+1).'" class="nxt">'.$next.'</a> ';
		if($mod=='2' || $mod=='3') $ret .= '<input type="text" name="custompage" class="txt-input" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$url.'page=\'+this.value; doane(event);}">';
		return $ret;
	}
	
	function newpage($total, $num, $page, $url,$fuhao)
	{
		$sqlnum = $total;
		$totalcount = $num;
		$pagesize = $page;
		$echopage = '';
		$route = $url;
		$add_url = '';
		$uppage=$page-1;
		$downpage=$page+1;
		if (is_array($add_url)) {
			$add_url = http_build_query($add_url);
		}

		$add_url .= '&page=';

		if ($totalcount < $sqlnum) {
			$page = 10;
			$offset = 2;
			$realpages = @ceil($sqlnum / $totalcount);
			$pages = $realpages;

			if ($pages < $page) {
				$from = 1;
				$to = $pages;
			}
			else {
				$from = $pagesize - $offset;
				$to = ($from + $page) - 1;

				if ($from < 1) {
					$to = ($pagesize + 1) - $from;
					$from = 1;

					if (($to - $from) < $page) {
						$to = $page;
					}
				}
				else if ($pages < $to) {
					$from = ($pages - $page) + 1;
					$to = $pages;
				}
			}

			$echopage = '<div class="col-sm-6"><div class="dataTables_info" id="DataTables_Table_0_info" role="alert" aria-live="polite" aria-relevant="all">第 ' . $pagesize . '页 总共 ' . $realpages . '页  共 ' . $sqlnum . '条</div></div>';
			$echopage .= '<div class="col-sm-6"><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">';
			$echopage .= '<ul class="pagination">';
			$echopage .= '<li' . ((1 < ($pagesize - $offset)) && ($page < $pages) ? '' : ' class="paginate_button previous ' . (1 >= $pagesize ? 'disabled' : '') . '" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_previous"') . ' ><a '.(1 < $pagesize ? ' href="' . $route .$fuhao.'page='.$uppage.'"' : ' ') .'>上一页</a></li>';
			$i = $from;
			for (; $i <= $to; $i++) {
				$echopage .= ($i == $pagesize ? '<li class="paginate_button active" aria-controls="DataTables_Table_0" tabindex="0"><a>' . $i . '</a></li>' : '<li class="paginate_button" aria-controls="DataTables_Table_0" tabindex="0"><a href="'.$route.$fuhao.'page='.$i.'" >' . $i . '</a></li>');
			}
			$echopage .= '<li class="paginate_button ' . ($pages <= $to ? 'next' : '') ." ". ($pagesize >= $pages ? 'disabled' : '') .'" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_next"><a '.($pagesize < $pages ? 'href="'.$route.$fuhao.'page='.$downpage.'"' : '') .'>下一页</a></li>';
			$echopage .= '</ul></div></div>';
			$echopage .= '<div class="zpage"><div class="data"><ul>';
		}

		return $echopage;
	}
}