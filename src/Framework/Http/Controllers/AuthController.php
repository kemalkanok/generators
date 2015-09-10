<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 30/08/15
 * Time: 21:41
 */
namespace Kanok\Generators\Framework\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Kanok\Generators\Framework\Helpers\Auth;

class AuthController extends RestController {
    /**
     * route for redirection after log in
     * @var string
     */
    public $dashboardNameSpace = "";
    /**
     * login view
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->viewNameSpace.'.login');
    }

    /**
     * attempt to login via post data
     *
     * @return $this|array|AuthController
     */
    public function store()
    {
        $request = $this->checkAjaxRequest();
        if($request instanceof JsonResponse)
            return $this->formFail($request->getData());
        return $this->attempt($request);
    }

    /**
     * attempts to login
     * @param $request
     * @return $this|array
     */
    public function attempt($request)
    {
        if (Auth::attempt($request)) {
            if ($request->ajax())
                return $this->success('common.login.success');
            return redirect()->route($this->dashboardNameSpace . '..index')->withSuccess(trans('common.login.success'));
        } else {
            if ($request->ajax())
                return $this->fail(trans('common.login.failed'));
            return redirect()->route($this->routeNameSpace . '.create')->withErrors([trans('common.login.failed')]);
        }
    }

    public function destroy()
    {
        Auth::logout();
        $request = app('Illuminate\Http\Request');
        if ($request->ajax())
            return $this->fail(trans('common.logout.success'));
        return redirect()->route($this->routeNameSpace. '.create')->withSuccess(trans('common.logout.success'));
    }


}