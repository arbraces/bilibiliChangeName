<?php
include "index.html";
//伪造头信息
header("Content-Type:text/html; charset=utf-8");
ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)');
if(!empty($_GET['url']) && !empty($_GET['videoUrl'])){
    $url = $_GET['url'];
    if(is_dir($url)){
        //把爬取过来的网页存入到html.txt
        @file_put_contents("html.txt",file_get_contents("compress.zlib://".$_GET['videoUrl']))?
        :exit('请检查url路径是否正确！');
        $str = file_get_contents("html.txt");

        //Windows不能出现的符号
        $winFilePreg = "@[\\\/:*?\"<>|]@";
        // 匹配目录名的正则
        $preg = '@"part":"(.*?)",@ism';

        //匹配到目录名后循环修改文件目录
        preg_match_all($preg, $str, $dirname);
        foreach($dirname[1] as $index=>$file){
            if(is_dir($url."\\".($index+1))){
                rename($url."\\".($index+1), $url."\\".preg_replace($winFilePreg, "", $file));
                echo "修改成功！";
                echo $file."<hr/>";
            }else{
                echo $url."\\".$index;
                exit('目录修改失败！');
            }
        }
        //修改路径名
        $titlePreg = '@title="(.*?)"@';
        preg_match($titlePreg, $str, $title);
        rename($url, dirname($url)."\\".preg_replace($winFilePreg, "", $title[1]))?
        :exit('文件名修改失败!');
    }else{
        exit('请提交目录');
    }

}else{
    echo "你还没有提交POST请求！";
}


?>
