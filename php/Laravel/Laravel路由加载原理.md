# Laravel路由加载原理
---
路由-是Laravel中很重要的一个主题，几乎所有框架都有路由的概念，简单讲就是将用户请求分配给对应的程序处理
## 路由加载原理
Laravel中的路由定义在routes 目录下的web.php 路由配置文件，我们知道在 Laravel 中，所有的服务都是通过「服务提供者」的 register 方法绑定到「Laralvel 服务容器」中，
之后才可以在Laravel中使用。

到这里我们自然会想到，加载路由文件任务本质是一种服务，它实现的功能是将路由文件中定义的路由加载到 Laravel 内核中，
然后再去匹配正确的路由并处理 HTTP 请求。所以，这里我们应该查找到与路由有关的「服务提供者」去注册和启动路由相关服务。

现在让我们到 config/app.php 配置文件中的 providers 节点去查找与路由相关的「服务提供者」，没错就是 App\Providers\RouteServiceProvider::class 类。
> 提示：有关「服务提供者」的运行原理，你可以阅读「Laravel 中服务提供者实现原理」一文，这里深入讲解「服务提供者」
  注册和启动原理。
  
这里有必要简单介绍下「服务提供者」的加载和执行过程：
1. 首先，HTTP 内核程序会去执行所有「服务提供者」 register 方法，将所有的服务注册到服务容器内，这里的注册指的是将服务绑定（bind）到容器；
2. 当所有「服务提供者」注册完后，会执行已完成注册「服务提供者」的 boot 方法启动服务。

「服务提供者」的注册和启动处理由 Illuminate\Foundation\Http\Kernel 这个 HTTP 内核程序完成。

了解完「服务提供者」的基本概念后，我们不难知道 RouteServiceProvider 路由提供者服务，同样由 注册（register） 和 启动（boot） 这两个处理去完成服务加载工作。
  
## 深入 RouteServiceProvider 服务提供者

 
 




