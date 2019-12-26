- 一键测试服务器到国内的速度脚本Superspeed.sh ：

```
wget https://raw.githubusercontent.com/oooldking/script/master/superspeed.sh
 chmod +x superspeed.sh
 ./superspeed.sh
```

- 一键VPS性能检测，脚本unixbench.sh

```
wget --no-check-certificate  https://github.com/teddysun/across/raw/master/unixbench.sh 

chmod +x unixbench.sh
./unixbench.sh
```



- 一键测试回程Ping值工具：mPing

- ```
  wget https://raw.githubusercontent.com/helloxz/mping/master/mping.sh
  bash mping.sh
  ```

- VPS速度测试工具
   在线测试工具。使用在线测试工具，可以方便得到服务器的响应时间，这一招对于国外的VPS特别有效果。以下是搜集整理的实用在线网站速度测试工具网站：

> [http://ping.chinaz.com/](https://link.jianshu.com?t=http%3A%2F%2Fping.chinaz.com%2F)
>  [http://www.ipip.net/ping.php](https://link.jianshu.com?t=http%3A%2F%2Fwww.ipip.net%2Fping.php)
>  [https://www.17ce.com/](https://link.jianshu.com?t=https%3A%2F%2Fwww.17ce.com%2F)
>  [http://www.webkaka.com/](https://link.jianshu.com?t=http%3A%2F%2Fwww.webkaka.com%2F)
>  [http://ce.cloud.360.cn/](https://link.jianshu.com?t=http%3A%2F%2Fce.cloud.360.cn%2F)

这几个在线测速工具各有各的优缺点，推荐使用ipip.net测试服务器IP和路由追踪，用17ce.com测试网页加载速度，用ping.chinaz.com用国内不同地方的Ping值。

> 下载地址：[https://www.ucblog.net/wzfou/WinMTR-CN-IP.zip](https://link.jianshu.com?t=https%3A%2F%2Fwww.ucblog.net%2Fwzfou%2FWinMTR-CN-IP.zip)
>  项目主页：[https://github.com/oott123/WinMTR](https://link.jianshu.com?t=https%3A%2F%2Fgithub.com%2Foott123%2FWinMTR)
>  带地图版：[https://cdn.ipip.net/17mon/besttrace.exe](https://link.jianshu.com?t=https%3A%2F%2Fcdn.ipip.net%2F17mon%2Fbesttrace.exe)

1. 启用

   WinMTR

   ，点击可以更新IP地址。

   ![img](https:////upload-images.jianshu.io/upload_images/3109491-8584ba7877b61a14.png?imageMogr2/auto-orient/strip|imageView2/2/w/555/format/webp)

2. 输入你想要追踪的域名或者服务器IP，接着你就可以看到数据包经过的节点还有丢包等情况，同时支持导出文本。

   ![img](https:////upload-images.jianshu.io/upload_images/3109491-b64d8065e14bad90.png?imageMogr2/auto-orient/strip|imageView2/2/w/976/format/webp)

3. 相关的参数说明如下：

> Hostname：到目的服务器要经过的每个主机IP或名称
>  Nr：经过节点的数量；以上图百度为例子：一共要经过10个节点，其中第一个是出口的路由器
>  Loss%：ping 数据包回复失败的百分比；藉此判断，那个节点（线路）出现故障，是服务器所在机房还是国际路由干路
>  Sent：已传送的数据包数量
>  Recv：成功接收的数据包数量
>  Best：回应时间的最小值
>  Avrg：平均回应时间
>  Worst：回应时间的最大值
>  Last：最后一个数据包的回应时间