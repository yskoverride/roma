<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class DoctorComponent extends Component
{
    protected $doctors;
    public $name;
    public $email;
    public $phone;
    public $doctorId;
    private $password;
    public $action = 'create';

    use WithPagination;

    public function render()
    {
        
        $this->doctors = User::paginate(10);
        return view('livewire.doctor-component', ['doctors' => $this->doctors]);
    }

    public function generateRandomPassword()
    {
        return Str::random(12);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->action = 'create';
    }

    public function store()
    {
        
        $password = $this->generateRandomPassword();
        
        $validatedData = $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($password),
        ]);

        event(new Registered($user));

        $this->resetInputFields();

        $this->emit('closeModal');
        $this->emitTo('notification-component', 'displayNotification', 'Doctor successfully created!');
    }


    public function edit($id)
    {
                
        $doctor = User::findOrFail($id);

        $this->doctorId = $doctor->id;
        $this->name = $doctor->name;
        $this->email = $doctor->email;
        $this->phone = $doctor->phone;

        $this->resetErrorBag();

        $this->action = 'update';
    }

    public function update()
    {
        
        $this->validate([
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->doctorId),
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($this->doctorId),
            ],
        ]);
    
        $doctor = User::find($this->doctorId);

        $doctor->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    
        $this->resetInputFields();

        $this->emit('closeModal');

        $this->emitTo('notification-component', 'displayNotification', 'Doctor successfully updated!');

    }

    public function delete($id)
    {
        User::find($id)->delete();
        
        $this->emitTo('notification-component', 'displayNotification', 'Doctor successfully deleted!');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
    }
}
