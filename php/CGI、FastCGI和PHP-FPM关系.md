## 目录
- 基础
- CGI
- FastCGI介绍
- FastCGI工作原理
- PHP-FPM工作原理
- 总结

在搭建 LAMP/LNMP 服务器时，会经常遇到 PHP-FPM、FastCGI和CGI 这几个概念。如果对它们一知半解，很难搭建出高性能的服务器。它们是什么概念，这些概念之间有什么关系呢。

### 基础

在网站架构中，web服务器（Nginx、Apache）只是内容的分发者，举个栗子，如果客户端请求的是 index.html，那么Web Server会去文件系统中找到这个文件，发送给浏览器，这里分发的是静态数据。

![image](https://github.com/fengzyz/studynotes/raw/master/images/html.png)

如果请求的是 index.php，根据配置文件，Web Server知道这个不是静态文件，需要去找 PHP 解析器来处理，那么他会把这个请求简单处理，然后交给PHP解析器。

![image](https://github.com/fengzyz/studynotes/raw/master/images/cgi.png)

当Web Server收到 index.php 这个请求后，会启动对应的 CGI 程序，这里就是PHP的解析器。接下来PHP解析器会解析php.ini文件，初始化执行环境，然后处理请求，再以规定CGI规定的格式返回处理后的结果，退出进程，Web server再把结果返回给浏览器。这就是一个完整的动态PHP Web访问流程，接下来再引出这些概念，就好理解多了，

- CGI：是 Web Server 与 Web Application 之间数据交换的一种协议。
- FastCGI：同 CGI，是一种通信协议，但比 CGI 在效率上做了一些优化。同样，SCGI 协议与 FastCGI 类似。
- PHP-CGI：是 PHP （Web Application）对 Web Server 提供的 CGI 协议的接口程序。
- PHP-FPM：是 PHP（Web Application）对 Web Server 提供的 FastCGI 协议的接口程序，额外还提供了相对智能一些任务管理。
WEB 中，

- Web Server 一般指Apache、Nginx、IIS、Lighttpd、Tomcat等服务器，
- Web Application 一般指PHP、Java、Asp.net等应用程序。
## Module方式
在了解 CGI 之前，我们先了解一下Web server 传递数据的另外一种方法：PHP Module加载方式。以 Apache 为例，在PHP Module方式中，是不是在 Apache 的配置文件 httpd.conf 中加上这样几句：
> 加入以下2句
> LoadModule php5_module D:/php/php5apache2_2.dll
>  AddType application/x-httpd-php .php
> 修改如下内容
>  <IfModule dir_module>
>      DirectoryIndex index.php index.html
> </IfModule>