## 介绍

通过使用模块来管理大型Laravel项目。模块就像一个laravel包。这个包已经在 [HDCMS](http://www.hdcms.com) 中使用。

模块是在 [nwidart.com/laravel-modules](https://nwidart.com/laravel-modules/v3/advanced-tools/artisan-commands) 组件基础上扩展了一些功能。

> nwidart.com/laravel-modules 组件的功能都可以正常使用

## 安装

    composer require houdunwang/laravel-modules
    php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"

配置 composer.json 设置自动加载目录

    {
      "autoload": {
        "psr-4": {
          "App\\": "app/",
          "Modules\\": "Modules/"
        }
      }
    }

## 命令
### 创建模块
```
php artisan hd:module Admin
```
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
