## 组件介绍

通过使用模块来管理大型Laravel项目，模块就像一个laravel包非常方便的进行添加或移除。

这个包已经在 [HDCMS](http://www.hdcms.com) 中使用。

模块是在 [nwidart.com/laravel-modules](https://nwidart.com/laravel-modules/v3/advanced-tools/artisan-commands) 和  [laravel-permission](https://github.com/spatie/laravel-permission#installation)  组件基础上扩展了一些功能，所以需要先安装这两个组件。

> laravel-modules 和 laravel-permission 组件的功能都可以正常使用

## 安装组件

    composer require houdunwang/laravel-module
    
    php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
    
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
    
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
    
    php artisan vendor:publish --provider="Houdunwang\Module\LaravelServiceProvider"
    
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

## 基础知识

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

**获取配置**

下面是获取 Admin/config/config.php 文件中的name值，支持 `.` 从深度嵌套的数组中检索值。

```
\HDModule::config('admin.config.name')
```

## 后台菜单

系统会根据模块配置文件 `menus.php` 生成后台菜单项

当 menus.php 文件不存在时，执行 `php artisan hd:config Admin` 系统会为模块 Admin 创建菜单。

**获取菜单**

获取系统可使用的所有菜单，以集合形式返回数据。可用于后台显示菜单列表。

```
\HDModule::getMenus()
```

## 权限管理

首先需要安装 [laravel-permission](https://github.com/spatie/laravel-permission#installation) 组件，安装方式在上面已经介绍。

### 创建权限配置

系统根据 `Admin` 模块配置文件 `permission.php` 重新生成权限，执行以下命令会创建权限配置文件。

```
php artisan hd:permission Admin
```

不指定模块时生成所有模块的权限表

```
php artisan hd:permission
```

> 文件存在时不会覆盖

生成的配置文件结构如下：

```
<?php return [
    [
        'group' => '文章管理',
        'permissions' => [
            ['title' => '添加栏目', 'name' => 'Modules\Admin\Http\Controllers\CategoryController@create', 'guard' => 'admin'],
        ],
    ],
];
```

name 指用于验证时的 `权限标识` ，可以使用任何字符定义。如果以 `控制器@方法` 形式定义的，在使用中间件验证时会比较容易。

### 获取权限配置

根据 `guard` 获取权限数据，可用于后台配置设置表单。

```
\HDModule::getPermissionByGuard('admin');
```

### 中间件

 [laravel-permission](https://github.com/spatie/laravel-permission#using-a-middleware) 组件提供了中间件功能，但处理不够灵活并对资源控制器支持不好。所以`houdunwang/laravel-module` 组件提供了中间件的功能扩展，你也可以使用  [laravel-permission](https://github.com/spatie/laravel-permission#installation)  中间件的所有功能。

以下都是对 `houdunwang/laravel-module`扩展中间件的说明，[laravel-permission](https://github.com/spatie/laravel-permission#using-a-middleware) 中间件使用请查看组件手册。

使用中间件路由需要模块 `permission.php` 配置文件中的权限标识为 `控制器@方法`形式。

### 配置

在 `app/Http/Kernel.php` 文件的 `$routeMiddleware` 段添加中间件

```
protected $routeMiddleware = [
	...
	'permission'    => \Houdunwang\Module\Middlewares\PermissionMiddleware::class,
	...
];
```



#### 站长特权

配置文件 `config/hd_module.php` 文件中定义站长使用的角色。

```
'webmaster' => 'webmaster'
```

在使用中间件验证时，如果不前用户所在角色为站长角色，系统不进行验证直接放行。

#### 普通路由

系统根据控制器方法检查是否存在权限规则，然后自动进行验证。

所以必须正确设置路由配置文件，下面是对编辑文章的权限设置

```
<?php
#config/permisson.php
return [
    [
        'group'       => '文章管理',
        'permissions' => [
            ['title' => '编辑管理', 'name' => 'Modules\Admin\Http\Controllers\ArticleController@edit', 'guard' => 'admin'],
        ],
    ],
];
```

下面是编辑文章的路由定义，必须保存 `Modules\Admin\Http\Controllers\ArticleController@edit` 规则已经在权限配置文件中定义，否则系统不验证。

```
Route::group([
    'middleware' => ['web', 'auth:admin'],'prefix'     => 'admin','namespace'  => 'Modules\Admin\Http\Controllers'], function () {
	Route::resource('edit_article', 'ArticleController@edit')->middleware("permission:admin");
});
```

上面的 `permission` 中间件的 `admin` 参数是权限 `guard`。

#### 资源路由

资源路由新增资源由 `create` 与 `store`方法完成，更新资源由 `edit` 与 `update` 方法完成。权限规则只需要设置 `create` 与 `edit` 方法即可，在执行 `store` 动作时系统会自动使用 `create` 方法规则，`update` 动作会使用 `create` 方法规则，下面是用户管理的资源控制器规则设置:

```
<?php
#config/permisson.php
return [
    [
        'group'       => '会员管理',
        'permissions' => [
            ['title' => '会员管理', 'name' => 'Modules\Admin\Http\Controllers\UserController@index', 'guard' => 'admin'],
            ['title' => '添加会员', 'name' => 'Modules\Admin\Http\Controllers\UserController@create', 'guard' => 'admin'],
            ['title' => '编辑会员', 'name' => 'Modules\Admin\Http\Controllers\UserController@edit', 'guard' => 'admin'],
            ['title' => '删除会员', 'name' => 'Modules\Admin\Http\Controllers\UserController@destory', 'guard' => 'admin'],
            ['title' => '查看会员', 'name' => 'Modules\Admin\Http\Controllers\UserController@show', 'guard' => 'admin'],
        ],
    ],
];
```

资源路由中间件的使用

```
Route::resource('role', 'RoleController')->middleware("permission:admin,resource");
```

上面的 `permission` 中间件的 `admin` 参数是权限 `guard`，中间件 permission 的第二个参数 `resource` 表示这是一个资源路由验证。

## 模块方法

获取当前请求使用的模块名

```
\HDModule::currentModule()
```

验证权限如果用户是站长直接放行

```
\HDModule::hadPermission()
```

## 自动化构建

大部分业务由 Controller控制器、Request请求难、Model模型、View视图、Handle处理器构成，很多时间这些工作都是重复的，系统支持通过一行命令生成业务需要的大部功能。

生成工作是根据模型和数据表完成的，所以必须先创建模型在数据库中创建模型表。

### 创建模型和迁移

执行以下命令系统会为 Article 模块创建 Category模型和对应的数据迁移文件。

```
php artisan hd:model Category Article
```

### 执行自动化构建

完成上面的模型与数据表创建后，执行以下命令系统将自动生成基础业务框架。

下面是根据 Article 模块的 Category 模型生成业务框架，系统同时会创建模型表单处理器，请查看 https://github.com/houdunwang/laravel-view 学习。

```
php artisan hd:build Category Article
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
