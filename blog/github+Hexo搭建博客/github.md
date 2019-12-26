## 4.6. 修改主题

既然默认主题很丑，那我们别的不做，首先来替换一个好看点的主题。这是 [官方主题](https://hexo.io/themes/)。

个人比较喜欢的2个主题：[hexo-theme-jekyll](https://github.com/pinggod/hexo-theme-jekyll) 和 [hexo-theme-yilia](https://github.com/litten/hexo-theme-yilia)。

首先下载这个主题：

```
$ cd /f/Workspaces/hexo/
$ git clone https://github.com/litten/hexo-theme-yilia.git themes/yilia
```

下载后的主题都在这里：

![img](http://image.liuxianan.com/201608/20160818_134500_245_0912.png)

修改`_config.yml`中的`theme: landscape`改为`theme: yilia`，然后重新执行`hexo g`来重新生成。

如果出现一些莫名其妙的问题，可以先执行`hexo clean`来清理一下public的内容，然后再来重新生成和发布。

## 4.7. 上传之前

在上传代码到github之前，一定要记得先把你以前所有代码下载下来（虽然github有版本管理，但备份一下总是好的），因为从hexo提交代码时会把你以前的所有代码都删掉。

## 4.8. 上传到github

如果你一切都配置好了，发布上传很容易，一句`hexo d`就搞定，当然关键还是你要把所有东西配置好。

首先，`ssh key`肯定要配置好。

其次，配置`_config.yml`中有关deploy的部分：

正确写法：

```
deploy:
  type: git
  repository: git@github.com:liuxianan/liuxianan.github.io.git
  branch: master
```

错误写法：

```
deploy:
  type: github
  repository: https://github.com/liuxianan/liuxianan.github.io.git
  branch: master
```

后面一种写法是hexo2.x的写法，现在已经不行了，无论是哪种写法，此时直接执行`hexo d`的话一般会报如下错误：

```
Deployer not found: github 或者 Deployer not found: git
```

原因是还需要安装一个插件：

```
npm install hexo-deployer-git --save
```

其它命令不确定，部署这个命令一定要用git bash，否则会提示`Permission denied (publickey).`

打开你的git bash，输入`hexo d`就会将本次有改动的代码全部提交，没有改动的不会：

![img](http://image.liuxianan.com/201608/20160818_140441_769_5024.png)

## 4.9. 保留CNAME、README.md等文件

提交之后网页上一看，发现以前其它代码都没了，此时不要慌，一些非md文件可以把他们放到source文件夹下，这里的所有文件都会原样复制（除了md文件）到public目录的：

![img](http://image.liuxianan.com/201608/20160818_141037_580_8035.png)

由于hexo默认会把所有md文件都转换成html，包括README.md，所有需要每次生成之后、上传之前，手动将README.md复制到public目录，并删除README.html。

## 4.10. 常用hexo命令

常见命令

```
hexo new "postName" #新建文章
hexo new page "pageName" #新建页面
hexo generate #生成静态页面至public目录
hexo server #开启预览访问端口（默认端口4000，'ctrl + c'关闭server）
hexo deploy #部署到GitHub
hexo help  # 查看帮助
hexo version  #查看Hexo的版本
```

缩写：

```
hexo n == hexo new
hexo g == hexo generate
hexo s == hexo server
hexo d == hexo deploy
```

组合命令：

```
hexo s -g #生成并本地预览
hexo d -g #生成并上传
```

## 4.11. _config.yml

这里面都是一些全局配置，每个参数的意思都比较简单明了，所以就不作详细介绍了。

需要特别注意的地方是，冒号后面必须有一个空格，否则可能会出问题。

## 4.12. 写博客

定位到我们的hexo根目录，执行命令：

```
hexo new 'my-first-blog'
```

hexo会帮我们在`_posts`下生成相关md文件：

![img](http://image.liuxianan.com/201608/20160823_183047_352_1475.png)

我们只需要打开这个文件就可以开始写博客了，默认生成如下内容：

![img](http://image.liuxianan.com/201608/20160823_183325_470_9306.png)

当然你也可以直接自己新建md文件，用这个命令的好处是帮我们自动生成了时间。

一般完整格式如下：

```
---
title: postName #文章页面上的显示名称，一般是中文
date: 2013-12-02 15:30:16 #文章生成时间，一般不改，当然也可以任意修改
categories: 默认分类 #分类
tags: [tag1,tag2,tag3] #文章标签，可空，多标签请用格式，注意:后面有个空格
description: 附加一段文章摘要，字数最好在140字以内，会出现在meta的description里面
---

以下是正文
```

那么`hexo new page 'postName'`命令和`hexo new 'postName'`有什么区别呢？

```
hexo new page "my-second-blog"
```

生成如下：

![img](http://image.liuxianan.com/201608/20160823_184852_854_6502.png)

最终部署时生成：`hexo\public\my-second-blog\index.html`，但是它不会作为文章出现在博文目录。

### 4.12.1. 写博客工具

那么用什么工具写博客呢？这个我还没去找，以前自己使用editor.md简单弄了个，大家有好用的hexo写博客工具可以推荐个。

### 4.12.2. 如何让博文列表不显示全部内容

默认情况下，生成的博文目录会显示全部的文章内容，如何设置文章摘要的长度呢？

答案是在合适的位置加上`<!--more-->`即可，例如：

```
# 前言

使用github pages服务搭建博客的好处有：

1. 全是静态文件，访问速度快；
2. 免费方便，不用花一分钱就可以搭建一个自由的个人博客，不需要服务器不需要后台；
3. 可以随意绑定自己的域名，不仔细看的话根本看不出来你的网站是基于github的；

<!--more-->

4. 数据绝对安全，基于github的版本管理，想恢复到哪个历史版本都行；
5. 博客内容可以轻松打包、转移、发布到其它平台；
6. 等等；
```

最终效果：

![img](http://image.liuxianan.com/201608/20160823_184633_653_1893.png)

# 最终效果

可以访问我的git博客来查看效果： http://mygit.me

不过呢，其实这个博客我只是拿来玩一玩的，没打算真的把它当博客，因为我已经有一个自己的博客了，哈哈！正因如此，本文仅限入门学习，关于hexo搭建个人博客的更高级玩法大家可以另找教程。

# 参考

http://www.cnblogs.com/zhcncn/p/4097881.html

http://www.jianshu.com/p/05289a4bc8b2