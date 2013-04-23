
<?php

class Controller extends Base_Controller {

    public function load() {

        $route_data = Router::getData();

        // set page title
        System::set('page_title', 'Hello ' . $route_data['name']);
        
        // set breadcrumb
        System::set('breadcrumb',  array(
            'Home' => null,
        ));
        
        // render
        Templater::render(array(
            'name' => $route_data['name']
        ));
    }

}