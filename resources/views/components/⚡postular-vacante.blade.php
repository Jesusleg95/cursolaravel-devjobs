<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Vacante;

new class extends Component
{
    use WithFileUploads;

    public $cv;
    public $vacante;

    protected function rules(){
        return [
            'cv' => 'required|mimes:pdf'
        ];
    }

    public function mount(Vacante $vacante){
        $this->vacante = $vacante;
    }

    public function postularme(){
        $datos = $this->validate();

        // Almacenar CV
        $cv = $this->cv->store('public/cv');
        $datos['cv'] = str_replace('public/cv/', '', $cv);

        // Crear candidato de la vacante
        $this->vacante->candidatos()->create([
            'user_id' => Auth::user()->id,
            'cv' => $datos['cv']
        ]);

        // Crear notificacion y mandar email

        // Mostrar un mensaje
        session()->flash('mensaje', 'Se envió correctamente tu información');

        return redirect()->back();
    }
};
?>

<div class="bg-gray-100 p-5 mt-10 flex flex-col justify-center items-center">
    <h3 class="text-center text-2xl font-bold my-4">
        Postularme a esta Vacante
    </h3>

    @if(session()->has('mensaje'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition.opacity.duration.500ms
            class="uppercase border border-green-600 bg-green-100 text-green-600 font-bold p-2 my-3 text-sm"
        >
            {{ session('mensaje') }}
        </div>

    @else
        <form 
            wire:submit.prevent='postularme' 
            class="w-96 mt-5"
        >
            <div class="mb-4">
                <x-input-label for="cv" :value="__('Curriculum (PDF)')" />
                <x-text-input 
                    id="cv" 
                    type="file"
                    accept=".pdf"
                    wire:model="cv" 
                    class="block mt-1 w-full"  
                />
            </div>

            @error('cv')
            <livewire:mostrar-alerta :message="$message" />
            @enderror

            <x-primary-button class="my-5">
                {{ __('Postularme') }}
            </x-primary-button>
        </form>
    @endif

</div>