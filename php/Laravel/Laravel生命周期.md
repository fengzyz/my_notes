# Laravel的生命周期
## 概述
Laravel 的生命周期从public\index.php开始，从public\index.php结束。

![image](https://github.com/fengzyz/studynotes/raw/master/images/1.png)

这么说有点草率，但事实确实如此。下面是public\index.php的全部源码（Laravel源码的注释是最好的Laravel文档），更具体来说可以分为四步：
```php
    define('LARAVEL_START', microtime(true));
    require __DIR__.'/../bootstrap/autoload.php';
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Illuminate\Http\Request::capture());   
    $response->send();
    
    $kernel->terminate($request, $response);
```    
这四步详细的解释是：
1. 注册加载composer自动生成的class loader，包括所有你composer require的依赖（对应代码1）

2. 生成容器Container，Application实例，并向容器注册核心组件（HttpKernel，ConsoleKernel，ExceptionHandler）（对应代码2，容器很重要，后面详细讲解）。

3. 处理请求，生成并发送响应（对应代码3，毫不夸张的说，你99%的代码都运行在这个小小的handle方法里面）。

4. 请求结束，进行回调（对应代码4，还记得可终止中间件吗？没错，就是在这里回调的）。

### 启动Laravel基础服务 

我们不妨再详细一点：
第一步注册加载composer自动生成的class loader就是加载初始化第三方依赖，不属于Laravel核心，到此为止。
第二步生成容器Container，并向容器注册核心组件，这里牵涉到了容器Container和合同Contracts，这是Laravel的重点，下面将详细讲解。
重点是第三步处理请求，生成并发送响应。
首先Laravel框架捕获到用户发到public\index.php的请求，生成Illuminate\Http\Request实例，传递给这个小小的handle方法。在方法内部，将该$request实例绑定到第二步生成的$app容器上。让后在该请求真正处理之前，调用bootstrap方法，进行必要的加载和注册，如检测环境，加载配置，注册Facades（假象），注册服务提供者，启动服务提供者等等。这是一个启动数组，具体在Illuminate\Foundation\Http\Kernel中，包括：