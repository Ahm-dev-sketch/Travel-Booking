<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_fillable_attributes()
    {
        $fillable = ['name', 'whatsapp_number', 'password', 'role'];
        $this->assertEquals($fillable, (new User)->getFillable());
    }

    public function test_user_has_hidden_attributes()
    {
        $hidden = ['password', 'remember_token'];
        $this->assertEquals($hidden, (new User)->getHidden());
    }
}
