<?php

use App\Models\Salario;
use App\Models\Categoria;
use App\Models\Vacante;
use Livewire\Component;
use Livewire\WithFileUploads;


new class extends Component
{
  
    public $salarios; 
    public $categorias; 
    public $titulo; 
    public $salario;
    public $categoria;
    public $empresa;
    public $ultimo_dia;
    public $descripcion; 
    public $imagen;

    use WithFileUploads;

protected function rules(){
    return [
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen' => 'required|image|max:1024',
    ];
}

    public function crearVacante(){
        $datos = $this->validate();

        //Almacenar la imagen
        $imagen = $this->imagen->store('public/vacantes');
        $datos['imagen'] = str_replace('public/vacantes/', '', $imagen);
        
        //Crear la vacante
        Vacante::create([
            'titulo' => $datos['titulo'],
            'salario_id' => $datos['salario'],
            'categoria_id' => $datos['categoria'],
            'empresa' => $datos['empresa'],
            'ultimo_dia' => $datos['ultimo_dia'],
            'descripcion' => $datos['descripcion'],
            'imagen' => $datos['imagen'],
            'user_id' => Auth::user()->id
        ]);

        //Crear mensaje

        //Redireccionar al usuario
    }

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
    wire:submit.prevent='crearVacante'
    enctype="multipart/form-data"
>
    <div>
        <x-input-label for="titulo" :value="__('Titulo Vacante')" />
        <x-text-input 
            id="titulo" 
            class="block mt-1 w-full" 
            type="text" 
            wire:model="titulo" 
            
            placeholder="Titulo Vacante"
        />

        @error('titulo')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="salario" :value="__('Salario Mensual')" />
        <select
            id="salario" 
            wire:model="salario"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
        >
            <option value="">-- Seleccione --</option>
            @foreach ($salarios as $salario)
                <option value="{{$salario->id}}">{{$salario->salario}}</option>
            @endforeach
        </select>

        @error('salario')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="categoria" :value="__('Categoría')" />
        <select
            id="categoria" 
            wire:model="categoria"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
        >
            <option value="">-- Seleccione --</option>
            @foreach ($categorias as $categoria)
                <option value="{{$categoria->id}}">{{$categoria->categoria}}</option>
            @endforeach
        </select>

        @error('categoria')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="empresa" :value="__('Empresa')" />
        <x-text-input 
            id="empresa" 
            class="block mt-1 w-full" 
            type="text" 
            wire:model="empresa" 
          
            placeholder="Empresa: Ej. Netflix, Uber, Spotify, etc."
        />

        @error('empresa')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="ultimo_dia" :value="__('Último Día para Postularse')" />
        <x-text-input 
            id="ultimo_dia" 
            class="block mt-1 w-full" 
            type="date" 
            wire:model="ultimo_dia" 
            
        />
        @error('ultimo_dia')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
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
        @error('descripcion')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="imagen" :value="__('Imagen')" />
        <x-text-input 
            id="imagen" 
            class="block mt-1 w-full" 
            type="file" 
            wire:model="imagen" 
            accept="image/*"
        />

        <div class="my-5 w-80">
            @if ($imagen)
                Imagen:
                <img src="{{$imagen->temporaryUrl()}}">
            @endif
        </div>

        @error('imagen')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <x-primary-button class="w-full justify-center">
        {{ __('Crear Vacante') }}
    </x-primary-button>
</form>