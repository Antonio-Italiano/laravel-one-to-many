<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for($i = 0; $i < 5; $i++){
            $project = new Project();

            $project->title = $faker->text(15);
            $project->description = $faker->paragraphs(2, true);
            // $project->image = "https://picsum.photos/id/" . $faker->numberBetween(1, 50) . "/200";
            $project->slug = Str::slug($project->title, '-');
            $project->url = $faker->url();
            
            $project->save();
        }
    }
}
