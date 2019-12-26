# HTTP协议与HTTPS协议的区别

HTTPS协议的全称为Hypertext Transfer Protocol over Secure Socket Layer ,它是以安全为目标的HTTP通道，其实就是HTTP的“升级”版本，只是它比单纯的HTTP协议更加安全。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g67ancorj0j30gp07875b.jpg)

HTTPS的安全基础是SSL，即在HTTP下加入了SSL层。也就是HTTPS通过安全传输机制进行传送数据，这种机制可保护网络传送的所有数据的隐蔽性与完整性，可以降低非侵入性拦截攻击的可能性。

既然是在HTTP的基础上进行构建的HTTPS协议，所以，无论怎么样，HTTP请求与响应都是以相同的方式进行工作的。



HTTP协议与HTTPS协议的主要区别

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g67asecy48j30jc0a03z2.jpg)

​	HTTP 是超文本传输协议，信息是明文传输，HTTPS则是具有安全性的SSL加密传输协议。

HTTP与HTTPS协议使用的是完全不同的连接方式，HTTP采用80端口连接，而HTTPS则是443端口。

HTTPS协议需要到CA申请证书，一般免费证书很少，需要交费，也有些Web容器提供，如tomcat 而HTTP协议却不需要。

HTTP连接相对简单，是无状态的，而HTTPS协议是由SSL+HTTP协议构建的可进行加密传输，身份认证的网络协议，相对来说，它要比HTTP协议更安全。

问题：

TLS/SSL 的具体解释是什么？

