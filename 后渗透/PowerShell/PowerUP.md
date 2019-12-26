## PowerUp 攻击模块讲解

PowerUP是Privesc模块下的一个脚本，功能相当强大，拥有众多用来寻找目标主机windows服务器漏洞进行提权的用脚本。

通常，在Windows下可以通过内核漏洞来提升权限

但是，我们常常会遇到无法通过内核漏洞提权所处服务器的情况，这个时候就需要利用脆弱的windows服务提权，或者利用常见的系统服务，通过其继承的系统权限来完成提权等，此框架可以在内核提权行不通的时候，帮助我们寻找服务器的脆弱点，进而通过脆弱点实现提权的目的。



## 1Invoke-Allchecks检查

###  首先上传powerup脚本至目标服务器

`upload //var/www/html/PowerSploit/Privesc/PowerUp.ps1 c:\`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5qvm4mvcjj30ve03276x.jpg)

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5qvpkv5dlj30ji0c2tad.jpg)

### 1) 使用IEX在内存中加载此脚本，执行Invoke-AllChecks 检查漏洞

```
powershell.exe -nop -exec bypass -c "IEX (New-Object Net.WebClient).DownloadString('C:\PowerUp.ps1'); Invoke-AllChecks"
```

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5qw7op06fj30wx0e7anr.jpg)

### 也可以在cmd环境导入模块绕过策略执行:

```
powershell.exe -exec bypass -Command "& {Import-Module c:\PowerUp.ps1; Invoke-AllChecks}"
```

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5qwrwc4fmj30x90cwqd6.jpg)

## 2.Find-PathDLLHijack 检测写入权限

可以使用 icacls（Windows 内建的一个工具，用来检查对有漏洞目录是否有写入的权限）来验证下 PowerUp 脚本检测是否正确，我们先对于检测出来的漏洞目录进行权限的检测

 如查看：C:\Program Files\Windows Media Player\wmpnetwk.exe路径下的权限检测。

```
icacls "C:\Program Files\Windows Media Player\wmpnetwk.exe"
```

使用Find-PathDLLHijack 检测当前%PATH%的那些目录是用户可以写入的

`powershell.exe -nop -exec bypass -c "IEX (New-Object Net.WebClient).DownloadString('C:\PowerUp.ps1'); Find-PathDLLHijack"`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g5qx8uc83nj314k0qg4qp.jpg)

## 【X】3.Get-applicationHost

该模块可以利用系统上的applicationHost.config文件恢复加密过的应用池和虚拟目录的密码，

## 【x】4.Get-RegistryAIwayslnstallElevated

该模块用于检查AIwayslnstallElevated注册表项是否被设置。如果已被设置，意味着MSI文件是以SYSTEM权限运行的

## 【X】5.Get-RegistryAutLogon

该模块用于检测Winlogin注册表的AutoAdminLogon项有没有被设置，可查询默认的用户名和密码，

## 6.Get-ServiceDetaill

该模块用于返回某服务的信息，

`powershell.exe -nop -exec bypass -c "IEX (New-Object Net.WebClient).DownloadString('C:\PowerUp.ps1'); Get-ServiceDetail -ServiceName Dhcp"`        //获取dhcp服务器的详细信息。

## [x]7.Get-ServiceFilePermission

该模块用于检查当前用户能够在那些服务的目录写入相关联的可执行文件，

可以通过这些文件实现提权

`powershell.exe -nop -exec bypass -c "IEX (New-Object Net.WebClient).DownloadString('C:\PowerUp.ps1'); Get-ServiceFilePermission"`

## 【x】8.Test-ServiceDacIPermission

该模块用于检查所有可以的服务，并尝试对这些打开的服务进行修改，如果可修改，则返回该服务对象。

## 【x】9.Get-ServiceUnquoted

该模块用于检查服务路径，返回包含空格但是不带引号的服务路径。

## 10.Get-UnattendedlnstallFile

该模块用于检测文件里包含的部署凭据。

## 11.Get-ModifiableRegistryAutoRun

该模块用于检查开机自启的应用程序路径和注册表键值，然后返回当前用户可修改的程序路径。

## 12.Get-ModifiableScheduledTaskFile

该模块用于返回当前用户能够修改的计划任务程序的名称和路径，

## 13.Get-Webconfig

该模块用于返回当前服务器上web.config文件中的数据库连接字符串的明文

## 14.Invoke-ServiceAbuse

该模块通过修改服务来添加用户到指定组，并可以通过设置-cmd参数触发添加用户的自定义命令，

## 15.Restore-ServiceBinary

该模块用于恢复服务的可执行文件到原始目录，

## 16.Test-ServiceDacIPermission

该模块用于检查某个用户是否在服务中有自由访问控制的权限，结果会返回true和false

## 17.Write-HijackDII

该模块用于输出一个自定义命令并且能够自我删除的bat文件到$env:Temp\debug.bat 并输出一个能够启动这个bat文件的DLL

## 18.Write-UserAddMSI

该模块用于生成一个安装文件，运行这个安装文件后弹出添加用户的对话框，

## 19.Write-ServiceBinary

该模块用于预编译C#服务的可执行文件，默认创建一个管理员账号，可通过Command定制自己的命令，

## 20.Install-ServiceBinary

该模块通过Write-ServiceBinary写一个C#的服务用来添加用户，



 

