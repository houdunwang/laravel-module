## 介绍

通过使用模块来管理大型Laravel项目。模块就像一个laravel包。这个包已经在 [HDCMS](http://www.hdcms.com) 中使用。

模块是在 [nwidart.com/laravel-modules](https://nwidart.com/laravel-modules/v3/advanced-tools/artisan-commands) 和  [laravel-permission](https://github.com/spatie/laravel-permission#installation)  组件基础上扩展了一些功能，所以需要先安装这两个组件。

> laravel-modules 和 laravel-permission 组件的功能都可以正常使用

## 安装

    composer require houdunwang/laravel-module
    
    php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
    
    composer require spatie/laravel-permission
    
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
    
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
    
    php artisan migrate

配置 composer.json 设置自动加载目录

    {
      "autoload": {
        "psr-4": {
          "App\\": "app/",
          "Modules\\": "Modules/"
        }
      }
    }

## 使用

### 创建模块

下面的命令是安装 `Admin` 模块

```
php artisan hd:module Admin
```
创建模块会同时执行以下操作：

* 生成 `menus.php` 配置文件
* 生成 `permission.php` 权限文件

### 模块配置

新建模块时系统会自动创建配置，一般情况下不需要执行以下命令生成配置文件（除组件添加新配置功能外）

```
php artisan hd:config Admin
```

**文件说明**

* config——基础配置，用于配置模块中文描述等信息
* permission.php——权限设置
* menus.php——后台管理菜单

### 模块菜单

系统会根据模块配置文件 `menus.php` 生成后台菜单项

**获取菜单**

获取系统可使用的所有菜单，以集合形式返回数据。

```
app('hd-menu')->all();
```

### 模块权限

首先需要安装 [laravel-permission](https://github.com/spatie/laravel-permission#installation) 组件，安装方式在上面已经介绍。

**设置权限**

系统根据 `Admin` 模块配置文件 `permission.php` 重新生成权限，执行以下命令会删除原有模块权限并重建。

```
php artisan hd:permission Admin
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
