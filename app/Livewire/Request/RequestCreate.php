<?php

namespace App\Livewire\Request;

use App\Models\AttendanceRecord;
use App\Models\Request;
use App\Models\RequestType;
use App\Services\DateRuleService;
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

        $rule = $type->date_rule ?? [];

        $this->allowedDates = DateRuleService::generateAllowedDates(
            $rule,
            auth()->id()
        );

        $this->dispatch( 'updateAllowedDates', dates: $this->allowedDates );
    }

    public function submit() {
        try {
            $rules = [
                'requestTypeId' => 'required|exists:request_types,id',
                'target_date' => 'required|date',
            ];

            if ( $this->selectedType?->payload_schema ) {
                foreach ( $this->selectedType->payload_schema[ 'fields' ] as $field ) {

                    $fieldRules = [];

                    if ( !empty( $field[ 'required' ] ) ) {
                        $fieldRules[] = 'required';
                    } else {
                        $fieldRules[] = 'nullable';
                    }

                    switch ( $field[ 'type' ] ) {
                        case 'time':
                        $fieldRules[] = 'date_format:H:i';
                        break;
                        case 'date':
                        $fieldRules[] = 'date';
                        break;
                        case 'text':
                        case 'textarea':
                        $fieldRules[] = 'string';
                        break;
                        case 'boolean':
                        $fieldRules[] = 'boolean';
                        break;
                    }

                    $rules[ "payload.{$field['name']}" ] = implode( '|', $fieldRules );
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

        } catch ( \Illuminate\Validation\ValidationException $e ) {
            $this->dispatch( 'processing-completed' );
            throw $e;
        }
    }

    public function render() {
        return view( 'livewire.pages.request.request-create' )->layout( 'layouts.app' );
    }
}