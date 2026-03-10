<?php

namespace App\Livewire\Request;

use App\Models\AttendanceRecord;
use App\Models\Request;
use App\Models\RequestType;
use App\Models\Shift;
use Livewire\Attributes\On;
use Livewire\Component;

class RequestCreate extends Component {
    public $requestTypeId;
    public $target_date;
    public $payload = [];
    public $allowedDates = [];

    public function getRequestTypesProperty() {
        return RequestType::active()->orderBy( 'id' )->get();
    }

    public function getSelectedTypeProperty() {
        return RequestType::find( $this->requestTypeId );
    }

    #[ On( 'setTargetDate' ) ]

    public function setTargetDate( $date ) {
        $this->target_date = $date;
    }

    public function updatedRequestTypeId( $value ) {
        if ( !$value ) {
            $this->allowedDates = [];
            return;
        }
        $type = RequestType::find( $value );
        if ( $type->code === 'punch_fix' ) {
            $this->allowedDates = AttendanceRecord::where( 'user_id', auth()->id() )
            ->pluck( 'work_date' )
            ->map( fn( $d ) => $d->format( 'Y-m-d' ) )
            ->toArray();
        } else {
            $this->allowedDates = [];
        }
        $this->dispatch( 'updateAllowedDates', dates: $this->allowedDates );
    }

    public function submit() {
        $rules = [
            'requestTypeId' => 'required|exists:request_types,id',
            'target_date' => 'required|date',
        ];

        if ( $this->selectedType?->payload_schema ) {
            foreach ( $this->selectedType->payload_schema[ 'fields' ] as $field ) {
                if ( !empty( $field[ 'required' ] ) ) {
                    $rules[ "payload.{$field['name']}" ] = 'required';
                }
            }
        }

        $this->validate( $rules );

        Request::create( [
            'user_id' => auth()->id(),
            'request_type_id' => $this->requestTypeId,
            'target_date' => $this->target_date,
            'payload' => $this->payload,
            'status' => 'pending',
        ] );

        return redirect()->route( 'requests.index' );
    }

    public function render() {
        return view( 'livewire.pages.request.request-create' )->layout( 'layouts.app' );
    }
}