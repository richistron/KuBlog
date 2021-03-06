<?php
/**
 * KBlog - KumbiaPHP Blog
 * PHP version 5
 * LICENSE
 *
 * This source file is subject to the GNU/GPL that is bundled
 * with this package in the file docs/LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to deivinsontejeda@gmail.com so we can send you a copy immediately.
 *
 * @author Deivinson Tejeda <deivinsontejeda@gmail.com>
 */
Load::models('perfiles');
class PerfilesController extends ApplicationController
{
    /**
     * Lista los perfiles
     *
     * @param int $page
     */
    final function index ($page = 1)
    {
        $perfiles = new Perfiles();
        $this->listPerfiles = $perfiles->paginate("page: $page");
    }
    /**
     * Crea un perfil
     *
     */
    final function create ()
    {
        if (Input::hasPost('perfiles')) {
            $perfil = new Perfiles($this->post('perfiles'));
            if (! $perfil->save()) {
                Flash::error('Fallo Operación');
                $this->perfiles = $this->post('perfiles');
            }
        }
    }
    final function edit ()
    {}
    /**
     * Borra un perfil
     *
     * @param int $id
     */
    final function del ($id = null)
    {
        if ($id) {
            $perfiles = new Perfiles();
            //Buscando el Objeto a Borrar
            $perfil = $perfiles->find($id);
            if (! $perfil->delete()) {
                Flash::error('Falló Operación');
            }
        }
        //enrutando al index para listar los menus
        Router::redirect('perfiles/');
    }
}
