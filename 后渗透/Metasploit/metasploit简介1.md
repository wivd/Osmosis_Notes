 

# Metasploit  简介

https://xz.aliyun.com/t/2536#toc-18

Metasploit Framework 是非常优秀的开源渗透测试框架

Metasploit 渗透测试框架（MSF3.4）包含3功能模块：msfconsole、msfweb、msfupdate。msfupdate用于软件更新，建议使用前先进行更新，可以更新最新的漏洞库和利用代码。msfconsole 是整个框架中最受欢迎的模块，个人感觉也是功能强大的模块，所有的功能都可以该模块下运行。msfweb 是Metasploit framework的web组件支持多用户，是Metasploit图形化接口。

## msfconsole（msf控制台）

msfconsole是MSF中最主要最常用的功能组件，使用集成化的使用方法，可以使用MSF中的所有命令和模块，支持很多其它接口方式不支持的功能，启动msfconsole如下图所示：



msfconsole主要有以下特点：

 支持命令完成功能（tab键）

  支持外部命令执行（可以执行系统命令）

如下为使用ping命令：



##   使用流程


 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g603gpp983j30fp0cuwh1.jpg)


##   help命令

和其它基于命令行的程序一样，使用？或者help可以显示MSF所支持的命令，如下为MSF内置的全部命令。



![](http://ww1.sinaimg.cn/large/007bHQE8gy1g603l6bvu1j30fy0ozdgr.jpg)

## show命令

在msfconsole中键入show，系统会显示Metasploit的所有模块，若想显示某一类型的模块可以加入模块名称，最常用的主要有一下三个：show payloads、show exploits、show auxiliary。



`show auxiliary`显示Metasploit中的可用辅助模块列表，这些辅助模块包括scanner、dos、fuzzer等

`show exploits `显示Metasploit中包含的所有可以利用的攻击类型列表。

`show payloads `显示Metasploit中可以在不同平台中可以在远程主机执行的代码，即shellcode。

注：在使用具体的exploit时，只显示该平台支持的payload，例如：在使用ms08-067时，只显示windows平台可以使用的payload。

 `show options `显示可利用模块exploit的设置、条件、描述等。在具体的模块中使用，后面use命令中会有实例。

`show targets `显示可利用模块exploit支持的目标类型（操作系统、软件版本等）。在具体的模块中使用，后面use命令中会有实例。

`show advanced `显示可利用模块exploit高级信息，自定义利用程序时使用。在具体的模块中使用，后面use命令中会有实例。

`show encoders `显示可利用模块exploit的编码方式，在具体的模块中使用，后面set命令中会有实例。

##   search命令

search 命令是最常用的命令之一，用于查找各种exploit、payload、auxiliary等，命令支持基于正则表达式的模糊查询。如下为查找ms08-067实例：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g61a2ew25vj30p205kjrh.jpg)

## info命令

info用于显示特殊模块的详细信息，显示内容包括该模块的选项、目标及其它信息。以下是使用info命令显示ms08-067实例：



## use命令

use命令用于使用特殊的模块，如利用程序、shellcode或辅助模块等。以ms08-067为例，模块名称必须包含完整的路径，可以通过search命令搜索，以下还演示了show options、show targets命令的使用。

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g603zfxoj9j30q20dbmyn.jpg)

## conect 命令

connect命令可以连接到远程主机，连接方式和nc、telnet相同，可以指定端口，如下为connect命令演示：



## set命令

set命令用于当前使用模块的选项和设置参数。

set payload  xxx/xxx z设置溢出代码

set encoder xxx/xxx 设置利用程序编码方式

set target xxx 设置目标类型

set xxx xxx 设置参数

下面以ms08-067为例：



## check命令

​      部分exploit支持check命令，该命令用于检测目标系统是否存在漏洞，而不是进行溢出操作。如下：说明目标系统不存在漏洞

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g61a5sgzk4j30lo037aa1.jpg)

## 设置全局变量

Metasploit 支持设置全局变量并可以进行存储，下次登录时直接使用。设置全局变量使用setg命令，unsetg撤销全局变量，save用于保存全局变量。如下所示：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g61a6anlxmj30ls09l74m.jpg)

## exploit/run命令

设置好各个参数后，可以使用exploit命令执行溢出操作，当使用了自定义auxiliary参数时，需要用run命令执行操作。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g61a6r6tjcj30ls0480sr.jpg)

## resource命令

resource命令可以加载资源文件，并按顺序执行文件中的命令。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g61a7682usj30lr0i5gmq.jpg)

## irb命令

运行irb命令，进入irb脚本模式，可以执行命令创建脚本。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g61a7n33ulj30lt05bmx4.jpg)

