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
        
         $mainGroup1->users()->attach([1,2,3,4]);
         $mainGroup2= Group::create(['name'=>'Product']);
         $mainGroup2->users()->attach([1,2,3,4]);
         $mainGroup3= Group::create(['name'=>'Sale']);
          $mainGroup3->users()->attach([1,2,3,4]);
         $mainGroup4= Group::create(['name'=>'Accounting']);
         $mainGroup4->users()->attach([1,2,3,4]);
         $mainGroup5= Group::create(['name'=>'LeadeShip']);

         $mainGroup7= Group::create(['name'=>'QA']);

         
    }
}
