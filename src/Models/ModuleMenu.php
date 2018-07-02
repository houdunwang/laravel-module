<?php

namespace Houdunwang\Module\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleMenu extends Model
{
    protected $fillable = ['title', 'icon', 'permission', 'module'];
}
