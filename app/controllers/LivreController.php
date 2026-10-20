<?php
namespace App\Controllers;

use App\Core\Controller;

class LivreController extends Controller
{
    public function index(): void     { $this->requireAuth(); /* TODO J2 */ }
    public function createForm(): void { $this->requireAuth(); /* TODO J2 */ }
    public function create(): void    { $this->requireAuth(); /* TODO J2 */ }
    public function editForm(int $id): void { $this->requireAuth(); /* TODO J2 */ }
    public function update(int $id): void   { $this->requireAuth(); /* TODO J2 */ }
    public function delete(int $id): void   { $this->requireAuth(); /* TODO J2 */ }
}
