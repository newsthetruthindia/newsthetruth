<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_name',
        'create_user',
        'edit_user',
        'view_user',
        'delete_user',
        'create_post',
        'edit_post',
        'delete_post',
        'publish_post',
        'review_post',
        'set_post_priority',
        'edit_post_comment',
        'add_post_comment',
        'edit_others_post_comment',
        'delete_post_comment',
        'delete_others_post_comment',
        'create_gallery',
        'update_gallery',
        'delete_gallery',
        'create_category',
        'edit_category',
        'delete_category',
        'create_tag',
        'edit_tag',
        'delete_tag',
        'parmanant_delete',
    ];

    public function user(){
        return $this->belongsTo( User::class );
    }
    public function permission(){
        return $this->belongsTo( RolePermission::class );
    }
}
