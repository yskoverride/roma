<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationComponent extends Component
{
    public $message;
    public $type;
    public $display = false;

    protected $listeners = ['displayNotification'];

    public function displayNotification($message, $type = 'success')
    {
        
        $this->message = $message;
        $this->type = $type;
        $this->display = true;

        $this->dispatchBrowserEvent('notification:display', ['duration' => 3000]);
    }

    public function closeNotification()
    {
        $this->reset(['message', 'type']);
    }

    public function render()
    {
        return view('livewire.notification-component');
    }
}
