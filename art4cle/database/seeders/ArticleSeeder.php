<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $law = Category::create(['name' => 'Закон']);
        $health = Category::create(['name' => 'Здравоохранение']);
        $tragik = Category::create(['name' => 'Трагик']);
        $hrono = Category::create(['name' => 'Хронология']);

        Article::create([
            'title' => 'Исполнитель',
            'category_id' => $law->id,
            'text' => '«Смерть — лучший лекарь. Никаких рецептов, никаких очередей, а главное — стопроцентный результат без рецидивов».',
        ]);

        Article::create([
            'title' => 'О вечном',
            'category_id' => $health->id,
            'text' => '«В Управе напомнили, что смерть — это не конец, а просто самый надежный способ сэкономить на суточном рационе».',
        ]);

        Article::create([
            'title' => 'Отражение',
            'category_id' => $tragik->id,
            'text' => '«Ты так отчаянно ищешь смысл в наших словах, дурачок. Мы ведь просто эхо мыслей, которые ты побоялся додумать до конца».',
        ]);

        Article::create([
            'title' => 'Хроника',
            'category_id' => $hrono->id,
            'text' => '«Здесь умирают не от язвы и не от ножа. Здесь просто устают искать причину просыпаться по утрам».',
        ]);
    }
}
