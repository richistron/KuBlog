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
/**
 * Carga de Modelos
 */
Load::models('articulo' , 'articulo_etiqueta' , 'etiqueta');
/**
 * Gestiona la parte administrativa de las noticias
 */
class ArticuloController extends ApplicationController {

    /**
     * Lista los artículos
     * @param $page
     * @return Paginate
     */
    public function index ($page = 1) {
        $this->pageTitle = 'Lista de artículos';
        $articulo = new Articulo();
        $this->listPosts = $articulo->getAllPost($page);
    }
    /**
     * Edita un artículo
     * @param $id
     * @return ResultSet
     */
    public function edit ($id = NULL) {
        $articulo = new Articulo();
        //se verifica si se ha enviado el formulario (submit)
        if (Input::hasPost('articulo')) {
            if($articulo = Articulo::input('update', Input::post('articulo'))) {
                $articulo_etiqueta = new ArticuloEtiqueta();
                $articulo_etiqueta->addTagsPost(Input::post('tags'), $articulo->id);
                return Router::redirect('admin/articulo/');
            }
        }
        if ($id != NULL) {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->articulo = $articulo->find($id);
            $this->pageTitle = 'Editando el articulo - ' . $this->articulo->title;
            $etiqueta = new Etiqueta();
            $this->tags = $etiqueta->getTagByPost($this->articulo->id);
        }
    }
    /**
     * Elimina una etiqueta de un articulo
     *
     * @param int $articulo_id
     */
    public function delTag ($articulo_id = null) {
        View::select(NULL);
        $articulo_etiqueta = new ArticuloEtiqueta();
        $articulo_etiqueta->delTagByPost($articulo_id, Input::post('name'));
        echo Input::post('name');
    }
    /**
     * Crea un nuevo articulo
     *
     */
    public function create () {
        if (Input::hasPost('articulo')) {
            if($articulo = Articulo::input('create', Input::post('articulo'))) {
                $articulo_etiqueta = new ArticuloEtiqueta();
                $articulo_etiqueta->addTagsPost(Input::post('tags'), $articulo->id);
                return Router::redirect('admin/articulo/');
            }
        }

        $this->usuario_id = Auth::get('id');
        //$this->autor = Auth::get('nombre');
    }
    /**
     * Eliminar un artículo
     *
     * @param int $id
     */
    public function del ($id = null) {
        View::select(NULL);
        if ($id) {
            $articulo = new Articulo();
            $articulo->del($id);
        }
        return Router::redirect('admin/articulo/');
    }
    /**
     * Corriendo filtro
     *
     */
    public function before_filter () {
        if (Input::isAjax()) {
            View::response('view');
        }
    }
}
