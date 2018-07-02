<?php

namespace Houdunwang\Module\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleMenuGroup extends Model
{
    protected $fillable = ['title', 'icon', 'permission','module'];

    public function menus()
    {
        return $this->hasMany(ModuleMenu::class);
    }
}
