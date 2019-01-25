<?php

namespace Egysatria\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
	protected $table 	= "users";
	protected $fillable = [
		'users_name',
		'email_users',
		'password_users',
	];
}