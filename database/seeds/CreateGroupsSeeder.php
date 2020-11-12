<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\Group;
class CreateGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $mainGroup1= Group::create(['name'=>'Marketting']);
         $mainGroup2= Group::create(['name'=>'Product']);

         $mainGroup3= Group::create(['name'=>'Sale']);
         $mainGroup4= Group::create(['name'=>'Accounting']);

         $mainGroup5= Group::create(['name'=>'LeadeShip']);

         $mainGroup7= Group::create(['name'=>'QA']);

         
    }
}
