# CSRF漏洞

https://www.cnblogs.com/shikyoh/p/4959678.html

https://www.freebuf.com/column/155800.html

## CSRF漏洞简介 

 CSRF（Cross-Site Request Forgery，跨站点伪造请求）是一种网络攻击方式，该攻击可以在受害者毫不知情的情况下以受害者名义伪造请求发送给受攻击站点，从而在未授权的情况下执行在权限保护之下的操作，具有很大的危害性。具体来讲，可以这样理解CSRF攻击：攻击者盗用了你的身份，以你的名义发送恶意请求，对服务器来说这个请求是完全合法的，但是却完成了攻击者所期望的一个操作，比如以你的名义发送邮件、发消息，盗取你的账号，添加系统管理员，甚至于购买商品、虚拟货币转账等。

CSRF攻击方式并不为大家所熟知，实际上很多网站都存在CSRF的安全漏洞。早在2000年，CSRF这种攻击方式已经由国外的安全人员提出，但在国内，直到2006年才开始被关注。2008年，国内外多个大型社区和交互网站先后爆出CSRF漏洞，如：百度HI、NYTimes.com（纽约时报）、Metafilter（一个大型的BLOG网站）和YouTube等。但直到现在，互联网上的许多站点仍对此毫无防备，以至于安全业界称CSRF为“沉睡的巨人”，其威胁程度由此“美誉”便可见一斑。

## CSRF攻击原理及实例 

![](C:\Users\dell\Desktop\CSRF\CSRF原理.jpg)

![1564539745432](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1564539745432.png)

 CSRF攻击原理比较简单，如图1所示。其中Web A为存在CSRF漏洞的网站，Web B为攻击者构建的恶意网站，User C为Web A网站的合法用户。 
                                                   

1 用户C打开浏览器，访问受信任网站A，输入用户名和密码请求登录网站A；

2 在用户信息通过验证后，网站A产生Cookie信息并返回给浏览器，此时用户登录网站A成功，可以正常发送请求到网站A；

3 用户未退出网站A之前，在同一浏览器中，打开一个TAB页访问网站B；

4 网站B接收到用户请求后，返回一些攻击性代码，并发出一个请求要求访问第三方站点A； 
 **(****网站B对网站A发出一个请求的同时，浏览器也会把网站A产生Cookie信息带上)–相当于浏览器访问网站A—这是user信息被盗用的关键**

5 浏览器在接收到这些攻击性代码后，根据网站B的请求，在用户不知情的情况下携带Cookie信息，向网站A发出请求。网站A并不知道该请求其实是由B发起的，所以会根据用户C的Cookie信息以C的权限处理该请求，导致来自网站B的恶意代码被执行。

## CSRF攻击分类



CSRF站外类型的漏洞本质上就是传统意义上的外部提交数据问题。通常程序员会考虑给一些留言或者评论的表单加上水印以防止SPAM问题（这里，SPAM可以简单的理解为垃圾留言、垃圾评论，或者是带有站外链接的恶意回复），但是有时为了提高用户的体验性，可能没有对一些操作做任何限制，所以攻击者可以事先预测并设置请求的参数，在站外的Web页面里编写脚本伪造文件请求，或者和自动提交的表单一起使用来实现GET、POST请求，当用户在会话状态下点击链接访问站外Web页面，客户端就被强迫发起请求。

CSRF站内类型的漏洞在一定程度上是由于程序员滥用类变量造成的。在一些敏感的操作中（如修改密码、添加用户等），本来要求用户从表单提交发起请求传递参数给程序，但是由于使用了REQUEST类变量造成的。在一些敏感的操作中（如修改密码、添加用户等），本来要求用户从表单提交发起POST请求传递参数给程序，但是由于使用了_REQUEST等变量，程序除支持接收POST请求传递的参数外也支持接收GET请求传递的参数，这样就会为攻击者使用CSRF攻击创造条件。一般攻击者只要把预测的请求参数放在站内一个贴子或者留言的图片链接里，受害者浏览了这样的页面就会被强迫发起这些请求。

## CSRF攻击实例

下面以Axous 1.1.1 CSRF Add Admin Vulnerability（漏洞CVE编号：CVE-2012-2629）为例，介绍CSRF攻击具体实施过程。

Axous是一款网上商店应用软件。Axous 1.1.1以及更低版本在实现上存在一个CSRF漏洞，远程攻击者可以通过构造特制的网页，诱使该软件管理员访问，成功利用此漏洞的攻击者可以添加系统管理员。利用此漏洞主要包含以下三个过程：

1. 攻击者构造恶意网页。在实施攻击前，攻击者需要构造一个与正常添加管理员用户基本一样的网页，在该恶意网页中对必要的参数项进行赋值，并将该网页的action指向正常添加管理员用户时访问的URL，核心代码如图2所示；
2. 攻击者利用社会工程学诱使Axous系统管理员访问其构造的恶意网页；
3. 执行恶意代码。当系统管理员访问恶意网页时，恶意代码在管理员不知情的情况下以系统管理员的合法权限被执行，攻击者伪造的管理员账户添加成功。 
                      

## CSRF漏洞防御 

 CSRF漏洞防御主要可以从三个层面进行，即服务端的防御、用户端的防御和安全设备的防御。

服务端的防御 
 目前业界服务器端防御CSRF攻击主要有三种策略：验证HTTP Referer字段，在请求地址中添加token并验证，在HTTP头中自定义属性并验证。下面分别对这三种策略进行简要介绍。

1 验证HTTP Referer字段

根据HTTP协议，在HTTP头中有一个字段叫Referer，它记录了该HTTP请求的来源地址。在通常情况下，访问一个安全受限页面的请求必须来自于同一个网站。比如某银行的转账是通过用户访问http://bank.test/test?page=10&userID=101&money=10000页面完成，用户必须先登录bank. test，然后通过点击页面上的按钮来触发转账事件。当用户提交请求时，该转账请求的Referer值就会是转账按钮所在页面的URL（本例中，通常是以bank. test域名开头的地址）。而如果攻击者要对银行网站实施CSRF攻击，他只能在自己的网站构造请求，当用户通过攻击者的网站发送请求到银行时，该请求的Referer是指向攻击者的网站。因此，要防御CSRF攻击，银行网站只需要对于每一个转账请求验证其Referer值，如果是以bank. test开头的域名，则说明该请求是来自银行网站自己的请求，是合法的。如果Referer是其他网站的话，就有可能是CSRF攻击，则拒绝该请求。 
 java获取HTTP Referer代码：

​        // 获取请求是从哪里来的  

​        String referer = request.getHeader("referer");  

​        // 如果是直接输入的地址，或者不是从本网站访问的重定向到本网站的首页  

​        if (referer == null || !referer.startsWith("http://localhost")) {  

​            response.sendRedirect("/day06/index.jsp");  

​            // 然后return，不要输出后面的内容了  

​            return;  

​        }  

2 在请求地址中添加token并验证

CSRF攻击之所以能够成功，是因为攻击者可以伪造用户的请求，该请求中所有的用户验证信息都存在于Cookie中，因此攻击者可以在不知道这些验证信息的情况下直接利用用户自己的Cookie来通过安全验证。由此可知，抵御CSRF攻击的关键在于：在请求中放入攻击者所不能伪造的信息，并且该信息不存在于Cookie之中。鉴于此，系统开发者可以在HTTP请求中以参数的形式加入一个随机产生的token，并在服务器端建立一个拦截器来验证这个token，如果请求中没有token或者token内容不正确，则认为可能是CSRF攻击而拒绝该请求。

3 在HTTP头中自定义属性并验证

自定义属性的方法也是使用token并进行验证，和前一种方法不同的是，这里并不是把token以参数的形式置于HTTP请求之中，而是把它放到HTTP头中自定义的属性里。通过XMLHttpRequest这个类，可以一次性给所有该类请求加上csrftoken这个HTTP头属性，并把token值放入其中。这样解决了前一种方法在请求中加入token的不便，同时，通过这个类请求的地址不会被记录到浏览器的地址栏，也不用担心token会通过Referer泄露到其他网站。

4 用户端的防御 
 对于普通用户来说，都学习并具备网络安全知识以防御网络攻击是不现实的。但若用户养成良好的上网习惯，则能够很大程度上减少CSRF攻击的危害。例如，用户上网时，不要轻易点击网络论坛、聊天室、即时通讯工具或电子邮件中出现的链接或者图片；及时退出长时间不使用的已登录账户，尤其是系统管理员，应尽量在登出系统的情况下点击未知链接和图片。除此之外，用户还需要在连接互联网的计算机上安装合适的安全防护软件，并及时更新软件厂商发布的特征库，以保持安全软件对最新攻击的实时跟踪。

##  漏洞利用演示

**DVWA-CSRF** 

### Low等级

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rj7imbaj309d04h3yg.jpg)

**抓包**

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rjjuze9j30jb08a0tk.jpg)

**正常跳转**

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rjwbolhj30cr092t99.jpg)

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rkaznc7j30m509xta1.jpg)

**在这里我们把密码改为qwer**

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rl0tz7ij30k50bpwfm.jpg)

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rlblovyj30h80ak3zr.jpg)



 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rlnu3npj30eh08haa5.jpg)



 ![1563863977602](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1563863977602.png)



 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rmc4dwcj30lp09b74c.jpg)

**成功进入了DVWA**

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rmmq1hcj30my0czdiu.jpg)

### CSRF Medium等级

开始，抓包

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rn0qgarj30e2067t8y.jpg)



![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rnbnvsjj31080bvack.jpg)

**很显然，网站对referer做了验证，绕过referer验证有以下几种方法：**

**1****）空Referer绕过：**

在referer字段后添加：http:// https:// ftp:// file://,在发送，看是否可以绕过referer验证。

**2****）判断referer是否存在某个关键词。**

**在本示例中用第二种方法绕过referer验证：**

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rnt5t3xj310b0a3jti.jpg)

**构造csrf poc：**

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59ro5r8guj30jt0b1dgy.jpg)

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59royz8alj30hd08174o.jpg)

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rp9mkq6j30kr08caaa.jpg)

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rpmyq2nj30ga07raa8.jpg)

### CSRF High等级

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rq3c9u8j30od08pgmj.jpg)

**所以像medium和low等级那样的方法是不能用的了，但是我们可以利用burp的插件CSRF　Token　Tracker绕过token验证：**

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rqctd4cj30pc0fyadn.jpg)



 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rzieiw4j30uu04lt99.jpg)



![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59rzujnl4j30wb0aw0u0.jpg)



**然后来到repeater选项下：**

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59s05noxxj30yc096ju1.jpg)



 