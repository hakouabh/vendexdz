<?php

namespace Database\Seeders;
use App\Models\Variable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   /*  public function run(): void
    {
        $variables = [
            [
                'key' => '1',
                'lable' => 'confirmed',
                'icon' => 'icons/confirmed.svg',
                'color' => 'bg-gradient-to-r from-green-400 to-emerald-500',
                'show' =>true,
            ],
            [
                'key' => '1',
                'lable' => 'pending',
                'icon' => 'icons/pending.svg',
                'color' => 'bg-gradient-to-r from-yellow-300 to-yellow-500',
                'type'=>   'default',
                'show' =>true,
            ],
             
             [
                'key' => '1',
                'lable' => 'canceled',
                'icon' => 'icons/canceled.svg',
                'color' => 'bg-gradient-to-r from-red-400 to-red-600',
                'show' =>true,
            ],
             [
                'key' => '1',
                'lable' => 'closed',
                'icon' => 'icons/closed.svg',
                'color' => 'bg-gradient-to-r from-gray-500 to-gray-700',
                'show' =>false,
            ],
             [
                'key' => '1',
                'lable' => 'faulse number',
                'icon' => 'icons/faulse_number.svg',
                'color' => 'bg-gradient-to-r from-pink-400 to-pink-600',
                'show' =>false,
            ],
            [
                'key' => '1',
                'lable' => 'wrong product',
                'icon' => 'icons/faulse.svg',
                'color' => 'bg-gradient-to-r from-rose-400 to-rose-600',
                'type'=>   'error',
                'show' =>true,
            ],
             [
                'key' => '1',
                'lable' => 'reported',
                'icon' => 'icons/reported.svg',
                'color' => 'bg-gradient-to-r from-orange-400 to-orange-600',
                'show' =>false,
            ],
             [
                'key' => '1',
                'lable' => 'not respond',
                'icon' => 'icons/respond.svg',
                'color' => 'bg-gradient-to-r from-indigo-400 to-indigo-600 ',
                'show' =>false,
            ],
            [
                'key' => '1',
                'lable' => 'NA/1',
                'icon' => 'icons/na.svg',
                'color' => 'bg-gradient-to-r from-blue-300 to-blue-500',
                'show' =>false,
            ],
             [
                'key' => '1',
                'lable' => 'NA/2',
                'icon' => 'icons/na.svg',
                'color' => 'bg-gradient-to-r from-cyan-400 to-cyan-600',
                'show' =>false,
            ],
            [
                'key' => '1',
                'lable' => 'NA/3',
                'icon' => 'icons/na.svg',
                'color' => 'bg-gradient-to-r from-teal-400 to-teal-600',
                'show' =>false,
            ],
            [
                'key' => '1',
                'lable' => 'NA/4',
                'icon' => 'icons/na.svg',
                'color' => 'bg-gradient-to-r from-purple-400 to-purple-600',
                'show' =>false,
            ],
            [
                'key' => '1',
                'lable' => 'NA/5',
                'icon' => 'icons/na.svg',
                'color' => 'bg-gradient-to-r from-lime-400 to-lime-600',
                'show' =>false,
            ],[
                'key' => '2',
                'lable' => 'In Preparation',
                'icon' => 'icons/pending.svg',
                'color' => 'bg-gradient-to-r from-yellow-300 to-yellow-500',
                'type' =>'default',
                'show' =>true,
            ],[
                'key' => '2',
                'lable' => 'In Process',
                'icon' => 'icons/in_delivery.svg',
                'color' => 'bg-gradient-to-r from-yellow-300 to-yellow-500',
                'show' =>true,
            ],[
                'key' => '2',
                'lable' => 'in delivery',
                'icon' => 'icons/in_delivery.svg',
                'color' => 'bg-gradient-to-r from-yellow-300 to-yellow-500',
                'show' =>true,
            ]
            ,[
                'key' => '2',
                'lable' => 'delivered',
                'icon' => 'icons/delivered.svg',
                'color' => 'bg-gradient-to-r from-blue-300 to-blue-500',
                'show' =>true,
            ],[
                'key' => '2',
                'lable' => 'delivered/payed',
                'icon' => 'icons/payed.svg',
                'color' => 'bg-gradient-to-r from-lime-400 to-lime-600',
                'show' =>true,
            ],[
                'key' => '2',
                'lable' => 'reported',
                'icon' => 'icons/reported.svg',
                'color' => 'bg-gradient-to-r from-orange-400 to-orange-600',
                'show' =>true,
            ],[
                'key' => '2',
                'lable' => 'return',
                'icon' => 'icons/return.svg',
                'color' => 'bg-gradient-to-r from-red-400 to-red-600',
                'show' =>false,
            ],['key'=>'2','lable'=>'At Office','icon'=>'icons/stop_desk.svg','color'=>'bg-gradient-to-r from-gray-300 to-gray-500','show'=>false],

            ['key'=>'2','lable'=>'SD - NA 1','icon'=>'icons/respond.svg','color'=>'bg-gradient-to-r from-red-400 to-red-600','show'=>false],
            ['key'=>'2','lable'=>'SD - NA 2','icon'=>'icons/respond.svg','color'=>'bg-gradient-to-r from-red-500 to-red-700','show'=>false],
            ['key'=>'2','lable'=>'SD - NA 3','icon'=>'icons/respond.svg','color'=>'bg-gradient-to-r from-red-600 to-red-800','show'=>false],

            ['key'=>'2','lable'=>'SD - Reported','icon'=>'icons/reported.svg','color'=>'bg-gradient-to-r from-orange-400 to-orange-600','show'=>false],
            ['key'=>'2','lable'=>'SD - Canceled 3x','icon'=>'icons/canceled.svg','color'=>'bg-gradient-to-r from-pink-400 to-pink-600','show'=>false],
            ['key'=>'2','lable'=>'SD - Waiting Client','icon'=>'icons/pending.svg','color'=>'bg-gradient-to-r from-yellow-400 to-yellow-600','show'=>false],

            ['key'=>'2','lable'=>'NA 1','icon'=>'icons/respond.svg','color'=>'bg-gradient-to-r from-red-400 to-red-600','show'=>false],
            ['key'=>'2','lable'=>'NA 2','icon'=>'icons/respond.svg','color'=>'bg-gradient-to-r from-red-500 to-red-700','show'=>false],
            ['key'=>'2','lable'=>'NA 3','icon'=>'icons/respond.svg','color'=>'bg-gradient-to-r from-red-600 to-red-800','show'=>false],

            ['key'=>'2','lable'=>'Canceled by Client','icon'=>'icons/canceled.svg','color'=>'bg-gradient-to-r from-pink-500 to-pink-700','show'=>false],
            ['key'=>'2','lable'=>'Canceled','icon'=>'icons/canceled.svg','color'=>'bg-gradient-to-r from-pink-500 to-pink-700','show'=>false],

            ['key'=>'2','lable'=>'Dispatcher','icon'=>'icons/dispatcher.svg','color'=>'bg-gradient-to-r from-indigo-400 to-indigo-600','show'=>false],
            ['key'=>'2','lable'=>'To be Retried','icon'=>'icons/to_be_retried.svg','color'=>'bg-gradient-to-r from-orange-500 to-orange-700','show'=>false],

            ['key'=>'2','lable'=>'Return to Courier','icon'=>'icons/return.svg','color'=>'bg-gradient-to-r from-red-500 to-red-700','show'=>false],
            ['key'=>'2','lable'=>'Return via Shuttle','icon'=>'icons/return.svg','color'=>'bg-gradient-to-r from-red-500 to-red-700','show'=>false],
            ['key'=>'2','lable'=>'Return from Dispatcher','icon'=>'icons/return.svg','color'=>'bg-gradient-to-r from-red-500 to-red-700','show'=>false],

            ['key'=>'2','lable'=>'A Relanced','icon'=>'icons/a_relaunched.svg','color'=>'bg-gradient-to-r from-purple-400 to-purple-600','show'=>false],
            [
                'key' => '3',
                'lable' => 'home',
                'icon' => 'icons/home.svg',
                'color' => 'bg-gradient-to-r from-purple-400 to-purple-600',
                'type' =>'default',
                'show' =>false,
            ],
            [
                'key' => '3',
                'lable' => 'stop desk',
                'icon' => 'icons/stop_desk.svg',
                'color' => 'bg-gradient-to-r from-lime-400 to-lime-600',
                'show' =>false,
            ],
            [
                'key' => '1',
                'lable' => 'conf-repor',
                'icon' => 'icons/pending.svg',
                'color' => 'bg-gradient-to-r from-blue-300 to-blue-500',
                'show' =>false,
            ],
            
                  ];

        foreach ($variables as $var) {
            Variable::create($var);
        }
    }*/
}
