<?php namespace xlx;
class PHPApi{ 
    public $title="PHPAPI";
    public $paths=[]; 
    public $exts=['*.php'];
    public $define=[];
	function dirs($dir,&$arr){
		if(!is_dir($dir)) 
			return $arr[] = $dir;   
		$dirs = glob($dir.'/*',GLOB_ONLYDIR );
		foreach ($this->exts as $ext){
			$dirs = array_merge($dirs, glob($dir.'/'.$ext) );
		}    
		foreach ($dirs as $value){ 
			$this->dirs($value,$arr); 
		}  
	} 
    function bulid($doc,&$apis){
        preg_match_all('/[\r\n]@(\S+)\s([\S\s]+?)(?=[\r\n]@|$)/',$doc,$arr,PREG_SET_ORDER);
        foreach ($arr as &$v){ 
            array_shift($v); 
            if($v[0]=='api')$api['url']=$v[1];
            if($v[0]=='apiName')$api['name']=array_filter(preg_split('/\s+/',$v[1]));
            if($v[0]=='apiDesc')$api['desc']=$v[1];
            if($v[0]=='apiUse'){ 
                $this->bulid("\n".$this->define[trim($v[1])],$a); 
                $api = array_merge($api??[],$a[0]); 
            }
            if($v[0]=='reqParam'){
                $a = preg_split('/\s+/',$v[1],3);
                $api['req'][array_shift($a)]=$a; 
            } 
            if($v[0]=='resParam'){
                $a = preg_split('/\s+/',$v[1],3);
                $api['res'][array_shift($a)]=$a; 
            }
            if($v[0]=='resSuccess')
                $api['success'][]= preg_split('/[\r\n]/',$v[1],2);
            if($v[0]=='resError')
                $api['error'][]= preg_split('/[\r\n]/',$v[1],2);
        }
        if(!empty($api))
            $apis[]=$api; 
    }
    public function gen($return=false){ 
        $apis=[];
        foreach ($this->paths as $path)  
            $this->dirs($path,$files); 
        foreach ($files as $value){
            $txt=file_get_contents($value);
            preg_match_all('/\/\*[\S\s]+?[\S\s]+?\*\//',$txt,$arr);
            foreach ($arr[0] as $txt) {
                if(strstr($txt,'@api')){
                    $txt = substr($txt,3,strlen($txt)-5); 
                    $txt = preg_replace('/([\r\n])\s+\*\s/',"\n",$txt);
                    $this->bulid($txt,$apis);  
                }
            } 
        } 
        return $return?$apis:$this->view($apis);
    }  
    public function view($apis){ 
        $title=$this->title;
        $tree=[];
        foreach ($apis as  $value) {
            $t = &$tree;
            foreach ($value['name'] as $k) 
                $t = &$t[$k]; 
            $t=join($value['name'],'-');
        } 
        include __DIR__.'/../assets/view.php';
    }
}  