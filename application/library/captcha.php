<?php
class captcha{
	private $_code;
	private $font;
	public function __construct() {
		$this->font = AP.'library/airbus.ttf';
	}
	
	public function verify($length=4, $mode=1, $type='png', $width=48, $height=22) {
    	//清除注册验证信息
        $_SESSION['reg_one'] = 0;
        $_SESSION['captcha'] = $randval = self::randstring($length, $mode);
        $width = ($length * 10 + 10) > $width ? $length * 10 + 10 : $width;

        if ($type != 'gif' && function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($width, $height);
        } else {
            $im = imagecreate($width, $height);
        }
		
        
        $r = Array(225, 255, 255, 223);
        $g = Array(225, 236, 237, 255);
        $b = Array(225, 236, 166, 125);
        $key = mt_rand(0, 3);
        $backColor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]);
        $borderColor = imagecolorallocate($im, 100, 100, 100);
        imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
        $stringColor = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
        for ($i = 0; $i < 10; $i++) {
            imagearc($im, mt_rand(-10, $width), mt_rand(-10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $stringColor);
        }
        for ($i = 0; $i < 25; $i++) {
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $stringColor);
        }
        for ($i = 0; $i < $length; $i++) {
            imagestring($im, 5, $i * 10 + 5, mt_rand(1, 8), $randval{$i}, $stringColor);
        }
        self::output($im, $type);
    }
    
	public function img($im_x='100', $im_y='35', $size='22') {
		$im = imagecreatetruecolor($im_x, $im_y);
		$text_c = ImageColorAllocate($im, mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
		$buttum_c = ImageColorAllocate($im,255,255,255);
		imagefill($im, 16, 13, $buttum_c);
		$text = $this->_code = $this->make_rand('4');
		for ($i=0;$i<strlen($text);$i++){
			$tmp =substr($text,$i,1);
			$array = array(-1,1);
			$p = array_rand($array);
			$an = $array[$p]*mt_rand(1,10);//角度
			imagettftext($im, $size, $an, 1+$i*$size, 28, $text_c, $this->font, $tmp);
		}
		$distortion_im = imagecreatetruecolor ($im_x, $im_y);
		imagefill($distortion_im, 16, 13, $buttum_c);
		for ( $i=0; $i<$im_x; $i++) {
			for ( $j=0; $j<$im_y; $j++) {
				$rgb = imagecolorat($im, $i , $j);
				if( (int)($i+20+sin($j/$im_y*2*M_PI)*10) <= imagesx($distortion_im)&& (int)($i+20+sin($j/$im_y*2*M_PI)*10) >=0 ) {
					imagesetpixel ($distortion_im, (int)($i+10+sin($j/$im_y*2*M_PI-M_PI*0.1)*4) , $j , $rgb);
				}
			}
		}
		$rand = mt_rand(5,30);
		$rand1 = mt_rand(15,25);
		$rand2 = mt_rand(5,10);
		for ($yy=$rand; $yy<=+$rand+2; $yy++){
			for ($px=-80;$px<=80;$px=$px+0.1){
				$x=$px/$rand1;
				if ($x!=0){
					$y=sin($x);
				}
				$py=$y*$rand2;
				imagesetpixel($distortion_im, $px+80, $py+$yy, $text_c);
			}
		}
		Header("Content-type: image/JPEG");
		ImagePNG($distortion_im);
		ImageDestroy($distortion_im);
		ImageDestroy($im);
	}
    
	public function code() {
		return strtolower($this->_code);
	}
    public function output($im, $type='png', $filename='') {
        header("Content-type: image/" . $type);
        $ImageFun = 'image' . $type;
        if (empty($filename)) {
            $ImageFun($im);
        } else {
            $ImageFun($im, $filename);
        }
        imagedestroy($im);
    }
	static public function randstring($len=6,$type='',$addchars='') {
        $str ='';
        switch($type) {
            case 0:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addchars;
                break;
            case 1:
                $chars= str_repeat('0123456789',3);
                break;
            case 2:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addchars;
                break;
            case 3:
                $chars='abcdefghijklmnopqrstuvwxyz'.$addchars;
                break;
            default :
                $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addchars;
                break;
        }
        if($len>10 ) {
            $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
        }
        $chars   =   str_shuffle($chars);
        $str     =   substr($chars,0,$len);
        return $str;
    }
	private function make_rand($length="4"){
		$str="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$result="";
		for($i=0;$i<$length;$i++){
			$num[$i]=rand(0,25);
			$result.=$str[$num[$i]];
		}
		return $result;
	}
}