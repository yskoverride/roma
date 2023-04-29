<?php

namespace App\Http\Livewire;

use App\Models\Patient;
use Livewire\Component;

class SearchablePatientDropdown extends Component
{
    
    public $search = '';
    protected $patientId;
    public $patient;

    public $state = [
        'name' => '',
        'attendant_name' => '',
        'phone' => ''
    ];
    
    public $selectedPatientId = null;
    public $showAddPatientModal = false;

    public function selectPatient($id)
    {
        $this->patientId = $id;
        $this->patient = Patient::find($id);
        $this->search = $this->patient->name;
        
        $this->emit('patientSelected', $this->patientId);
    }
    
    public function getPatientsProperty()
    {
        if (empty($this->search)) {
            return collect();
        }

        // if (strlen($this->search) < 3) {
        //     return collect();
        // }

        return Patient::where('name', 'ILIKE', '%' . $this->search . '%')
            ->orWhere('phone', 'ILIKE', '%' . $this->search . '%')
            ->get();
    }

    public function refreshPatients()
    {
        $this->patients;
    }

    public function addPatient()
    {
                
        $this->validate([
            'state.name' => 'required|max:255',
            'state.attendant_name' => 'required|max:255',
            'state.phone' => 'required|numeric|phone:mobile|unique:patients,phone',
        ]);


        $patient = Patient::create([
            'name' => $this->state['name'],
            'attendant_name' => $this->state['attendant_name'],
            'phone' => $this->state['phone'],
        ]);

        // Select the newly created patient
        $this->selectPatient($patient->id);

        // Reset the state
        $this->state = [
            'name' => '',
            'attendant_name' => '',
            'phone' => '',
        ];

        // Close the add patient modal
        $this->showAddPatientModal = false;

    }

    protected function messages()
    {
        return [
            'state.name.required' => 'Patient name is required',
            'state.name.max' => 'Patient name must not be greater than 255 characters',
            'state.attendant_name.required' => 'Attendant name is required',
            'state.attendant_name.max' => 'Attendant name must not be greater than 255 characters',
            'state.phone.required' => 'Phone number is required',
            'state.phone.numeric' => 'Phone number is invalid',
            'state.phone.phone' => 'Phone number is invalid',
            'state.phone.unique' => 'Phone number already exists',
        ];
    }
    
    public function render()
    {
        return view('livewire.searchable-patient-dropdown');
    }
}
