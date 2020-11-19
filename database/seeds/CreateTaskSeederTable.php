<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\Task;
class CreateTaskSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task1 = Task::create(['title'=>'Title 1','start_date'=>'2020-11-21','finish_date'=>'2020-11-25',
        'user_id'=>4,'group_id'=>1,'content'=>'Content 1'
    ]);
    }
}
