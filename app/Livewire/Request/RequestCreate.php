<?php

namespace App\Livewire\Request;

use App\Models\Request;
use App\Models\RequestType;
use Livewire\Component;

class RequestCreate extends Component {
    public $requestTypeId;
    public $target_date;
    public $payload = [];

    public function getRequestTypesProperty() {
        return RequestType::active()->orderBy( 'id' )->get();
    }

    public function getSelectedTypeProperty() {
        return RequestType::find( $this->requestTypeId );
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