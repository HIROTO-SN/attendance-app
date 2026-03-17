<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\HolidayCalendar;

class DateRuleService
 {
    public static function generateAllowedDates( array $rule, int $userId ): array
 {
        if ( empty( $rule ) ) {
            return [];
        }
        $start = now();
        $end = now();
        $today = now();

        // 過去範囲
        if ( !empty( $rule[ 'before' ][ 'value' ] ) ) {

            $start = match ( $rule[ 'before' ][ 'unit' ] ) {
                'day' => $today->copy()->subDays( $rule[ 'before' ][ 'value' ] ),
                'week' => $today->copy()->subWeeks( $rule[ 'before' ][ 'value' ] ),
                'month' => $today->copy()->subMonths( $rule[ 'before' ][ 'value' ] ),
                default => $today->copy(),
            }
            ;

        } else {

            // 未入力 → 月初
            $start = $today->copy()->startOfMonth();
        }

        // 未来範囲
        if ( !empty( $rule[ 'after' ][ 'value' ] ) ) {

            $end = match ( $rule[ 'after' ][ 'unit' ] ) {
                'day' => $today->copy()->addDays( $rule[ 'after' ][ 'value' ] ),
                'week' => $today->copy()->addWeeks( $rule[ 'after' ][ 'value' ] ),
                'month' => $today->copy()->addMonths( $rule[ 'after' ][ 'value' ] ),
                default => $today->copy(),
            }
            ;

        } else {

            // 未入力 → 月末
            $end = $today->copy()->endOfMonth();
        }

        // 打刻取得（まとめて）
        $attendanceDates = AttendanceRecord::where( 'user_id', $userId )
        ->pluck( 'work_date' )
        ->map( fn( $d ) => $d->format( 'Y-m-d' ) )
        ->toArray();

        // 祝日取得（まとめて）
        $holidayDates = HolidayCalendar::whereBetween( 'holiday_date', [ $start, $end ] )
        ->pluck( 'holiday_date' )
        ->map( fn( $d ) => $d->format( 'Y-m-d' ) )
        ->toArray();

        $weekdayMap = [
            'sun' => 0,
            'mon' => 1,
            'tue' => 2,
            'wed' => 3,
            'thu' => 4,
            'fri' => 5,
            'sat' => 6,
        ];

        $allowedWeekdays = collect( $rule[ 'weekdays' ] ?? [] )
        ->map( fn( $w ) => $weekdayMap[ $w ] );

        $dates = [];

        for ( $date = $start->copy();
        $date->lte( $end );
        $date->addDay() ) {

            $dateStr = $date->format( 'Y-m-d' );

            // 曜日チェック
            if ( $allowedWeekdays->isNotEmpty() ) {
                if ( !$allowedWeekdays->contains( $date->dayOfWeek ) ) {
                    continue;
                }
            }

            // 祝日チェック
            if ( !( $rule[ 'allow_holiday' ] ?? true ) ) {
                if ( in_array( $dateStr, $holidayDates ) ) {
                    continue;
                }
            }

            // 打刻条件
            $source = $rule[ 'source' ] ?? 'any';

            if ( $source === 'worked_only' ) {
                if ( !in_array( $dateStr, $attendanceDates ) ) {
                    continue;
                }
            }

            if ( $source === 'non_worked_only' ) {
                if ( in_array( $dateStr, $attendanceDates ) ) {
                    continue;
                }
            }

            $dates[] = $dateStr;
        }

        return $dates;
    }
}