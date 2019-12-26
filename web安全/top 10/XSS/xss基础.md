# Xss原理分析

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g88j6a2xo8j30xp0lvgpo.jpg)

## 输出问题导致的js代码的被执行

1.反射型XSS

反射性XSS又称为非持久型XSS，这种攻击方式

攻击方式：攻击者通过电子邮件等方式将包含XSS代码的恶意链接发送给目标用户当目标用户访问该链接时，服务器接受该目标用户的请求并进行处理，然后服务器把带有XSS代码的数据发送给目标用户的浏览器，浏览器解析这段带有XSS代码的恶意脚本后，就会触发XSS漏洞。

2.存储型XSS

存储型XSS又称持久型xss，攻击脚本将被永久地存放在服务器的数据库或文件中，具有很高的隐蔽性。

攻击方式：这种攻击多见于论坛，博客和留言板，攻击者在发帖的过程中，将恶意脚本连同正常信息一起注入帖子的内容中。随着帖子被服务器存储下来，恶意脚本也永久地被存放在服务器的后端存储器中。当其他用户浏览这个被注入了恶意脚本的帖子时，恶意脚本会在他们的浏览器中得到执行。

3.DOM型XSS

DOM 型xss其实是一种特殊类型的反射型xss它是基于DOM文档对象模型的一种漏洞。

攻击方式：用户请求一个经过专门设计的URL，它由攻击者提交，而且其中包含xss代码。服务器的响应不会以任何形式包含攻击者的脚本。当用户的浏览器处理这个响应时，DOM对象就会处理xss代码，导致存在XSS漏洞。

# Xss技术分类 

## 反射型跨站（非持续型）



## 存储型跨站（持续型）

 

## DOM型 

https://xz.aliyun.com/t/3919

# Xss常见的攻击

盗取cookie 

##  反射型XSS：

 

[靶场地址][http://43.247.91.228:81/vulnerabilities/xss_r/]

用户：admin

密码：password

### 0x1.选择安全等级为low

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jvh89fnj30jo0fhwhz.jpg)

### 0x2.选择XSS reflected 

在选择框输入xss 弹出显示代码：`<script>alert(/OOO/)</script>`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jw21n68j30jm0dqjt5.jpg)

### 0x3. 查看源代码分析过滤了什么？

点击view source 查看源代码

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jwwq5eoj30dd049aa0.jpg)

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jx8gt4kj30jk09bmy2.jpg)

上述代码中没有对name参数作任何过滤和检查，存在明显的xss漏洞。

 

### 2x01 将代码的安全等级调整为medium 

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jxmr6l3j30ji0bd0vo.jpg)

### 2x02 选择XSS reflected ,

并输入:`<script>alert(/xss/)</script>` 检查xss漏洞。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jxx6dkaj30jl07qwgb.jpg)

发现输出： hello alert(/xss/) ，这里表面对name 参数进行了过滤。

### 2x03.这里我们直接打开源代码分析过滤了什么？

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jy9uz2kj30k50af0t5.jpg)

发现多了`str_replace('<script>','',$_GET['name'])`这段代码，这句代码替换`<script>`标签为''空字符。所以我们可以使用双写绕过。

 

### 2x04 另一种方法，大小的混淆绕过：

输入：`</pre><SCriPt>alert(/xss/)</ScRipt><pre3x01 `将代码安全等级设置为high 模式

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jz7pw9cj30aa07smx7.jpg)

### 3x01 将代码安全等级设置为high 模式

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jztnzsgj30jt0dgn0i.jpg)

### 3x02 我们直接打开代码进行查看，过滤了什么？

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59k1lzo80j30ju0783z6.jpg)

这里利用了htmlspecialchars()函数进行过滤。这个函数可以把& （和号）、"（双引号）、'（单引号）、<（小于）、>（大于）这些敏感符号都进行转义，所有的跨站语句中基本都离不开这些符号，因而只需要这一个函数就阻止了XSS漏洞，所以跨站漏洞的代码防御还是比较简单的。

##  反射型xss盗取cookie

 

以管理员身份登录到DWVA ，DWVA Security 设置为 LOW 并点击submit

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59kapsf4dj30yf0iin1p.jpg)

点击XSS(Reflected) 

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59kb13f40j30ph0g175y.jpg)

在输入框输入`<script>alert(‘xss’)</script>  `验证是否存在xss漏洞

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59kbh50ozj30qz08xq3q.jpg)

 

打开xss平台 ，新建一个xss利用项目：

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59kbs8ulzj30n90rptc7.jpg)

 

查看xss利用的源代码：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59kc5h6b7j30n80nkmzt.jpg)

调用攻击代码放到xss注入点，盗取到登录的cookie

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59kdd05mkj30n90rpjv4.jpg)

再利用cookie利用工具进行登录利用

# DVWA储存型xss

## 0x01 设置代码安全等级为: low 级别

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qcir68jj30ii0cngoi.jpg)

##   0x02 在内容框中输入储存型xss 测试代码：

## `<script>alert(/xss/)</script>`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qdbus30j30iv0bj75y.jpg)

## 0x03 我们通过刷新链接 检查xss是否已经存储代码。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qdtpuzuj30iw0b8gnk.jpg)

发现代码已经存储。

## 0x04 打开在线的xss平台， 

生成盗取cookie 的代码写入到xxs漏洞处，盗取登录用户的cookie。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qeba9hdj30j40a4gmb.jpg)

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qeo3mc1j30j50eqn19.jpg)

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qf5ukz7j30ih04hweg.jpg)

已经盗取到登录用户的cookie。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qfg8coej30if0dk42d.jpg)

## 0x05 查看源代码，存在的问题。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qg1j1arj30ir0digmf.jpg)

## 1x01 将代码的安全等级设置为medium

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qgduan9j30im0d741t.jpg)

## 1x02 测试xss 的漏洞存在那个地方？

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qgupftrj30ij06o3yt.jpg)

在内容框输入`<scrip>alert(/xss/)</scrip> `发现没有弹出xss

1x03 打开代码进行分析

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59qh9lx7jj30ii0a5q4j.jpg)

使用函数对输入的函数进行了过滤。

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 