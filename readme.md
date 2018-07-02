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

## 模块管理

### 创建模块

```
php artisan hd:module-install Admin
```
创建模块会同时执行以下操作：

* 生成 `menus.php` 配置文件
* 生成 `permission.php` 权限文件

### 安装模块

组件会创建 `modules` 数据表用于记录已经安装的模块，模块安装执行以下动作：

* 根据模块配置文件 `menus.php` 生成后台菜单
* 根据模块配置文件 `perssions.php` 生成模块权限
* 模块名称等信息被系统记录

下面是安装 `Admin` 模块安装到系统中

```
php artisan hd:module-install Admin
```

> 修改过 `menus.php` 等配置文件后，需要重新执行安装。

## 模块配置

新建模块时系统会自动创建配置，一般情况下不需要独立执行（除组件添加新配置功能外）

```
php artisan hd:config Admin
```

**文件说明**

* config——基础配置，用于配置模块中文描述等信息
* permission.php——权限设置
* menus.php——后台管理菜单

## 模块菜单

系统会根据模块配置文件 `menus.php` 生成后台菜单项

**创建模块菜单**

```
php artisan hd:menu-install Admin
```

## 模块权限

### 安装权限组件

权限操作依赖 [laravel-permission](https://github.com/spatie/laravel-permission#installation) 进行权限管理，所以首先需要安装。

```
composer require spatie/laravel-permission

#生成迁移文件，根据业务需要可以随意添加表字段
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"

php artisan migrate
```

> 认真检查每一步要安装正确，不要重复安装！会重复生成数据迁移文件，导致执行数据迁移出错。

### 设置权限

系统根据 `Admin` 模块配置文件 `permission.php` 重新生成权限，执行以下命令会删除原有模块权限并重建。

```
php artisan hd:permission Admin
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
