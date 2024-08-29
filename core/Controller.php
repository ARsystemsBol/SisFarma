<?php
class Controller {
    // Método para cargar vistas
    protected function view($view, $data = []) {
        // Extrae los datos para que estén disponibles como variables en la vista
        extract($data);
        
        // Incluye el archivo de vista
        require_once '../app/views/' . $view . '.php';
    }
}