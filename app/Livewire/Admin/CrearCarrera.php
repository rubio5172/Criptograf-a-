<?php

namespace App\Livewire\Admin;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CrearCarrera extends Component
{
    public string $nombre='';
    #[Layout('components.layouts.admin')]
    public function render()
    {
        
        return view('livewire.admin.crear-carrera');
    }
}
