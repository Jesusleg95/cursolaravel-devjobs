<?php

use App\Models\Vacante;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public function getVacantesProperty(){
        return Vacante::where('user_id', Auth::user()->id)->paginate(10);
    }

    #[On('eliminarVacante')] 
    public function eliminarVacante(Vacante $vacanteId){
        $vacanteId->delete();
    }
};
?>

<main>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        @forelse ($this->vacantes as $vacante)
            
            <div class="p-6 bg-white border-b border-gray-200 text-gray-900 md:flex md:justify-between md:items-center">
                <div class="leading-10">
                    <a href="#" class="text-xl font-bold">
                        {{$vacante->titulo}}
                    </a>
                    <p class="text-sm text-gray-600 font-bold">
                        {{$vacante->empresa}}
                    </p>
                    <p class="text-sm text-gray-500">
                        Último día: {{$vacante->ultimo_dia->format('d/m/Y')}}
                    </p>
                </div>

                <div class="flex flex-col md:flex-row items-stretch gap-3 mt-5 md:mt-0">
                    <a 
                        href="#"
                        class="bg-slate-800 py-2 text-center px-4 rounded-lg text-white text-xs font-bold uppercase"
                    >
                        Candidatos
                    </a>

                    <a 
                        href="{{route('vacantes.edit', $vacante->id)}}"
                        class="bg-blue-800 py-2 text-center px-4 rounded-lg text-white text-xs font-bold uppercase"
                    >
                        Editar
                    </a>

                    <button 
                        wire:click="$dispatch('mostrarAlerta',{vacanteId: {{$vacante->id}}})" 
                        class="bg-red-600 py-2 text-center px-4 rounded-lg text-white text-xs font-bold uppercase"
                    >
                        Eliminar
                </button>
                </div>

                
            </div>
            
        @empty
            <p class="p-3 text-center text-sm text-gray-600">
                No hay vacantes que mostrar
            </p>
        @endforelse
    </div>

    <div class="mt-10">
        {{$this->vacantes->links()}}
    </div>
</main>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('mostrarAlerta',({vacanteId})=>{
            Swal.fire({
                title: "¿Eliminar vacante?",
                text: "Una vacante eliminada no se puede recuperar",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar",
                cancelButtonText: 'Cancelar'
            }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('eliminarVacante', ({vacanteId}));
                Swal.fire({
                    title: "Se eliminó la vacante",
                    text: "Eliminado correctamente",
                    icon: "success"
                });
            }
            });
        }) 
    </script>
@endpush