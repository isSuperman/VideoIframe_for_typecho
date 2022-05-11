# VideoIframe_for_typecho

![](https://github.com/isSuperman/VideoIframe_for_typecho/raw/main/assets/logo.jpg)

## 介绍
一款加强typecho挂在视频插件，理论上适应全部主题

## 插件优势

 1. 抖音直接粘贴复制的所有文本即可，自动解析网址
 2. 哔哩哔哩直接粘贴复制的iframe代码，自动替换为720p高清
 3. 其他视频自动解析，复制视频地址
 4. 加入了自动检测更新功能，以便及时体验最新功能


## 安装教程
 1. 下载插件后，上传到`usr/plugins/`，解压后将文件夹改名为VideoIframe
 2. 启用插件
 3. 填写对应的云视频解析api
 4. 撰写文章时，点击插入iframe视频按钮，填写对应的视频网址即可

## 使用说明
- 启用插件后，填写相应的云视频解析api
- 在撰写文章的编辑器中，找到对应图标，点击弹出对话框
- 目前针对抖音和哔哩哔哩做了优化，抖音在app中复制链接，哔哩哔哩复制iframe代码，均无需删除文本，直接粘贴即可
- 其他类型视频粘贴网址，附带网络协议

## 插件截图
### 设置界面
![设置界面](https://github.com/isSuperman/VideoIframe_for_typecho/raw/main/assets/iframesetting.png)

### 插入按钮
> 1.0.3 样式
![插入按钮](https://github.com/isSuperman/VideoIframe_for_typecho/raw/main/assets/iframebtn2.png)

> 1.0.1-1.0.2 样式
![插入按钮](https://github.com/isSuperman/VideoIframe_for_typecho/raw/main/assets/iframebtn.png)

### 使用界面
![使用界面](https://github.com/isSuperman/VideoIframe_for_typecho/raw/main/assets/iframepanel.png)

## 更新计划
- [x] 插件化
- [x] 进一步优化代码，使用短代码代替
- [x] 适配Vditor 
- [ ] 其他

## 目前存在的问题
Vditor编辑器已适配，前台解析尚未适配

## 感谢
1. 编辑器扩展采用cmsblog.cn博主的代码
2. 短代码采用[小さな手は](https://www.littlehands.site/)的ShortCode

## 参与贡献

1.  Fork 本仓库
2.  新建 Feat_xxx 分支
3.  提交代码
4.  新建 Pull Request