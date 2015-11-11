<?php

namespace Kanok\Generators\Contracts;


interface RestContract
{
    /**
     * set viewNameSpace
     * @return string
     */
    function getViewNameSpace();
    /**
     * set routeNameSpace
     * @return string
     */
    function getRouteNameSpace();
    /**
     * set model
     * @return string
     */
    function getModelName();

    /**
     * set upload fields
     * @return array
     */
    function getUploads();

    /**
     * set each upload field destinations
     * @return array
     */
    function getUploadPath();

    /**
     * path of create request
     * @return string
     */
    function getCreateRequest();

    /**
     * path of update requests
     * @return string
     */
    function getUpdateRequest();


}