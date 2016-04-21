<?php
class core_template {
	
	var $subtemplates = array();
	var $replacecode = array('search' => array(), 'replace' => array());
	
    public function parse($route, $viewfile, $cachefile) {
    	$fp = @fopen($viewfile, 'r');
		$template = @fread($fp, filesize($viewfile));
		fclose($fp);
		$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\-\>)?[a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
		$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";
		$headerexists = preg_match("/{(sub)?template\s+[\w\/]+?header\}/", $template);
		$this->subtemplates = array();
		for($i = 1; $i <= 3; $i++) {
			if($this->strexists($template, '{subtemplate')) {
				$template = preg_replace("/[\n\r\t]*(\<\!\-\-)?\{subtemplate\s+([a-z0-9_:\/]+)\}(\-\-\>)?[\n\r\t]*/ies", "\$this->loadsubtemplate('\\2')", $template);
			}
		}
		$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
		$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
		$template = preg_replace("/\{lang\s+(.+?)\}/ies", "\$this->language('\\1')", $template);
		$template = preg_replace("/\{G\s+(.+?)\}/ies", "\$this->config('\\1')", $template);
		$template = preg_replace("/[\n\r\t]*\{eval\}\s*(\<\!\-\-)*(.+?)(\-\-\>)*\s*\{\/eval\}[\n\r\t]*/ies", "\$this->evaltags('\\2')", $template);
		$template = preg_replace("/[\n\r\t]*\{eval\s+(.+?)\s*\}[\n\r\t]*/ies", "\$this->evaltags('\\1')", $template);
		$template = str_replace("{LF}", "<?=\"\\n\"?>", $template);
		$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\-\>\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
		$template = preg_replace("/$var_regexp/es", "core_template::addquote('<?=\\1?>')", $template);
		$template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "\$this->addquote('<?=\\1?>')", $template);
		$template = "<?php if(!defined('SP')) exit();?>\n$template";
		$template = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_:\/]+)\}[\n\r\t]*/ies", "\$this->stripvtags('<? include template(\'\\1\'); ?>')", $template);
		$template = preg_replace("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/ies", "\$this->stripvtags('<? include template(\'\\1\'); ?>')", $template);
		$template = preg_replace("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "\$this->stripvtags('<? echo \\1; ?>')", $template);
		$template = preg_replace("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r\t]*)/ies", "\$this->stripvtags('\\1<? if(\\2) { ?>\\3')", $template);
		$template = preg_replace("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", "\$this->stripvtags('\\1<? } elseif(\\2) { ?>\\3')", $template);
		$template = preg_replace("/\{else\}/i", "<? } else { ?>", $template);
		$template = preg_replace("/\{\/if\}/i", "<? } ?>", $template);
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r\t]*/ies", "\$this->stripvtags('<? if(is_array(\\1)) foreach(\\1 as \\2) { ?>')", $template);
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*/ies", "\$this->stripvtags('<? if(is_array(\\1)) foreach(\\1 as \\2 => \\3) { ?>')", $template);
		$template = preg_replace("/\{\/loop\}/i", "<? } ?>", $template);
		$template = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $template);
		if(!empty($this->replacecode)) {
			$template = str_replace($this->replacecode['search'], $this->replacecode['replace'], $template);
		}
		$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);
		if(!@$fp = fopen($cachefile, 'w')) {
			$this->error('directory_notfound', dirname($cachefile));
		}
		$template = preg_replace("/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/e", "\$this->transamp('\\0')", $template);
		$template = preg_replace("/\<script[^\>]*?src=\"(.+?)\"(.*?)\>\s*\<\/script\>/ies", "\$this->stripscriptamp('\\1', '\\2')", $template);
		$template = preg_replace("/\<\?(\s{1})/is", "<?php\\1", $template);
		$template = preg_replace("/\<\?\=(.+?)\?\>/is", "<?php echo \\1;?>", $template);
		flock($fp, 2);
		fwrite($fp, $template);
		fclose($fp);
    }
	function loadsubtemplate($file) {
		$filename = C('view')->getview($file);
		if($content = @implode('', file($filename))) {
			$this->subtemplates[] = $filename;
			return $content;
		} else {
			return '<!-- '.$file.' -->';
		}
	}
	function transamp($str) {
		$str = str_replace('&', '&amp;', $str);
		$str = str_replace('&amp;amp;', '&amp;', $str);
		$str = str_replace('\"', '"', $str);
		return $str;
	}
	function stripscriptamp($s, $extra) {
		$extra = str_replace('\\"', '"', $extra);
		$s = str_replace('&amp;', '&', $s);
		return "<script src=\"$s\" type=\"text/javascript\"$extra></script>";
	}
	function stripvtags($expr, $statement = '') {
		$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
		$statement = str_replace("\\\"", "\"", $statement);
		return $expr.$statement;
	}
	function evaltags($php) {
		$php = str_replace('\"', '"', $php);
		$i = count($this->replacecode['search']);
		$this->replacecode['search'][$i] = $search = "<!--EVAL_TAG_$i-->";
		$this->replacecode['replace'][$i] = "<? $php?>";
		return $search;
	}
	function language($var) {
		return L($var);
	}
	function config($var) {
		return G($var);
	}
	function addquote($var) {
		return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	}
	function error($message, $tplname) {
		trigger_error($message, E_USER_ERROR);
	}
	function strexists($string, $find) {
		return !(strpos($string, $find) === FALSE);
	}
}