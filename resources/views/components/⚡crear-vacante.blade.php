<?php

use App\Models\Salario;
use App\Models\Categoria;
use Livewire\Component;

new class extends Component
{
    public $salarios, $categorias; 
    public $titulo, $salario, $categoria, $empresa, $ultimo_dia, $descripcion, $imagen;

    protected $rules = [
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen' => 'required'
    ];

    public function mount()
    {
        $this->salarios = Salario::all();
        $this->categorias = Categoria::all();
    }
};
?>

<form 
    action=""
    class="md:w-1/2 space-y-5"
>
    <div>
        <x-input-label for="titulo" :value="__('Titulo Vacante')" />
        <x-text-input 
            id="titulo" 
            class="block mt-1 w-full" 
            type="text" 
            wire:model="titulo" 
            :value="old('titulo')" required autofocus
            placeholder="Titulo Vacante"
        />
    </div>

    <div>
        <x-input-label for="salario" :value="__('Salario Mensual')" />
        <select
            id="salario" 
            wire:model="salario"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
        >
            <option>-- Seleccione --</option>
            @foreach ($salarios as $salario)
                <option value="{{$salario->id}}">{{$salario->salario}}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="categoria" :value="__('Categoría')" />
        <select
            id="categoria" 
            wire:model="categoria"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
        >
            <option>-- Seleccione --</option>
            @foreach ($categorias as $categoria)
                <option value="{{$categoria->id}}">{{$categoria->categoria}}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="empresa" :value="__('Empresa')" />
        <x-text-input 
            id="empresa" 
            class="block mt-1 w-full" 
            type="text" 
            wire:model="empresa" 
            :value="old('empresa')" required autofocus
            placeholder="Empresa: Ej. Netflix, Uber, Spotify, etc."
        />
    </div>

    <div>
        <x-input-label for="ultimo_dia" :value="__('Último Día para Postularse')" />
        <x-text-input 
            id="ultimo_dia" 
            class="block mt-1 w-full" 
            type="date" 
            wire:model="ultimo_dia" 
            :value="old('ultimo_dia')" required autofocus
        />
    </div>

    <div>
        <x-input-label for="descripcion" :value="__('Descripción Puesto')" />
        <textarea 
            wire:model="descripcion" 
            id="descripcion" 
            placeholder="Descripción general del puesto"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full h-72"   
        >
        </textarea>
    </div>

    <div>
        <x-input-label for="imagen" :value="__('Imagen')" />
        <x-text-input 
            id="imagen" 
            class="block mt-1 w-full" 
            type="file" 
            wire:model="imagen" 
        />
    </div>

    <x-primary-button class="w-full justify-center">
        {{ __('Crear Vacante') }}
    </x-primary-button>
</form>