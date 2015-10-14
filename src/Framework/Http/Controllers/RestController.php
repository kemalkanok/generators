<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 30/08/15
 * Time: 21:41
 */
namespace Kanok\Generators\Framework\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Kanok\Generators\Framework\Helpers\FileUpload;
use Kanok\Generators\Framework\Traits\ApiControllerTrait;

class RestController extends Controller {

    /**
     * namespace of view
     * @var string
     */
    public $viewNameSpace = "";
    /**
     * namespace of route
     * @var string
     */
    public $routeNameSpace = "";
    /**
     * model path
     * @var string
     */
    public $model = "";
    /**
     * fields for uploads
     * @var array
     */
    public $uploads = [];
    /**
     * upload path for each specific upload
     * @var array
     */
    public $uploadPath = [];
    /*
     * create request path
     * @var string
     */
    public $createRequest = "";
    /*
     * update request path
     * @var string
     */
    public $updateRequest = "";

    use ApiControllerTrait;

    /**
     * List records from resource
     * @return View
     */
    public function index()
    {
        $collection = app($this->model)->all();
        $_request = app('Illuminate\Http\Request');
        if($_request->type == 'json')
        {
            return $this->success($collection);
        }
        return view($this->viewNameSpace . '.index',compact('collection'));
    }

    /**
     * Show the create form for new record
     * @return View
     */
    public function create()
    {
        return view($this->viewNameSpace.'.create');
    }

    /**
     * Create new record to resource
     * @return mixed
     */
    public function store()
    {
        $request = $this->checkAjaxRequestForCreate();
        if($request instanceof JsonResponse)
            return $this->formFail($request->getData());
        $element = $this->insertRecord($request);
        if($request->type == 'json')
            return $this->success();
        return redirect()->route($this->routeNameSpace.'.edit',$element->id)->withSuccess(trans('common.created'));
    }

    /**
     * @param $createdElement
     */
    public function extraStore($createdElement)
    {

    }

    /**
     * Remove specific record from resource
     * @param $id
     * @return View
     */
    public function show($id)
    {
        $_request = app('Illuminate\Http\Request');
        $this->beforeDelete($id);
        app($this->model)->findOrFail($id)->delete();
        $this->afterDelete();
        if($_request->type == 'json')
        {
            return $this->success();
        }
        return redirect()->route($this->routeNameSpace.'.index')->withSuccess(trans('common.deleted'));
    }

    /**
     * show edit form for specific record
     * @param $id
     * @return View
     */
    public function edit($id)
    {
        $element = app($this->model)->findOrFail($id);
        return view($this->viewNameSpace.'.edit',compact('element'));
    }

    /**
     * update specific record in resource
     * @param $id
     * @return mixed
     */
    public function update( $id)
    {
        $request = $this->checkAjaxRequestForUpdate();
        if($request instanceof JsonResponse)
            return $this->formFail($request->getData());
        $request = $this->updateRecord($id);
        if($request->type == 'json')
            return $this->success();
        return redirect()->route($this->routeNameSpace.'.edit',$id)->withSuccess(trans('common.updated'));

    }

    /**
     * uploads the specified fields
     * @param Request $request
     * @param $requestData
     * @return mixed
     */
    private function upload(Request $request, $requestData)
    {
        foreach ($this->uploads as $key => $upload) {
            if (isset($requestData[$upload])) {
                $requestData[$upload] = FileUpload::uniqueUpload($request, $upload, $this->uploadPath[$key]);
            }
        }
        return $requestData;
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    private function checkAjaxRequestForCreate()
    {
        $_request = app('Illuminate\Http\Request');
        if ($_request->ajax()) {
            try {
               return app($this->createRequest);
            }
            catch (HttpResponseException $e) {
                return $e->getResponse();
            }
        } else {
            return app($this->createRequest);
        }
    }
    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    private function checkAjaxRequestForUpdate()
    {
        $_request = app('Illuminate\Http\Request');
        if ($_request->ajax()) {
            try {
               return app($this->updateRequest);
            }
            catch (HttpResponseException $e) {
                return $e->getResponse();
            }
        } else {
            return app($this->updateRequest);
        }
    }

    /**
     * inserts a record to the system
     * @param $request
     * @return mixed
     */
    private function insertRecord($request)
    {
        $requestData = $request->all();
        $requestData = $this->upload($request, $requestData);
        $requestData = $this->beforeCreate($requestData);
        $element = app($this->model)->create($requestData);
        $this->afterCreate($element);
        return $element;
    }

    /**
     * General Method before creating the element
     * @param array $requestVariables
     * @return array
     */
    public function beforeCreate(array $requestVariables)
    {
        return $requestVariables;
    }

    /**
     * General Method after creating the element
     * @param Model $element
     */
    public function afterCreate($element)
    {

    }

    /**
     * General Method before updating the element
     * @param array $requestVariables
     * @return array
     */
    public function beforeUpdate(array $requestVariables)
    {
        return $requestVariables;
    }

    /**
     * General Method after updating the element
     * @param Model $element
     */
    public function afterUpdate($element)
    {

    }

    /**
     * General Method before deleting the element
     * @param $id
     */
    public function beforeDelete($id)
    {

    }

    /**
     * General Method after deleting the element
     */
    public function afterDelete()
    {

    }

    /**
     * updates a specific record
     *
     * @param $id
     * @return \Illuminate\Foundation\Application|mixed
     */
    private function updateRecord($id)
    {
        $request = app($this->updateRequest);
        $requestData = $request->all();
        $requestData = $this->upload($request, $requestData);
        $requestData = $this->beforeUpdate($requestData);
        $element = app($this->model)->findOrFail($id)->update($requestData);
        $this->afterUpdate($element);
        return $request;
    }

}