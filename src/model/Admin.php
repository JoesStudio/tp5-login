<?php


namespace joeStudio\login\model;


use think\Model;
use traits\model\SoftDelete;

class Admin extends Model
{
    use SoftDelete;

    protected $field = true;

    protected $pk = 'admin_id';
    protected $delete_time = "delete_time";
}