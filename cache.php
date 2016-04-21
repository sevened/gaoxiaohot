<?php  
/*  
 * 作者:yifangyou  
   日期:2012-07-21 14:43:00  
   功能:按照多个目录或者多个URL的方式，清除nginx的cache，或者查看nginx cache 缓存  
   要求:nginx + ngx_cache_purge  
 */ 
   //代理服务器的ip  
  $proxyIp="120.26.216.151";  
  //代理服务器端口  
    $proxyPort=80;  
    //代理服务器的缓存目录  
    $cacheDir="/home/proxy_cache/";  
    $proxySites=array(  
    //用户访问的域名 => 被代理的实际网站的域名，若是都是80的话就是一样即可  
     "http://www.imdb.cn"=>"http://www.imdb.cn:52771" 
    );  
//输出文件  
$output="";      
$result=array();  
$filedirs = array();  
//只查看缓存文件，不清除  
if($_POST["view"]){  
         $accessSite=$_POST["accessSite"];  
     $proxySite=$proxySites[$accessSite];  
         $clearUrls=array();  
         $clearUrls=explode ("\n",$_POST["dirs"]);  
         if($$proxySite){  
                 foreach($ds as $d){  
                         $d=str_replace($accessSite, $proxySite,$d);  
                         $clearUrls[]=$d;  
                 }  
         }  
         scan_dir($cacheDir);  
         $cacheurls = array();  
         foreach($filedirs as $filename){  
                if(!is_file($filename)){  
                        continue;  
                }  
                $cacheUrl=getUrlFromCacheFile($filename);  
                                if(count($clearUrls)){  
                                    $cacheurls[]=$cacheUrl;  
                                    continue;  
                                }  
                foreach($clearUrls as $clearUrl){  
                  $clearUrl=str_replace($accessSite, $proxySite,$clearUrl);  
                  $pos=strpos($cacheUrl,$clearUrl);  
                  // echo "$cacheUrl,$clearUrl,$pos<br/>";  
                  //比较http://www.b.com/a/b.jpg和http://www.b.com/a  
                  if($pos===0){  
                     $cacheurls[]=$cacheUrl;  
                     break;  
                   }  
                }  
        }  
 
}else //清除一批URL  
if($_POST["urls"]){  
    $accessSite=$_POST["accessSite"];  
    $proxySite=$proxySites[$accessSite];  
    $output.="<div style='font-size:16px;font-weight:bold'>执行结果  
\n\n";  
    $urls=explode ("\n",$_POST["urls"]);  
    foreach($urls as $url2){  
        $url=trim($url2);  
        $output.="------------------------$url start-----------------------------\n";  
        $pos = strpos($url, $accessSite);  
        if ($pos !== false && $pos==0) {  
            $url=str_replace($accessSite, $proxySite,$url);  
            if(purge($proxyIp,$proxyPort,$url)==0){  
                $result[$url2]=0;  
            }else{  
                $result[$url2]=1;  
            }  
        }else{  
            $output.="skip $url\n";  
            $result[$url2]=-1;  
        }  
        $output.="------------------------$url end -----------------------------\n";  
    }  
    $output.="\n";  
}else//清除某个目录下的所有文件  
if($_POST["dirs"]){  
    $accessSite=$_POST["accessSite"];  
    $proxySite=$proxySites[$accessSite];  
    $clearUrls=array();  
    $clearUrls=explode ("\n",$_POST["dirs"]);  
    if($$proxySite){  
        foreach($ds as $d){  
            $d=str_replace($accessSite, $proxySite,$d);  
            $clearUrls[]=$d;  
        }  
    }  
    scan_dir($cacheDir);  
    $cacheurls = array();  
    foreach($filedirs as $filename){  
        if(!is_file($filename)){  
            continue;  
        }  
        $cacheUrl=getUrlFromCacheFile($filename);  
 
        foreach($clearUrls as $clearUrl){  
         $clearUrl=str_replace($accessSite, $proxySite,$clearUrl);  
            $pos=strpos($cacheUrl,$clearUrl);  
            // echo "$cacheUrl,$clearUrl,$pos<br/>";  
            //比较http://www.b.com/a/b.jpg和http://www.b.com/a  
            if($pos===0){  
                    $cacheurls[]=$cacheUrl;  
                    break;  
            }  
        }  
    }  
    if(count($cacheurls) > 0){  
        $accessSite=$_POST["accessSite"];  
        $proxySite=$proxySites[$accessSite];  
        $output.="<div style='font-size:16px;font-weight:bold'>执行结果  
\n\n";  
        foreach($cacheurls as $url2){  
 
            $url=trim($url2);  
            $output.="------------------------$url start-----------------------------\n";  
            $pos = strpos($url, $accessSite);  
            if(purge($proxyIp,$proxyPort,$url)==0){  
                    $result[$url2]=0;  
            }else{  
                    $result[$url2]=1;  
            }  
            $output.="------------------------$url end -----------------------------\n www.it165.net";  
        }  
        $output.="\n";  
    }else{  
        foreach($clearUrls as $u){  
            $result[$u]=-1;  
        }  
    }  
}  
?>  
 
<!DOCTYPE html>  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<title>刷新squid</title>  
<body>  
<?php  
    if($result){  
        echo "<table border='1'><tr><td>URL</td><td>结果</td></tr>\n";  
        foreach($result as $url=>$isOk){  
            switch($isOk){  
                case 0://成功  
                $r="<font style='color:#90EE90'>成功</font>";  
                break;  
                case 1://成功  
                $r="<font color='red'>失败</font>";  
                break;  
                case -1://跳过  
                $r="<font color='Yellow'>跳过</font>";  
                break;  
            }  
            if($$proxySite){  
                $url=str_replace($proxySite, $accessSite, $url);  
            }  
            echo "<tr><td>$url</td><td>$r</td></tr>\n";  
        }  
        echo "</table>\n";  
    }  
?>  
 
<form action="" method="post">  
<table >  
<tr><td>选择站点：</td></tr>  
<tr><td>  
<select name="accessSite" id="accessSite">  
    <?php  
        foreach($proxySites as $accessSite => $proxySite){  
        $isSelected=$_POST["accessSite"]==$accessSite?"selected":"";  
            echo "<option value='$accessSite' $isSelected>$accessSite</option>\n";  
        }  
    ?>  
</select>  
<script>  
    function view(){  
        location="?accessSite="+document.getElementById("accessSite").value+"&view=1";      
    }      
</script>  
<input type="checkbox" name="view" value="1" <?php echo $_POST["view"]?"checked":"";?>/><label for="view">只查看</label>  
</td></tr>  
<tr><td>输入一组URL(一个一行)：</td></tr>  
<tr><td><textarea name="urls" style="width:1000px;height:200px;"><?php if($_POST["view"])foreach($cacheurls as $cacheurl){echo "$cacheurl\n";}?></textarea></td></tr>  
<tr><td>刷新目录(一个一行)：</td></tr>  
<tr><td><textarea name="dirs" style="width:1000px;height:200px;"></textarea></td></tr>  
<tr><td><input type="submit" value="提交" /></td></tr>  
</table>  
</form>  
<?php  
    echo $output;  
?>  
</body></html>  
<?php  
//清除某个url  
function purge($proxyIp,$proxyPort,$url)  
{  
    global $output;  
    $host = parse_url($url);  
    $host = $host['host'];  
    $purge_url=str_replace("http://".$host,"/purge",$url);  
    if (emptyempty($proxyIp)) {  
        $proxyIp = gethostbyname($host);  
    }  
    if (emptyempty($proxyPort)) {  
        $proxyPort = "80";  
    }  
    $output.="正在从服务器".$proxyIp."更新".$url."\n";  
    $errstr = '';  
    $errno = '';  
    $fp = fsockopen ($proxyIp, $proxyPort, $errno, $errstr, 2);  
    if (!$fp)  
    {  
         $output.="连接失败！";  
         return -1;  
    }  
    else 
    {  
        $out = "GET ".$purge_url." HTTP/1.1\r\n";  
        $out .= "Host:".$host."\r\n";  
        $out .= "Connection: close\r\n\r\n";  
        $output.="***********request start**************\n";  
        $output.=$out;  
        $output.="***********request end **************\n";  
        fputs ($fp, $out);  
        $output.="***********response start**************\n";  
        //是否更新成功  
        $isOk=false;  
        while($out = fgets($fp , 4096)){  
            if(strpos($out,"200 OK\r\n")!==FALSE){  
                //更新成功  
                $isOk=true;  
            }  
            $output.=$out;  
            if($out=="\r\n"){  
                break;  
            }  
        }  
        fclose ($fp);  
        $output.="***********response end **************\n";          
        flush();  
        if($isOk){  
            return 0;  
        }else{  
            return 1;  
        }  
    }  
}  
 
//递归扫描cache目录下所有文件路径  
function scan_dir($dir) {  
        global $filedirs;  
        if (!is_dir($dir)) return false;  
        if ($dh = opendir($dir)) {  
            while (($file = readdir($dh)) !== false) {  
                if ($file[0] == '.') continue;  
                if($file=='swap.state')continue;  
                $fullpath = "$dir/$file";  
                $filedirs[] = $fullpath;  
                if (is_dir($fullpath))   
                    scan_dir($fullpath);   
            }  
            closedir($dh);  
        }  
        return $filedirs;  
}  
//从cache文件中提取真实的URL  
function getUrlFromCacheFile($filename){  
        //cache文件头长度  
        $headerLen=0x1E;  
        $handle = fopen($filename, "rb");  
        if(!$handle){  
            return -1;   
        }  
        //读取文件的前1k字节  
        $contents = fread($handle, 1024);  
        fclose($handle);  
        if(strlen($contents)<=$headerLen){  
            return -2;   
        }  
        //截掉文件头  
        $contents=substr($contents,$headerLen);  
          
        //cache文件的分隔符为\A  
        $pos=strpos($contents, chr(0x0A));  
        if($pos===FALSE){  
            return -3;   
        }  
        //获取分隔符前的字符串  
        $contents="http://".substr($contents,0,$pos);  
        return $contents;  
}  
?> 