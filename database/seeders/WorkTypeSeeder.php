<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder {
    /**
    * Run the database seeds.
    */

    public function run(): void {
        DB::table( 'work_types' )->insert( [
            [ 'id'=>1, 'code'=>'fixed', 'name'=>'固定労働制', 'description'=>'毎日同じ開始・終了時刻で勤務する通常の会社員勤務形態' ],
            [ 'id'=>2, 'code'=>'flex', 'name'=>'フレックスタイム制（コアあり）', 'description'=>'コアタイム内の在席が必須。始業・終業時刻は従業員が自由に決定' ],
            [ 'id'=>3, 'code'=>'super_flex', 'name'=>'フレックスタイム制（コアなし）', 'description'=>'コアタイムなし。労働時間のみで管理するスーパーフレックス' ],
            [ 'id'=>4, 'code'=>'short_time', 'name'=>'時短勤務', 'description'=>'育児・介護等により所定労働時間を短縮した固定勤務' ],
            [ 'id'=>5, 'code'=>'variable', 'name'=>'変形労働時間制', 'description'=>'週または月単位で労働時間を調整する勤務形態' ],
            [ 'id'=>6, 'code'=>'discretionary', 'name'=>'裁量労働制', 'description'=>'実働にかかわらず、みなし労働時間で管理する勤務形態' ],
            [ 'id'=>7, 'code'=>'manager', 'name'=>'管理監督者', 'description'=>'労働時間管理の対象外となる管理職' ],
        ] );
    }
}