<?php

use App\Models\Salario;
use App\Models\Categoria;
use App\Models\Vacante;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;



new class extends Component
{
  
    public $salarios; 
    public $categorias; 

    public $vacante_id;
    public $titulo; 
    public $salario;
    public $categoria;
    public $empresa;
    public $ultimo_dia;
    public $descripcion; 
    public $imagen;
    public $imagen_nueva;

    use WithFileUploads;

    protected function rules(){
        return [
            'titulo' => 'required|string',
            'salario' => 'required',
            'categoria' => 'required',
            'empresa' => 'required',
            'ultimo_dia' => 'required',
            'descripcion' => 'required',
            'imagen_nueva' => 'nullable|image|max:1024'
        ];
    }

    public function mount(Vacante $vacante){
        $this->salarios = Salario::all();
        $this->categorias = Categoria::all();

        // Llenar el fomulario con la informacion de la vacante
        $this->vacante_id = $vacante->id;
        $this->titulo = $vacante->titulo;
        $this->salario = $vacante->salario_id;
        $this->categoria = $vacante->categoria_id;
        $this->empresa = $vacante->empresa;
        $this->ultimo_dia = Carbon::parse($vacante->ultimo_dia)->format('Y-m-d');
        $this->descripcion = $vacante->descripcion;
        $this->imagen = $vacante->imagen;

    }

    public function editarVacante(){
        $datos = $this->validate();

        // Revisar si hay una nueva imagen
        if($this->imagen_nueva){
            $imagen = $this->imagen_nueva->store('public/vacantes');
            $datos['imagen'] = str_replace('public/vacantes/', '', $imagen);
        }

        // Encontrar la vacante a editar
        $vacante = Vacante::find($this->vacante_id);

        // Asignar los valores
        $vacante->titulo = $datos['titulo'];
        $vacante->salario_id = $datos['salario'];
        $vacante->categoria_id = $datos['categoria'];
        $vacante->empresa = $datos['empresa'];
        $vacante->ultimo_dia = $datos['ultimo_dia'];
        $vacante->descripcion = $datos['descripcion'];
        $vacante->imagen = $datos['imagen'] ?? $vacante->imagen;

        // Guardar la vacante
        $vacante->save();

        // Redireccionar
        session()->flash('mensaje', 'La vacante se actualizó correctamente');
        return redirect()->route('vacantes.index');
    }

};
?>

<form 
    action=""
    class="md:w-1/2 space-y-5"
    wire:submit.prevent='editarVacante'
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
            wire:model="imagen_nueva" 
            accept="image/*"
        />

        <div class="my-5 w-80">
            <x-input-label 
                :value="__('Imagen Actual')"
            />

            <img 
                src="{{asset('storage/vacantes/'. $imagen)}}" 
                alt="{{'Imagen Vacante ' . $titulo}}"
            >
        </div>

        <div class="my-5 w-80">
            @if ($imagen_nueva)
                Imagen Nueva:
                <img src="{{$imagen_nueva->temporaryUrl()}}">
            @endif
        </div>

        @error('imagen_nueva')
           <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <x-primary-button class="w-full justify-center">
        {{ __('Guardar Cambios') }}
    </x-primary-button>
</form>