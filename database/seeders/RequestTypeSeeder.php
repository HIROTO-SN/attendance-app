<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestType;

class RequestTypeSeeder extends Seeder {
    public function run(): void {
        $types = [

            // ===  ===  ===  ===  ===  ===  ===  ===  =
            // 打刻・勤務時間系
            // ===  ===  ===  ===  ===  ===  ===  ===  =

            [
                'code' => 'punch_fix',
                'name' => '打刻修正申請',
                'description' => '打刻漏れ・打刻誤りを修正するための申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'before_clock_in', 'type' => 'time', 'label' => '修正前 出勤時刻', 'required' => true ],
                        [ 'name' => 'before_clock_out', 'type' => 'time', 'label' => '修正前 退勤時刻', 'required' => true ],
                        [ 'name' => 'after_clock_in', 'type' => 'time', 'label' => '修正後 出勤時刻', 'required' => true ],
                        [ 'name' => 'after_clock_out', 'type' => 'time', 'label' => '修正後 退勤時刻', 'required' => true ],
                        [ 'name' => 'reason', 'type' => 'textarea', 'label' => '修正理由', 'required' => true ],
                    ],
                ],
            ],

            [
                'code' => 'overtime',
                'name' => '残業申請',
                'description' => '所定労働時間を超えて勤務する場合の申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'start_time', 'type' => 'time', 'label' => '残業開始時刻', 'required' => true ],
                        [ 'name' => 'end_time', 'type' => 'time', 'label' => '残業終了時刻', 'required' => true ],
                        [ 'name' => 'reason', 'type' => 'textarea', 'label' => '残業理由', 'required' => true ],
                    ],
                ],
            ],

            [
                'code' => 'holiday_work',
                'name' => '休日出勤申請',
                'description' => '休日に勤務する場合の申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'work_start', 'type' => 'time', 'label' => '勤務開始時刻', 'required' => true ],
                        [ 'name' => 'work_end', 'type' => 'time', 'label' => '勤務終了時刻', 'required' => true ],
                        [ 'name' => 'reason', 'type' => 'textarea', 'label' => '理由', 'required' => true ],
                    ],
                ],
            ],

            // ===  ===  ===  ===  ===  ===  ===  ===  =
            // 休暇系
            // ===  ===  ===  ===  ===  ===  ===  ===  =

            [
                'code' => 'paid_leave',
                'name' => '有給休暇申請',
                'description' => '年次有給休暇を取得するための申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'from_date', 'type' => 'date', 'label' => '開始日', 'required' => true ],
                        [ 'name' => 'to_date', 'type' => 'date', 'label' => '終了日', 'required' => true ],
                        [ 'name' => 'is_half_day', 'type' => 'boolean', 'label' => '半休', 'required' => false ],
                        [ 'name' => 'reason', 'type' => 'textarea', 'label' => '理由', 'required' => false ],
                    ],
                ],
            ],

            [
                'code' => 'special_leave',
                'name' => '特別休暇申請',
                'description' => '慶弔休暇などの特別休暇を申請するための申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'leave_type', 'type' => 'text', 'label' => '休暇区分', 'required' => true ],
                        [ 'name' => 'from_date', 'type' => 'date', 'label' => '開始日', 'required' => true ],
                        [ 'name' => 'to_date', 'type' => 'date', 'label' => '終了日', 'required' => true ],
                        [ 'name' => 'reason', 'type' => 'textarea', 'label' => '理由', 'required' => true ],
                    ],
                ],
            ],

            [
                'code' => 'absence',
                'name' => '欠勤申請',
                'description' => 'やむを得ず欠勤する場合の申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'date', 'type' => 'date', 'label' => '欠勤日', 'required' => true ],
                        [ 'name' => 'reason', 'type' => 'textarea', 'label' => '理由', 'required' => true ],
                    ],
                ],
            ],

            // ===  ===  ===  ===  ===  ===  ===  ===  =
            // 勤務形態・その他
            // ===  ===  ===  ===  ===  ===  ===  ===  =

            [
                'code' => 'remote_work',
                'name' => '在宅勤務申請',
                'description' => '在宅勤務を行う場合の申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'date', 'type' => 'date', 'label' => '対象日', 'required' => true ],
                        [ 'name' => 'location', 'type' => 'text', 'label' => '勤務場所', 'required' => false ],
                        [ 'name' => 'reason', 'type' => 'textarea', 'label' => '理由', 'required' => false ],
                    ],
                ],
            ],

            [
                'code' => 'business_trip',
                'name' => '出張申請',
                'description' => '出張を行う場合の申請',
                'payload_schema' => [
                    'fields' => [
                        [ 'name' => 'destination', 'type' => 'text', 'label' => '出張先', 'required' => true ],
                        [ 'name' => 'from_date', 'type' => 'date', 'label' => '開始日', 'required' => true ],
                        [ 'name' => 'to_date', 'type' => 'date', 'label' => '終了日', 'required' => true ],
                        [ 'name' => 'purpose', 'type' => 'textarea', 'label' => '出張目的', 'required' => true ],
                    ],
                ],
            ],

        ];

        foreach ( $types as $type ) {
            RequestType::updateOrCreate(
                [ 'code' => $type[ 'code' ] ],
                $type
            );
        }
    }
}