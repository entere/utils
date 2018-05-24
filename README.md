#utils

## 安装

 安装包文件
  ```shell
  #composer require "entere/utils:dev-master"
  composer require "entere/utils:v0.1"
  ```

## 使用


Laravel5 实例：


```php
<?php namespace App\Http\Controllers;

use Entere\Utils;

class WelcomeController extends Controller {
    
    public function index()
    {
    	$params = ['name'=>'entere','age'=>4,'ip'=>'127.0.0.1'];
    	$key = 'entere';
        var_dump(Sign::getSign($params,$key));
    }
}
```

## License

MIT
