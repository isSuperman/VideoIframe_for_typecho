<?php

use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Text;
use Widget\Options;


if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * <strong style="color:#28B7FF;font-family: 楷体;">在文章页插入iframe视频，自动解析，支持多平台</strong>
 *<div class="videoiframe"><a style="width:fit-content" id="videoiframe">版本检测中..</div>&nbsp;</div><style>.videoiframe {    margin-top: 5px;}.videoiframe a {    background: #00BFFF;    padding: 5px;    color: #fff;}</style>
 * <script>var videoiframe="1.0.1";function update_detec(){var container=document.getElementById("videoiframe");if(!container){return}var ajax=new XMLHttpRequest();container.style.display="block";ajax.open("get","https://api.github.com/repos/isSuperman/VideoIframe_for_typecho/releases/latest");ajax.send();ajax.onreadystatechange=function(){if(ajax.readyState===4&&ajax.status===200){var obj=JSON.parse(ajax.responseText);var newest=obj.tag_name;if(newest>videoiframe){container.innerHTML="发现新主题版本："+obj.name+'。下载地址：<a href="'+obj.zipball_url+'">点击下载</a>'+"<br>当前版本:"+String(videoiframe)+'<a target="_blank" href="'+obj.html_url+'">👉查看新版亮点</a>'}else{container.innerHTML="当前版本:"+String(videoiframe)+"。"+"最新版"}}}};update_detec();</script>		
 * @package VideoIframe
 * @author <strong style="color:#28B7FF;font-family: 楷体;">isSuperman</strong>
 * @version 1.0.1
 * @link https://github.com/isSuperman/VideoIframe_for_typecho
 */
class VideoIframe_Plugin implements PluginInterface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     */
    public static function activate()
    {
		Typecho_Plugin::factory('admin/common.php')->begin = [__Class__, 'parseShortCode'];
		Typecho_Plugin::factory('Widget_Archive')->handleInit = [__Class__, 'parseShortCode'];
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('admin/write-post.php')->bottom = array(__CLASS__, 'insertIframe');
        Typecho_Plugin::factory('admin/write-page.php')->bottom = array(__CLASS__, 'insertIframe');

    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     */
    public static function deactivate()
    {
    }

    /**
     * 获取插件配置面板
     *
     * @param Form $form 配置面板
     */
    public static function config(Form $form)
    {
        /** 抖音解析API */
        $dyapi = new Text('dyapi', null, 'http://example.com/?url=', _t('抖音解析API'));
        $form->addInput($dyapi);
        /** 抖音解析API */
        $videoapi = new Text('videoapi', null, 'http://example.com/?url=', _t('其他视频云解析API'));
        $form->addInput($videoapi);
    }

    /**
     * 个人用户的配置面板
     *
     * @param Form $form
     */
    public static function personalConfig(Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render(){}

    /**
     *为header添加css文件
     *@access public
     *@return void
     */
    public static function header() {
        $cssUrl = Helper::options() -> rootUrl . '/usr/plugins/VideoIframe/static/css/style.css';
        echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />';
    }

    /**
     *为header添加css文件
     *@access public
     *@return void
    **/
    
    public static function insertIframe()
    {
        echo "<script src='" . Helper::options() -> rootUrl . '/usr/plugins/VideoIframe/static/js/extend.js' . "'></script>";
        echo "<script src='" . Helper::options() -> rootUrl . '/usr/plugins/VideoIframe/static/js/edit.js' . "'></script>";
    }

    /**
     *短代码解析
     *@access public
     *@return void
    **/
    public static function parseShortCode(){
		require_once 'ShortCode.php';
		ShortCode::set('videoiframe',function($name,$attr,$text,$code){
			// name 短代码名称
            // attr 短代码属性
            // text 短代码内容
            // code 整条短代码内容
            $host = explode('.',$text, -1)[1];

            switch ($host){
                case "douyin":
                    return '<iframe title="iframe" src="'. Helper::options()->plugin('VideoIframe')->dyapi . $text . '" frameborder="no"  framespacing="0" border="0" scrolling="no" allowfullscreen="true" class="iframe_video"></iframe>';
                case "bilibili":
                    return '<iframe title="iframe" src="' . $text . '&as_wide=1&high_quality=1&danmaku=0" frameborder="no" framespacing="0" border="0" scrolling="no" allowfullscreen="true" class="iframe_video"></iframe>';
                default:
                    return '<iframe title="iframe" src="' . Helper::options()->plugin('VideoIframe')->videoapi . $text . '" frameborder="no"  framespacing="0" border="0" scrolling="no" allowfullscreen="true" class="iframe_video"></iframe>';
            }
		});
	}

}