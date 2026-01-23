<?php

namespace App\Livewire\Request;

use App\Models\Request;
use Livewire\Component;

class RequestIndex extends Component {
    public function render() {
        $requests = Request::where( 'user_id', auth()->id() )
        ->latest()
        ->get();
        return view( 'livewire.pages.request.request-index', compact( 'requests' ) )->layout( 'layouts.app' );
    }
}