<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {

        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'Admin']);
        $journalistRole = \App\Models\Role::firstOrCreate(['name' => 'Journalist']);
        $readerRole = \App\Models\Role::firstOrCreate(['name' => 'Reader']);


        $law = \App\Models\Category::firstOrCreate(['name' => 'Закон']);
        $health = \App\Models\Category::firstOrCreate(['name' => 'Здравоохранение']);
        $tragik = \App\Models\Category::firstOrCreate(['name' => 'Трагик']);
        $hrono = \App\Models\Category::firstOrCreate(['name' => 'Хронология']);


        $admin = \App\Models\User::firstOrCreate(
            ['login' => 'admin'],
            [
                'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                'role_id' => $adminRole->id
            ]
        );

        if (!$admin->role_id) {
            $admin->role_id = $adminRole->id;
            $admin->save();
        }


        \App\Models\Text::firstOrCreate(
            ['heading' => 'Исполнитель'],
            ['text' => '«Смерть — лучший лекарь...»', 'category_id' => $law->id, 'user_id' => $admin->id]
        );
        \App\Models\Text::firstOrCreate(
            ['heading' => 'О вечном'],
            ['text' => '«В Управе напомнили...»', 'category_id' => $health->id, 'user_id' => $admin->id]
        );
        \App\Models\Text::firstOrCreate(
            ['heading' => 'Отражение'],
            ['text' => '«Ты так отчаянно ищешь смысл...»', 'category_id' => $tragik->id, 'user_id' => $admin->id]
        );
        \App\Models\Text::firstOrCreate(
            ['heading' => 'Хроника'],
            ['text' => '«Здесь умирают не от язвы...»', 'category_id' => $hrono->id, 'user_id' => $admin->id]
        );
    }
}
