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
use Illuminate\View\View;
use Kanok\Generators\Framework\Helpers\FileUpload;

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
    /**
     * List records from resource
     * @return View
     */
    public function index()
    {
        $collection = app($this->model)->all();
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
        $request = app($this->createRequest);
        $requestData = $request->all();
        $requestData = $this->upload($request, $requestData);
        $element = app($this->model)->create($requestData);
        return redirect()->route($this->routeNameSpace.'.edit',$element->id)->withSuccess(trans('common.created'));
    }

    /**
     * Remove specific record from resource
     * @param $id
     * @return View
     */
    public function show($id)
    {
        app($this->model)->findOrFail($id)->delete();
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
        $request = app($this->updateRequest);
        $requestData = $request->all();
        $requestData = $this->upload($request, $requestData);
        app($this->model)->findOrFail($id)->update($requestData);
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

}