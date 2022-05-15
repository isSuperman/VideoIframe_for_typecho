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
 * <script>var videoiframe="1.0.4";function update_detec(){var container=document.getElementById("videoiframe");if(!container){return}var ajax=new XMLHttpRequest();container.style.display="block";ajax.open("get","https://api.github.com/repos/isSuperman/VideoIframe_for_typecho/releases/latest");ajax.send();ajax.onreadystatechange=function(){if(ajax.readyState===4&&ajax.status===200){var obj=JSON.parse(ajax.responseText);var newest=obj.tag_name;if(newest>videoiframe){container.innerHTML="发现新主题版本："+obj.name+'。下载地址：<a href="'+obj.zipball_url+'">点击下载</a>'+"<br>当前版本:"+String(videoiframe)+'<a target="_blank" href="'+obj.html_url+'">👉查看新版亮点</a>'}else{container.innerHTML="当前版本:"+String(videoiframe)+"。"+"最新版"}}}};update_detec();</script>		
 * @package VideoIframe
 * @author <strong style="color:#28B7FF;font-family: 楷体;">isSuperman</strong>
 * @version 1.0.4
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
        // $cssUrl = Helper::options() -> rootUrl . '/usr/plugins/VideoIframe/static/css/style.css';
        // echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />';
        echo '<style>.iframe_video{position:relative;width:100%;}@media only screen and (max-width:767px){.iframe_video{height:15em;}}@media only screen and (min-width:768px) and (max-width:991px){.iframe_video{height:20em;}}@media only screen and (min-width:992px) and (max-width:1199px){.iframe_video{height:30em;}}@media only screen and (min-width:1200px){.iframe_video{height:40em;}}.iframe_cross{position:relative;width:100%;height:0;padding-bottom:75%}.iframe_cross iframe{position:absolute;width:100%;height:100%;left:0;top:0}</style>';
    }

    /**
     *为header添加css文件
     *@access public
     *@return void
    **/
    
    public static function insertIframe()
    {
        // echo "<script src='" . Helper::options() -> rootUrl . '/usr/plugins/VideoIframe/static/js/extend.js' . "'></script>";
        echo <<<EOF
        <script>
        (function ($) {
            $.fn.extend({
                /* 按键盘实现插入内容 */
                shortcuts: function () {
                    this.keydown(function (e) {
                        var _this = $(this);
                        e.stopPropagation();
                        if (e.altKey) {
                            switch (e.keyCode) {
                                case 67:
                                    _this.insertContent('[code]' + _this.selectionRange() + '[/code]');
                                    break;
                            }
                        }
                    });
                },
                /* 插入内容 */
                insertContent: function (myValue, t) {
                    var $t = $(this)[0];
                    if (document.selection) {
                        this.focus();
                        var sel = document.selection.createRange();
                        sel.text = myValue;
                        this.focus();
                        sel.moveStart('character', -l);
                        var wee = sel.text.length;
                        if (arguments.length == 2) {
                            var l = $t.value.length;
                            sel.moveEnd('character', wee + t);
                            t <= 0 ? sel.moveStart('character', wee - 2 * t - myValue.length) : sel.moveStart('character', wee - t - myValue.length);
                            sel.select();
                        }
                    } else if ($t.selectionStart || $t.selectionStart == '0') {
                        var startPos = $t.selectionStart;
                        var endPos = $t.selectionEnd;
                        var scrollTop = $t.scrollTop;
                        $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                        this.focus();
                        $t.selectionStart = startPos + myValue.length;
                        $t.selectionEnd = startPos + myValue.length;
                        $t.scrollTop = scrollTop;
                        if (arguments.length == 2) {
                            $t.setSelectionRange(startPos - t, $t.selectionEnd + t);
                            this.focus();
                        }
                    } else {
                        this.value += myValue;
                        this.focus();
                    }
                },
                /* 选择 */
                selectionRange: function (start, end) {
                    var str = '';
                    var thisSrc = this[0];
                    if (start === undefined) {
                        if (/input|textarea/i.test(thisSrc.tagName) && /firefox/i.test(navigator.userAgent)) str = thisSrc.value.substring(thisSrc.selectionStart, thisSrc.selectionEnd);
                        else if (document.selection) str = document.selection.createRange().text;
                        else str = document.getSelection().toString();
                    } else {
                        if (!/input|textarea/.test(thisSrc.tagName.toLowerCase())) return false;
                        end === undefined && (end = start);
                        if (thisSrc.setSelectionRange) {
                            thisSrc.setSelectionRange(start, end);
                            this.focus();
                        } else {
                            var range = thisSrc.createTextRange();
                            range.move('character', start);
                            range.moveEnd('character', end - start);
                            range.select();
                        }
                    }
                    if (start === undefined) return str;
                    else return this;
                }
            });
        })(jQuery);
        </script>
        EOF;
        echo <<<EOF
        <script>
        /* 增加自定义功能 */
        const items = [
        {
            title: '插入iframe视频',
            id: 'wmd-iframe-button',
            svg: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
        }
        ];

        items.forEach(_ => {
            let btn = $(`<p><input value="${_.title}" type="button" id="${_.id}" onclick="insertIframeVideo()" /></p>`)
            $('.mono.url-slug').append(btn);
        });

        function insertIframeVideo(){
            $('body').append(
                '<div id="iframePanel">' +
                '<div class="wmd-prompt-background" style="position: fixed; top: 0px; z-index: 1000; opacity: 0.5; height: 100%; left: 0px; width: 100%;"></div>' +
                '<div class="wmd-prompt-dialog">' +
                '<div>' +
                '<p><b>插入iframe视频地址</b></p>' +
                '<p style="color: #ff0012">bilibili粘贴iframe代码、抖音直接粘贴分享的文本</p>' +
                '<p style="color: #ff0012">其他视频地址需要附带网络协议（http/https）</p>' +
                '<p>请在下方的输入框内输入要插入的视频地址</p>' +
                '<p><input type="text" name="iframe_url"></input></p>' +
                '</div>' +
                '<form>' +
                '<button type="button" class="btn btn-s primary" id="iframe_ok">确定</button>' +
                '<button type="button" class="btn btn-s" id="iframe_cancel">取消</button>' +
                '</form>' +
                '</div>' +
                '</div>');
            $('.wmd-prompt-dialog input').val("http://").select();
        }
        $(document).on('click', '#awmd-iframe-button', function(){
            alert('11')
        })

        $(document).on('click', '#iframe_ok', function () {
        var iframe_url_str = $('.wmd-prompt-dialog input[name="iframe_url"]').val()
        var host_pattern = /([0-9a-z.]+)\//
        var host_url = host_pattern.exec(iframe_url_str)[0]
        var host = host_url.split('.').slice(-2).shift()
        
        switch (host) {
            case 'douyin':
            var dy_pattern = /https:\/\/(.*)\//
            var dy_iframe_url = dy_pattern.exec(iframe_url_str)[0]
            $('#text').insertContent('[VideoIframe]'+dy_iframe_url+'[/VideoIframe]');
            $(".vditor-reset").append(`<p data-block="0">[VideoIframe]${dy_iframe_url}[/VideoIframe]</p>`)
            $('#iframePanel').remove();
            $('textarea').focus();
            break;
            case 'bilibili':
            var b_pattern = /\/\/(.*)&page=1/
            var b_iframe_url = b_pattern.exec(iframe_url_str)[0]
            $('#text').insertContent('[VideoIframe]'+b_iframe_url+'[/VideoIframe]');
            $(".vditor-reset").append(`<p data-block="0">[VideoIframe]${b_iframe_url}[/VideoIframe]</p>`)
            $('#iframePanel').remove();
            $('textarea').focus();
            break;
            default:
            var o_pattern = /(https?:\/\/)([0-9a-z.]+)(:[0-9]+)?([/0-9a-z.]+)?(\?[0-9a-z&=]+)?(#[0-9-a-z]+)?/
            var iframe_url = o_pattern.exec(iframe_url_str)[0]
            $('#text').insertContent('[VideoIframe]'+iframe_url+'[/VideoIframe]');
            $(".vditor-reset").append(`<p data-block="0">[VideoIframe]${iframe_url}[/VideoIframe]</p>`)
            $('#iframePanel').remove();
            $('textarea').focus();
        }
        })
        $(document).on('click', '#iframe_cancel', function () {
        $('#iframePanel').remove();
        $('textarea').focus();
        });
        </script>
        EOF;
        // echo "<script src='" . Helper::options() -> rootUrl . '/usr/plugins/VideoIframe/static/js/edit.js' . "'></script>";
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