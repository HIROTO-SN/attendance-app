<?php

namespace App\Livewire\Request;

use App\Models\Request;
use Livewire\Component;

class RequestCreate extends Component {
    public string $type = '';
    public ?string $target_date = null;

    // 共通
    public string $reason = '';

    // 打刻修正用
    public ?string $start_time = null;
    public ?string $end_time = null;

    public function submit() {
        $this->validate( [
            'type' => 'required',
            'target_date' => 'required|date',
        ] );

        Request::create( [
            'user_id' => auth()->id(),
            'type' => $this->type,
            'target_date' => $this->target_date,
            'payload' => [
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'reason' => $this->reason,
            ],
            'status' => 'pending',
        ] );

        return redirect()->route( 'requests.index' );
    }

    public function render() {
        return view( 'livewire.pages.request.request-create' )->layout( 'layouts.app' );
    }
}