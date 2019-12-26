Web (新手练习题)之 Get_Post

    0x01 前言
    
    想玩玩攻防世界的朋友，CE已附上XCTF Web 练习题传送门：https://adworld.xctf.org.cn/
    
    难度系数： 一星
    
    题目来源： Cyberpeace-n3k0
    
    题目描述：X老师告诉小宁同学HTTP通常使用两种请求方法，你知道是哪两种吗？
    
    题目分析：从题目中可以看出装X老师想让小宁这靓仔（叼毛），熟悉常见的HTTP协议，HTTP协议有八种，但常见的两种貌似只有Get和Post，那么可以想象的到获得Flag 的方法，就在这两种请求方式里。
    
    HTTP协议中共定义了八种方法或者叫“动作”来表明对Request-URI指定的资源的不同操作方式，具体介绍如下：
    
        GET：向特定的资源发出请求。
    
        POST：向指定资源提交数据进行处理请求（例如提交表单或者上传文件）。数据被包含在请求体中。POST请求可能会导致新的资源的创建和/或已有资源的修改。
    
        OPTIONS：返回服务器针对特定资源所支持的HTTP请求方法。也可以利用向Web服务器发送'*'的请求来测试服务器的功能性。
    
        HEAD：向服务器索要与GET请求相一致的响应，只不过响应体将不会被返回。这一方法可以在不必传输整个响应内容的情况下，就可以获取包含在响应消息头中的元信息。
    
        PUT：向指定资源位置上传其最新内容。
    
        DELETE：请求服务器删除Request-URI所标识的资源。
    
        TRACE：回显服务器收到的请求，主要用于测试或诊断。
    
        CONNECT：HTTP/1.1协议中预留给能够将连接改为管道方式的代理服务器。
    
    用 GET 给后端传参的方法是：在?后跟变量名字，不同的变量之间用&隔开
    
    举例：
    
    https://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&rsv_idx=1

 

0x02 HackBar 解题

根据提示，先Get 方式提交一个名为a值为1的变量， 在 url 后添加/？a=1 ，点击Execute 按钮，即可发送 get 请求

然后再以POST方式，提交一个名为b值为2的变量。打开HackBar，复制刚刚Get 的URL，勾选Post data，填入b=2，点击Execute 按钮，即可发送 POST 请求

PS：切记，一定要在HackBar 里面勾选Post data

成功获得Flag

 

https://blog.csdn.net/God_XiangYu/article/details/100601630