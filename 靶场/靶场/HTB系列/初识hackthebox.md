Hack The Box是一个在线平台，允许您测试您的渗透测试技能，并与其他类似兴趣的成员交流想法和方法。它包含一些不断更新的挑战。其中一些模拟真实场景，其中一些更倾向于CTF风格的挑战。

http://www.sohu.com/a/288310203_120055360

一. 先注册一个登录账号

Hack The Box是一个在线平台，注册需要有邀请码



https://www.hackthebox.eu/invite  在console输入 makeInviteCode()得到一串base64



解码得路径：

In order to generate the invite code,

make a POST request to /api/invite/generate

 

对 https://www.hackthebox.eu/api/invite/generate 进行post请求，base64解码得到邀请码



注册时会有人机验证，建议在Chrome中注册。（如果没有验证需要翻墙。）

 

在kali下装置这几个源

apt-get install network-manager-openvpn

apt-get install network-manager-openvpn-gnome

apt-get install network-manager-pptp

apt-get install network-manager-pptp-gnome

apt-get install network-manager-strongswan

apt-get install network-manager-vpnc

apt-get install network-manager-vpnc-gnome

 

点击网页的Connection Pack按钮下载ovpn文件

sudo openvpn --config xxxx.ovpn

连接成功后会在页面显示√


