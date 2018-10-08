<?php
/**
 * Created by PhpStorm.
 * User: tuanpa
 * Date: 1/10/18
 * Time: 1:49 PM
 */


namespace App\Http\Controllers;

class BaseController extends Controller
{
    const STATUS_SUCCESSFUL = "successful";
    const STATUS_FAIL = "fail";

    protected function getDefaultStatus() {
        $result = array();
        $result["status"] = self::STATUS_FAIL;
        $result["message"] = 'Error!';
        return $result;
    }

    protected function getSuccessStatus($data = [])
    {
        $result = array();
        $result["status"] = self::STATUS_SUCCESSFUL;
        if ($data) {
            $result["data"] = $data;
        }
        return $result;
    }

    protected function response($data)
    {
        if(!isset($data['status']))
            $data['status'] = self::STATUS_SUCCESSFUL;

        return response()->json($data);
    }

    protected function stringToDateOrNull($dateString)
    {
        if ($dateString == null || strlen($dateString) == 0) {
            return null;
        }
        $retVal = DateTime::createFromFormat("d/m/Y", $dateString);
        $retVal->setTime(0, 0, 0);
        return $retVal;
    }

    protected function getAction($request)
    {
        return $request->route()[1]['uses'];
    }

    protected function buildFilter($request)
    {
        $filter = [
            'page_size' => 40,
            'page_id' => 1,
            'columns' => ['*']
        ];
        if ($request->has('page_size')) {
            $filter['page_size'] = $request->input('page_size');
        }
        if ($request->has('page_id') && $request->input('page_id') > 0) {
            $filter['page_id'] = $request->input('page_id');
        }
        if ($request->has('ids') && $request->input('ids')) {
            $filter['ids'] = explode(',', $request->input('ids'));
        }
        if ($request->has('id') && $request->input('id')) {
            $filter['id'] = $request->input('id');
        }
        if ($request->has('creator_id') && $request->input('creator_id')) {
            $filter['creator_id'] = $request->input('creator_id');
        }
        if ($request->has('business_id') && !empty($request->input('business_id'))) {
            $filter['business_id'] = $request->input('business_id');
        } else if ($request->has('business_user') && !empty($request->input('business_user'))) {
            $filter['business_user'] = explode('|', $request->input('business_user'));
        }
        return $filter;
    }



    public function fileUpload($fileObj, $path = '/', $fileName = '')
    {
        $destinationPath = resource_path($path);
        if(!$fileName){
            $fileName = str_slug($fileObj->getClientOriginalName()) . time();
        }
        $fileName .= '.' . $fileObj->getClientOriginalExtension();
        $fileObj->move($destinationPath, $fileName);
        return $path . $fileName;
    }

    public function removeFileUpload($filePath)
    {
        resource_path($filePath);
        @unlink(resource_path($filePath));
    }

}

