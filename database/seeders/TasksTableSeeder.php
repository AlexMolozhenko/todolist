<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $users = null;
    public $task = null;
    public function run()
    {
        $this->users = User::all();
        foreach ($this->users as $user) {

            for ($i = 0; $i < 5; $i++) {
                 $this->task = Task::create([
                    'title' => "Task {$i} for {$user->name}".fake()->city,
                    'description' => "Description for task {$i} of {$user->name}".fake()->city,
                    'status' => 'todo',
                    'priority' => rand(1, 5),
                    'user_id' => $user->id,
                ]);

                    for($j = 0;$j < 3;$j++){
                        Task::create([
                            'title' => "Task {$i} for {$user->name}".fake()->city,
                            'description' => "Description for task {$i} of {$user->name}".fake()->city,
                            'status' => 'todo',
                            'priority' => rand(1, 5),
                            'user_id' => $user->id,
                            'parent_id' => $this->task->id,
                        ]);
                    }

            }
        }
    }
}
