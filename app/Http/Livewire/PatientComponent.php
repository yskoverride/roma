<?php

namespace App\Http\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class PatientComponent extends Component
{
    protected $patients;
    public $name;
    public $attendant_name;
    public $phone;
    public $patientId;
    public $action = 'create';

    use WithPagination;

    public function render()
    {
        
        $this->patients = Patient::paginate(10);
        return view('livewire.patient-component', ['patients' => $this->patients]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->action = 'create';
    }

    public function store()
    {
                
        $validatedData = $this->validate([
            'name' => 'required|max:255',
            'attendant_name' => 'required|max:255',
            'phone' => 'required|numeric|phone:mobile|unique:patients,phone',
        ]);

        Patient::create([
            'name' => $validatedData['name'],
            'attendant_name' => $validatedData['attendant_name'],
            'phone' => $validatedData['phone'],
        ]);

        $this->resetInputFields();

        $this->emit('closeModal');
        $this->emitTo('notification-component', 'displayNotification', 'Patient successfully created!');
    }


    public function edit($id)
    {
                
        $patient = Patient::findOrFail($id);

        $this->patientId = $patient->id;
        $this->name = $patient->name;
        $this->attendant_name = $patient->attendant_name;
        $this->phone = $patient->phone;

        $this->resetErrorBag();

        $this->action = 'update';
    }

    public function update()
    {
        
        $this->validate([
            'name' => 'required|max:255',
            'attendant_name' => 'required|max:255',
            'phone' => [
                'required',
                'numeric',
                'phone:mobile',
                Rule::unique('patients', 'phone')->ignore($this->patientId),
            ],
        ]);
    
        $patient = Patient::find($this->patientId);

        $patient->update([
            'name' => $this->name,
            'attendant_name' => $this->attendant_name,
            'phone' => $this->phone,
        ]);
    
        $this->resetInputFields();

        $this->emit('closeModal');

        $this->emitTo('notification-component', 'displayNotification', 'Patient successfully updated!');

    }

    public function delete($id)
    {
        Patient::find($id)->delete();
        
        $this->emitTo('notification-component', 'displayNotification', 'Patient successfully deleted!');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->attendant_name = '';
        $this->phone = '';
    }
}