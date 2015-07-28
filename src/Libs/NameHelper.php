<?php
/**
 * Created by PhpStorm.
 * User: ismail
 * Date: 13.06.2015
 * Time: 23:28
 */

namespace Kanok\Generators\Libs;


class NameHelper {

    /**
     * Get the name for the model
     *
     * @param $resource
     * @return string
     */
    public function getModelName($resource)
    {
        return ucwords(str_singular(camel_case($resource)));
    }

    /**
     * Get the name for the controller
     *
     * @param $resource
     * @return string
     */
    public function getControllerName($resource)
    {
        return ucwords(str_plural(camel_case($resource))) . 'Controller';
    }

    /**
     * Get the view folder name
     *
     * @param $resource
     * @return string
     */
    public function getViewFolderName($resource)
    {
        return str_plural($resource);
    }

    /**
     * Get the name for the migration
     *
     * @param $resource
     * @return string
     */
    public function getMigrationName($resource)
    {
        return "create_" . camel_case($resource) . "_table";
    }

    /**
     * Get the migration class name
     *
     * @param $resource
     * @return string
     */
    public function getMigrationClassName($resource)
    {
        return ucwords(camel_case( $this->getMigrationName($resource) ));
    }



    /**
     * Get the route name on Http/routes.php
     *
     * @param $resource
     * @return string
     */
    public function getRouteName($resource)
    {
        return  str_plural($resource);
    }

    /**
     * Get the request name
     *
     * @param $resource
     * @return string
     */
    public function getRequestName($resource)
    {
        return  ucwords(str_singular(camel_case($resource))) . 'Request';
    }

    /**
     * Get the language file name
     *
     * @param $resource
     * @return string
     */
    public function getLanguageFileName($resource)
    {
        return str_plural($resource);
    }


}