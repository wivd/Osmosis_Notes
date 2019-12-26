使用github pages服务搭建博客的好处有：

1. 全是静态文件，访问速度快；
2. 免费方便，不用花一分钱就可以搭建一个自由的个人博客，不需要服务器不需要后台；
3. 可以随意绑定自己的域名，不仔细看的话根本看不出来你的网站是基于github的；
4. 数据绝对安全，基于github的版本管理，想恢复到哪个历史版本都行；
5. 博客内容可以轻松打包、转移、发布到其它平台；
6. 等等；

## 1.1. 准备工作

在开始一切之前，你必须已经：

- 有一个github账号，没有的话去注册一个；
- 安装了node.js、npm，并了解相关基础知识；
- 安装了git for windows（或者其它git客户端）



# 搭建github博客

## 2.1. 创建仓库

新建一个名为`你的用户名.github.io`的仓库，比如说，如果你的github用户名是test，那么你就新建`test.github.io`的仓库（必须是你的用户名，其它名称无效），将来你的网站访问地址就是 http://test.github.io 了，是不是很方便？

由此可见，每一个github账户最多只能创建一个这样可以直接使用域名访问的仓库。

几个注意的地方：

1. 注册的邮箱一定要验证，否则不会成功；
2. 仓库名字必须是：`username.github.io`，其中`username`是你的用户名；
3. 仓库创建成功不会立即生效，需要过一段时间，大概10-30分钟，或者更久，我的等了半个小时才生效；

创建成功后，默认会在你这个仓库里生成一些示例页面，以后你的网站所有代码都是放在这个仓库里啦。

## 2.2. 绑定域名

当然，你不绑定域名肯定也是可以的，就用默认的 `xxx.github.io` 来访问，如果你想更个性一点，想拥有一个属于自己的域名，那也是OK的。

首先你要注册一个域名，域名注册以前总是推荐去`godaddy`，现在觉得其实国内的阿里云也挺不错的，价格也不贵，毕竟是大公司，放心！

绑定域名分2种情况：带www和不带www的。

域名配置最常见有2种方式，CNAME和A记录，CNAME填写域名，A记录填写IP，由于不带www方式只能采用A记录，所以必须先ping一下`你的用户名.github.io`的IP，然后到你的域名DNS设置页，将A记录指向你ping出来的IP，将CNAME指向`你的用户名.github.io`，这样可以保证无论是否添加www都可以访问，如下：

![img](http://image.liuxianan.com/201608/20160823_191336_238_8683.png)

然后到你的github项目根目录新建一个名为CNAME的文件（无后缀），里面填写你的域名，加不加www看你自己喜好，因为经测试：

- 如果你填写的是没有www的，比如 mygit.me，那么无论是访问 http://www.mygit.me 还是 http://mygit.me ，都会自动跳转到 http://mygit.me
- 如果你填写的是带www的，比如 www.mygit.me ，那么无论是访问 http://www.mygit.me 还是 http://mygit.me ，都会自动跳转到 http://www.mygit.me
- 如果你填写的是其它子域名，比如 abc.mygit.me，那么访问 http://abc.mygit.me 没问题，但是访问 http://mygit.me ，不会自动跳转到 http://abc.mygit.me

另外说一句，在你绑定了新域名之后，原来的`你的用户名.github.io`并没有失效，而是会自动跳转到你的新域名。

# 配置SSH key

为什么要配置这个呢？因为你提交代码肯定要拥有你的github权限才可以，但是直接使用用户名和密码太不安全了，所以我们使用ssh key来解决本地和服务器的连接问题。

```
$ cd ~/. ssh #检查本机已存在的ssh密钥
```

如果提示：No such file or directory 说明你是第一次使用git。

```
ssh-keygen -t rsa -C "邮件地址"
```

然后连续3次回车，最终会生成一个文件在用户目录下，打开用户目录，找到`.ssh\id_rsa.pub`文件，记事本打开并复制里面的内容，打开你的github主页，进入个人设置 -> SSH and GPG keys -> New SSH key：

![img](http://image.liuxianan.com/201608/20160818_143914_495_9084.png)

将刚复制的内容粘贴到key那里，title随便填，保存。

## 3.1. 测试是否成功

```
$ ssh -T git@github.com # 注意邮箱地址不用改
```

如果提示`Are you sure you want to continue connecting (yes/no)?`，输入yes，然后会看到：

Hi liuxianan! You've successfully authenticated, but GitHub does not provide shell access.

看到这个信息说明SSH已配置成功！

此时你还需要配置：

```
$ git config --global user.name "liuxianan"// 你的github用户名，非昵称
$ git config --global user.email  "xxx@qq.com"// 填写你的github注册邮箱
```

具体这个配置是干嘛的我没仔细深究。

# 使用hexo写博客

## 4.1. hexo简介

Hexo是一个简单、快速、强大的基于 Github Pages 的博客发布工具，支持Markdown格式，有众多优秀插件和主题。

官网： http://hexo.io
 github: https://github.com/hexojs/hexo

## 4.2. 原理

由于github  pages存放的都是静态文件，博客存放的不只是文章内容，还有文章列表、分类、标签、翻页等动态内容，假如每次写完一篇文章都要手动更新博文目录和相关链接信息，相信谁都会疯掉，所以hexo所做的就是将这些md文件都放在本地，每次写完文章后调用写好的命令来批量完成相关页面的生成，然后再将有改动的页面提交到github。

## 4.3. 注意事项

安装之前先来说几个注意事项：

1. 很多命令既可以用Windows的cmd来完成，也可以使用git bash来完成，但是部分命令会有一些问题，为避免不必要的问题，建议全部使用git bash来执行；
2. hexo不同版本差别比较大，网上很多文章的配置信息都是基于2.x的，所以注意不要被误导；
3. hexo有2种`_config.yml`文件，一个是根目录下的全局的`_config.yml`，一个是各个`theme`下的；

## 4.4. 安装

```
$ npm install -g hexo
```

## 4.5. 初始化

在电脑的某个地方新建一个名为hexo的文件夹（名字可以随便取），比如我的是`F:\Workspaces\hexo`，由于这个文件夹将来就作为你存放代码的地方，所以最好不要随便放。

```
$ cd /f/Workspaces/hexo/
$ hexo init
```

hexo会自动下载一些文件到这个目录，包括node_modules，目录结构如下图：

![img](http://image.liuxianan.com/201608/20160818_115922_773_1148.png)

```
$ hexo g # 生成
$ hexo s # 启动服务
```

执行以上命令之后，hexo就会在public文件夹生成相关html文件，这些文件将来都是要提交到github去的：

![img](http://image.liuxianan.com/201608/20160818_120700_028_2426.png)

`hexo s`是开启本地预览服务，打开浏览器访问 http://localhost:4000 即可看到内容，很多人会碰到浏览器一直在转圈但是就是加载不出来的问题，一般情况下是因为端口占用的缘故，因为4000这个端口太常见了