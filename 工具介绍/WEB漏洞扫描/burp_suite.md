#                   BurpSuite pro v2.0 使用入门教程





**目录**

- [BurpSuite简介](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label0)
- [软件版本](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label1)
- [主要模块](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label2)
- [浏览器代理设置（IE浏览器）](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label3)
- 插件安装
  - [在BApp Store中安装插件](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label4_0)
  - [安装自定义插件](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label4_1)
- [Scan](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label5)
- Proxy模块
  - [Module1：intercept](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label6_0)
  - [Module2：http history](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label6_1)
  - [Module3：WebSockets history](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label6_2)
  - [Module4：Options](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label6_3)
- Intruder模块（暴力破解）
  - [Module1：Target](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label7_0)
  - [Module2：Positions](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label7_1)
  - [Module3：Payloads](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label7_2)
  - [Module4：Opetions](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label7_3)
- [Repeater模块（上传绕过）](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label8)
- [Decoder模块(编码模块)](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label9)
- [过滤器的使用](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label10)
- [参考资料](https://www.cnblogs.com/jsjliyang/p/10853307.html#_label11) 



# BurpSuite简介

BurpSuite是进行Web应用安全测试集成平台。它将各种安全工具无缝地融合在一起，以支持整个测试过程中，从最初的映射和应用程序的攻击面分析，到发现和利用安全漏洞。Burpsuite结合先进的手工技术与先进的自动化，使你的工作更快，更有效，更有趣。在[安全人员常用工具表](http://sectools.org/)中，burpsuite排在第13位，且排名在不断上升，由此可见它在安全人员手中的重要性。Burpsuite的模块几乎包含整个安全测试过程，从最初对目标程序的信息采集，到漏洞扫描及其利用，多模块间高融合的配合，使得安全测试的过程更加高效。



# 软件版本

本文中使用的软件版本为**Burp Suite Professional v2.0.11beta**,1.6版本中有Spider模块，2.0版本中未找到，故不做介绍。

# 主要模块

介绍Burp Suite中的主要模块，如下：
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512191959802-6315775.png)



1. Dashboard(仪表盘)——显示任务、实践日志等。
2. Target(目标)——显示目标目录结构的的一个功能。
3. Proxy(代理)——拦截HTTP/S的代理服务器，作为一个在浏览器和目标应用程序之间的中间人，允许你拦截，查看，修改在两个方向上的原始数据流。
4. Intruder(入侵)——一个定制的高度可配置的工具，对web应用程序进行自动化攻击，如：枚举标识符，收集有用的数据，以及使用fuzzing 技术探测常规漏洞。
5. Repeater(中继器)——一个靠手动操作来触发单独的HTTP 请求，并分析应用程序响应的工具。
6. Sequencer(会话)——用来分析那些不可预知的应用程序会话令牌和重要数据项的随机性的工具。
7. Decoder(解码器)——进行手动执行或对应用程序数据者智能解码编码的工具。
8. Comparer(对比)——通常是通过一些相关的请求和响应得到两项数据的一个可视化的“差异”。
9. Extender(扩展)——可以让你加载Burp Suite的扩展，使用你自己的或第三方代码来扩展Burp Suit的功能。
10. Options(设置)——包括Project options和User options，是对Burp Suite的一些设置。

[回到顶部](https://www.cnblogs.com/jsjliyang/p/10853307.html#_labelTop)

# 浏览器代理设置（IE浏览器）

打开浏览器，在右上角工具中选择Internet选项，或者在菜单栏选择工具->Internet选项
 1.选择“连接选项卡”
 2.选择“局域网设置”
 3.在“为LAN使用代理服务器”前打勾
 4.地址输入`127.0.0.1` 端口输入`8080`（注：在BurpSuite中默认为8080端口，其他端口请修改BurpSuite配置）

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192011109-374391966.png)

# 插件安装

BurpSuite给出了两种插件安装方法，一种是在线安装：通过**BApp Store**安装插件；第二种是本地安装：添加本地环境中的插件。



## 在BApp Store中安装插件

打开Extender选项卡，在BApp Store中可以下载安装很多插件。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192019057-356340362.png)





## 安装自定义插件

在Extender选项卡中的Extensions中点击add按钮，会弹出根据插件类型选择插件的目录。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192025195-2072938095.png)



# Scan

- 在Dashboard中可以创建扫描任务，进行扫描。在1.6版本中有一个专门的Scanner模块来进行扫描的使用。

  ![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192032870-928758692.png)

- Event log中显示事件列表。

- Issue activity显示任务中发现的漏洞。

# Proxy模块

代理模块作为BurpSuite的核心功能，拦截HTTP/S的代理服务器，作为一个在浏览器和目标应用程序之间的中间人，允许拦截，查看，修改在两个方向上的原始数据流。



## Module1：intercept

用于显示修改HTTP请求及响应内容，并可以将拦截的HTTP请求快速发送至其他模块处理。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192104052-1291783995.png)



- Forward：用于发送数据。当把所需要的HTTP请求编辑编辑完成后，手动发送数据。
- Drop：将该请求包丢弃。
- Intercept is off/on:拦截开关。当处于off状态下时，BurpSuite会自动转发所拦截的所有请求；当处于on状态下时，BurpSuite会将所有拦截所有符合规则的请求并将它显示出来等待编辑或其他操作。
- Action:功能菜单，与右键菜单内容相同，在这可以将请求包发送到其他的模块或者对数据包进行其他的操作，菜单中的详细功能我们会在后续课程中陆续说明。



## Module2：http history

这里将记录经过代理服务器访问的所有请求，即使当Intercept is off时也会记录。
 记录包括：#(请求索引号)、Host(主机)、Method(请求方式)、URL(请求地址)、Params(参数)、Edited(编辑)、Status(状态)、Length(响应字节长度)、MIME   type(响应的MLME类型)、Extension(地址文件扩展名)、Title(页面标题)、Comment(注释)、SSL、IP(目标IP地址)、Cookies、Time(发出请求时间)、Listener  port(监听端口)。
 




 下方窗口可以显示请求的详细内容（Request）及其响应内容（Response），通过右键菜单也可以将请求发送至其他模块。双击某个请求即可打开详情,通过Previous/next可以快速切换请求，并且Action也可以将请求发送至其他模块。

这个版块用于记录WebSockets的数据包，是HTML5中最强大的通信功能，定义了一个全双工的通信信道，只需Web上的一个 Socket即可进行通信，能减少不必要的网络流量并降低网络延迟。



## Module4：Options

该版块主要用于设置代理监听、请求和响应，拦截反应，匹配和替换，ssl等。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192116298-1360451433.png)



- Proxy  Listeners：代理侦听器是侦听从您的浏览器传入的连接本地HTTP代理服务器。它允许您监视和拦截所有的请求和响应，并且位于BurpProxy的工作流的心脏。默认情况下，Burp默认监听12.0.0.1地址，端口8080。要使用这个监听器，你需要配置你的浏览器使用127.0.0.1:8080作为代理服务器。此默认监听器是必需的测试几乎所有的基于浏览器的所有Web应用程序。
- Intercept Client Requests：配置拦截规则，设置拦截的匹配规则。 当Intercept request based  on the following  rules为选中状态时，burpsuite会配置列表中的规则进行拦截或转发。注意：如果该复选框未选中，那么即使Intercept is  on也无法截取数据包。 
  - 规则可以通过Enabled列中的复选框选择开启或关闭。
  - 规则可以是域名， IP地址，协议， HTTP方法， URL，文件扩展名，参数，cookie ，头/主体内容，状态代码，MIME类型， HTML页面标题等。
  - 规则按顺序处理，并且使用布尔运算符AND和OR组合。
- Intercept Server  Responses：功能类似于配置拦截规则，设置拦截的匹配规则，不过这个选项是基于服务端拦截，当选小的Intercept request  based on the following rules为选中状态时，burpsuite会匹配响应包。
- Intercept WebSockets Messages：用于设置拦截WebSockets数据。
- Response Modification：用于执行响应的自动修改。可以使用这些选项来自动修改HTML应用程序响应中匹配的内容。
- Match and  replace：用于自动替换请求和响应通过代理的部分。对于每一个HTTP消息，已启用的匹配和替换规则依次执行，选择适用的规则进行匹配执行。规则可以分别被定义为请求和响应，对于消息头和身体，并且还特别为只请求的第一行。每个规则可以指定一个文字字符串或正则表达式来匹配，和一个字符串来替换它。对于邮件头，如果匹配条件，整个头和替换字符串匹配留空，然后头被删除。如果指定一个空的匹配表达式，然后替换字符串将被添加为一个新的头。有可协助常见任务的各种缺省规则，这些都是默认为禁用。  匹配多行区域。您可以使用标准的正则表达式语法来匹配邮件正文的多行区域。
- SSL Pass Through：指定WEB服务器在经过burpsuite连接时使用SSL连接。
- Miscellaneous：其他选项，这些选项控制着Burp代理的行为的一些具体细节。

# Intruder模块（暴力破解）

Burp intruder是一个强大的工具，用于自动对Web应用程序自定义的攻击。它可以用来自动执行您的测试过程中可能出现的所有类型的任务。例如目录爆破，注入，密码爆破等。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192136224-1658923906.png)





## Module1：Target

用于配置目标服务器进行攻击的详细信息。

- Host：这是目标服务器的IP地址或主机名。
- Port：这是目标服务的端口号。
- Use HTTPS：这指定的SSL是否应该被使用
   在BurpSuite任何请求处，右键菜单选择“Send to intruder”选项，将自动发送到此模块下并自动填充以上内容。



## Module2：Positions

设置Payloads的插入点以及攻击类型（攻击模式）。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192146368-753434588.png)



- attack type：攻击模式设置。 
  - sniper：对变量依次进行破解。多个标记依次进行。
  - battering ram：对变量同时进行破解。多个标记同时进行。
  - pitchfork：每一个变量标记对应一个字典，取每个字典的对应项。
  - cluster bomb：每个变量对应一个字典，并且进行交集破解，尝试各种组合。适用于用户名+密码的破解。
- add：插入一个新的标记。
- clear：清除所有的标记。
- auto：自动设置标记，一个请求发到该模块后burpsuite会自动标记cookie URL等参数。
- refresh：如果必要的话，这可以要求模板编辑器的语法高亮。



## Module3：Payloads

设置payload，配置字典。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192155943-2128289854.png)



- Payload Sets：Payload数量类型设置。 

  - Payload Set：指定需要配置的变量。

  - Payload type：Payload类型。

    > Simple list：简单字典
    >  Runtime file：运行文件
    >  Custom iterator：自定义迭代器
    >  Character substitution：字符替换
    >  Recursive grep：递归查找
    >  lllegal unicode：非法字符
    >  Character blocks：字符块
    >  Numbers：数字组合
    >  Dates：日期组合
    >  Brute forcer：暴力破解
    >  Null payloads：空payload
    >  Username generator：用户名生成
    >  copy other payload：复制其他payload

- Payload Opetions[Payload type]：该选项会根据上个选项中Payload type的设置而改变。

- Payload Processing：对生成的Payload进行编码、加密、截取等操作。

- Payload  Encoding：可以配置哪些有效载荷中的字符应该是URL编码的HTTP请求中的安全传输。任何已配置的URL编码最后应用，任何有效载荷处理规则执行之后。这是推荐使用此设置进行最终URL编码，而不是一个有效载荷处理规则，因为可以用来有效载荷的grep选项来检查响应为呼应有效载荷的最终URL编码应用之前。



## Module4：Opetions

此选项卡包含了request headers，request engine，attack results ，grep  match，grep_extrack，grep  payloads和redirections。可以发动攻击之前，在主要Intruder的UI上编辑这些选项，大部分设置也可以在攻击时对已在运行的窗口进行修改。

# Repeater模块（上传绕过）

Repeater是用于手动操作和发送个别HTTP请求，并分析应用程序的响应一个简单的工具。可以发送一个内部请求从Burp任何地方到Repeater，修改请求并且发送。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192214207-1763776146.png)



- 可以从Proxy history、site map、Scanner等模块中右键菜单send to repeater发送到repeater，对页面数据进行修改发送。
- 点击go，发送请求，右边响应请求。
- 可以通过“<“和”>“来返回上一次和下一个操作。
- 单击”x“可以删除当前测试请求页面。
- 底部的功能用于搜索条件，可以用正则表达式，底部右边显示匹配结果数。

该模块的设置在菜单栏Repeater中，主要选项如下：
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192303103-1607876964.png)



- Update Content-length：更新头部长度。
- Unpack gzip/deflate：该选项控制Burp是否自动解压缩在收到的答复的gzip和deflate压缩内容。
- Follow redirections：在遇到重定向时Burp该怎么处理 
  - Never：不会跟随任何重定向。
  - On-site only：中继器将只跟随重定向到使用相同的主机，端口和协议的URL。
  - In-scope Only：中继器将只跟随重定向到的目标范围之内的URL。
  - Always：中继器将跟随重定向到任何URL任何责任。
- Process cookies in redirections：当被重定向后是否提交cookie。
- View：设置响应/请求版块的布局方式。
- Action：形同于右键菜单。

[回到顶部](https://www.cnblogs.com/jsjliyang/p/10853307.html#_labelTop)

# Decoder模块(编码模块)

将原始数据转换成各种编码和哈希表的简单工具。它能够智能地识别多种编码格式采用启发式技术。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192309717-1074301077.png)



- Decode as...：解码
- Encode as...：编码 
  - 支持的编码解码类型：1.Url 2.HTML 3.Base64 4.ASCII码 5.Hex（十六进制） 6.octal（八进制） 7.binary(二进制) 8.GZIP
- hash：支持的hash算法：1.SHA-384 2.SHA-224 3.SHA-256 4.MD2 5.SHA 6.SHA-512 7.MD5
- Smart decoding：智能解码，burpsuite会递归查询自己所支持的格式尝试解码。通过有请求的任意模块的右键菜单send to Decoder或输入数据选择相应的数据格式即可进行解码编码操作，或直接点击Smart decoding进行智能解码。

# 过滤器的使用

在burpsuite中在多个模块都可以看到Fitter过滤器的身影，它们使用方法相同，在此统一介绍。
 

![img](https://img2018.cnblogs.com/blog/1506992/201905/1506992-20190512192317758-1947372654.png)


 过滤器分为以下模块：

- Fitter by Request type：按照请求类型筛选 
  - Show only in-scope items：只显示范围内的
  - Show only requested items：只显示请求的
  - Show only parameterized requests：只显示带有参数的请求
  - Hide not-found items：隐藏未找到的
- Fitter by search term：通过关键字筛选 
  - regex：通过正则表达式匹配
  - case sensitive：是否区分大小写
  - negative search：消极搜索，选择后将筛选出不包含该关键字的请求
- Fitter by MIME type：通过文件类型筛选 
  - HTML：是否显示HTML文件请求
  - Script：是否显示脚本文件请求
  - XML：是否显示标记文件请求
  - CSS：是否显示层叠样式文件请求
  - Other text：是否显示其他类型文本请求
  - images：是否显示图片请求
  - Flash：是否显示Flash动画请求
  - Other binary：是否显示其他二进制文件。
- Fitter by file extension：通过文件后缀筛选 
  - Show only：只显示自定义的后缀请求
  - Hide ：隐藏自定义的后缀请求
- Fitter by status code：根据HTTP响应状态码筛选 
  - 2xx：显示成功的请求
  - 3xx：显示重定向的请求
  - 4xx：显示请求错误的请求
  - 5xx：显示服务器错误的请求
- Fitter by annotation:显示仅显示用户提供的注释或亮点的项目 
  - Show only commented items：只显示注释项目
  - Show only highlighted items：只显示突出显示的项目
     -Folders：是否显示文件夹
  - hide empty folders：隐藏空文件夹