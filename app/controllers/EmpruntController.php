<?php
namespace App\Controllers;

use App\Core\Controller;

class EmpruntController extends Controller
{
    public function index(): void      { $this->requireAuth(); /* TODO J3 */ }
    public function createForm(): void  { $this->requireAuth(); /* TODO J3 */ }
    public function create(): void     { $this->requireAuth(); /* TODO J3 */ }
    public function retour(int $id): void { $this->requireAuth(); /* TODO J3 */ }
}
