### [Hackbar](https://mrxn.net/tag/hackbar)作为[网络安全](https://mrxn.net/tag/渗透)学习者常备的工具，也是[渗透测试](https://mrxn.net/tag/渗透) 中搭配Firefox必不可少的黑客工具，但是最新版也开始收费了，一个月3刀，6个月5刀，1年9刀，虽然费用不贵，还是动动手。

But, Firefox 和 Chrome 的插件有点不一样，firefox 的插件必须是经过签名过的，才能加载到浏览器。修改插件里的任何一个字符都会导致签名失效。
非签名的只能通过临时加载插件的方式，加载到浏览器里面。
后面介绍chrome下直接修改代码实现[破解](https://mrxn.net/tag/破解)，这里先说Firefox下目前的两个替代方案：

##  方案一

 使用没升级前的[hackbar](https://mrxn.net/tag/hackbar)，升级完的是2.2.2版本，找到一个2.1.3版本，没有收费代码，可以直接加载使用。
 hackbar2.1.3版本：https://github.com/Mr-xn/hackbar2.1.3
 使用方法：打开firefox的插件目录
![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7ypu1135bj30by0bx3yw.jpg)
 然后点 "从文件安装附加组件"
![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7ypuksnuwj30g706oq4u.jpg)
 加载{4c98c9c7-fc13-4622-b08a-a18923469c1c}.xpi 即可
 但是，切记！！！切记！！！切记！！！
**一定记住要关闭插件的自动更新！！！，否则浏览器会自动更新插件到收费版本**！！!

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7ypuyzcl7j30g608amzn.jpg)

## 方案二

 在火狐扩展组件商店搜索

 “Max hackbar” 
地址：https://addons.mozilla.org/zh-CN/firefox/search/?q=max%20hackbar&platform=WINNT&appver=66.0.5
 基本上可以替代[hackbar](https://mrxn.net/tag/hackbar) 