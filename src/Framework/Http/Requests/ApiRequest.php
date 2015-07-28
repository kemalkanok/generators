<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 07/06/15
 * Time: 18:17
 */

namespace Kanok\Generators\Framework\Http\Requests;

use Kanok\Generators\Framework\Exceptions\ApiRequestFormFailException;
use Illuminate\Support\Facades\Input;
/**
 * Class ApiRequest
 * @package App\Http\Requests
 */
class ApiRequest {
    private $request;
    private $errorMessageArray;
    private $errorMessageJson;
    private $UnauthorizedMessageArray = ["status" => "403", "message" => "Unauthorized Request Fail"];
    private $UnauthorizedMessageJson;
    /**
     *
     */
    function __construct()
    {
        $this->createValidator();
        return $this->validate();
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
    /**
     * @param $messages
     * @return array
     */
    public function fail($messages)
    {
        $this->errorMessageArray = ["status" => "304", "message" => "form fail", "errors" => $messages];
        $this->errorMessageJson = json_encode($this->errorMessageArray);
        return $this->errorMessageJson;
    }
    /**
     * @var
     */
    protected $validator;
    /**
     * creates the validator
     * return void
     */
    function createValidator()
    {
        $this->validator = \Validator::Make(\Input::all(), $this->rules());
    }
    /**
     * validate the validator
     * @return mixed
     */
    function validate()
    {
        if($this->checkAuthorize())
        {
            return $this->createAuthorizeError();
        }
        if ($this->validator->fails()) {
            return $this->createErrorPage();
        }
        else
        {
            return $this->createSuccessPage();
        }
    }

    /**
     * generate Error Page
     * @throws ApiRequestFormFailException
     */
    function createErrorPage()
    {
        throw new ApiRequestFormFailException($this->fail($this->validator->messages()));
    }
    /**
     * generates Success Page
     * @return mixed
     */
    function createSuccessPage()
    {
        $this->request = Input::all();
        return $this->request;
    }
    public static function all()
    {
        return Input::all();
    }
    /**
     * checks the authorize method
     * @return bool
     */
    public function checkAuthorize()
    {
        return !$this->authorize();
    }
    /**
     * @throws ApiRequestFormFailException
     */
    public function createAuthorizeError()
    {
        $this->UnauthorizedMessageJson = json_encode($this->UnauthorizedMessageArray);
        throw new ApiRequestFormFailException($this->UnauthorizedMessageJson);
    }
}