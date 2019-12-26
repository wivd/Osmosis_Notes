# SSRF概念

​     SSRF(Server-Side Request Forgery ，服务器端请求伪造)是一种由攻击者构造形成由服务器发起请求的一个安全漏洞，SSRF的主要攻击目标为外网无法访问的内部系统。

SSRF（server-Side Request Forgery:服务器端请求伪造）是一种由攻击者构造形成由服务端发起请求的一个安全漏洞。一般情况下，SSRF是要目标网站的内部系统。（因为他是从内部系统访问的，所有可以通过它攻击外网无法访问的内部系统，也就是把目标网站当中间人）

## SSRF漏洞是如何产生的？

SSRF 形成的原因大都是由于服务端提供了从其他服务器应用获取数据的功能，且没有对目标地址做过滤与限制。比如从指定URL地址获取网页文本内容，加载指定地址的图片，文档，等等。

 首先，我们要对目标网站的架构了解，脑子了要有一个架构图。比如 ： A网站，是一个所有人都可以访问的外网网站，B [SSRF漏洞.md](SSRF漏洞.md) 网站是一个他们内部的OA网站。

所以，我们普通用户只可以访问a网站，不能访问b网站。但是我们可以同过a网站做中间人，访问b网站，从而达到攻击b网站需求。

正常用户访问网站的流程是：

输入A网站URL --> 发送请求 --> A服务器接受请求（没有过滤），并处理 -->返回用户响应

【那网站有个请求是`www.baidu,com/xxx.php?image=URL`】

那么产生SSRF漏洞的环节在哪里呢？安全的网站应接收请求后，检测请求的合法性

产生的原因：服务器端的验证并没有对其请求获取图片的参数（image=）做出严格的过滤以及限制，导致A网站可以从其他服务器的获取数据

例如：

`www.baidu.com/xxx.php?image=www.abc.com/1.jpg`

如果我们将`www.abd.com/1.jpg`换为与该服务器相连的内网服务器地址会产生什么效果呢？

如果存在该内网地址就会返回1xx 2xx 之类的状态码，不存在就会其他的状态码

终极简析: SSRF漏洞就是通过篡改获取资源的请求发送给服务器，但是服务器并没有检测这个请求是否合法的，然后服务器以他的身份来访问其他服务器的资源。

## SSRF漏洞的寻找（漏洞常见出没位置）

注：个人觉得所有调外部资源的参数都有可能存在ssrf漏洞

1）分享：通过URL地址分享网页内容

2）转码服务

3）在线翻译

4）图片加载与下载：通过URL地址加载或下载图片

5）图片、文章收藏功能

6）未公开的api实现以及其他调用URL的功能

7）从URL关键字中寻找

share

wap

url

link

src

source

target

u

3g

display

sourceURl

imageURL

domain

...

## SSRF漏洞的验证方法

1）因为SSRF漏洞是让服务器发送请求的安全漏洞，所以我们就可以通过抓包分析发送的请求是否是由服务器的发送的，从而来判断是否存在SSRF漏洞

2）在页面源码中查找访问的资源地址 ，如果该资源地址类型为 www.baidu.com/xxx.php?image=（地址）的就可能存在SSRF漏洞

**ssrf****常见漏洞代码**

- 首先有三个常见的容易造成ssrf漏洞的函数需要注意

  ` fsockopen() `	**函数说明：**fsockopen — 打开一个网络连接或者一个Unix套接字连接

  `  file_get_contents()`   **函数说明：** file_get_contents() 函数把整个文件读入一个字符串中。

 `curl_exec()`					**函数说明：**	curl_exec — 执行一个cURL会话

- 下面是本地搭建环境测试

  ```
  fsockopen()
  <?php
  $host=$_GET['url'];
  $fp = fsockopen("$host", 80, $errno, $errstr, 30);
  if (!$fp) {
    echo "$errstr ($errno)<br />\n";
  } else {
     $out = "GET / HTTP/1.1\r\n";
     $out .= "Host: $host\r\n";
     $out .= "Connection: Close\r\n\r\n";
     fwrite($fp, $out);
     while (!feof($fp)) {
         echo fgets($fp, 128);
    }
  
      fclose($fp);
  
  }
  
  ?>
  ```

  

1. 

- ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59r5rx9oxj31gp0jt0vo.jpg)

后面可结合`bWAPP靶场`中的SSRF来进行练习。**

 

 