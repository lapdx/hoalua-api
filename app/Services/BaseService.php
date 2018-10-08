<?php
/**
 * Created by PhpStorm.
 * User: tuanpa
 * Date: 1/10/18
 * Time: 2:04 PM
 */

namespace App\Services;


class BaseService
{
    /**
     * @return mixed
     */
    public function getData($filter = [])
    {
        if($filter['page_size'] == -1) {
            return $this->query($filter)->get($filter['columns']);
        } else {
            return $this->query($filter)->forPage($filter['page_id'], $filter['page_size'])->get($filter['columns']);
        }

    }

    public function getCount($filter = [])
    {
        return $this->query($filter)->count();
    }

    /**
     * @return mixed
     */
    public function paginator($filter = [])
    {
        $count = $this->getCount($filter);
        $pageSize = $filter['page_size'];
        $pageId = $filter['page_id'];
        $pageCount = ceil($count / $pageSize);
        $hasNext = true;
        if ($pageId >= $pageCount) {
            $hasNext = false;
        }
        $paginator = [
            'has_next' => $hasNext,
            'total_count' => $count,
            'page_count' => $pageCount,
            'limit' => (int) $pageSize,
            'off_set' => ($pageId - 1) * $pageSize,
        ];
        return $paginator;
    }

    /**
     * @return mixed
     */
    public function query($filter = [])
    {
        $query = call_user_func(static::MODEL . '::query');
        $tableName = call_user_func(static::MODEL . '::getTableName');
        if (array_key_exists('create_from', $filter) && $filter['create_from']) {
            $query->where($tableName . '.created_at', '>=', $filter['create_from']);
        }
        if (array_key_exists('create_to', $filter) && $filter['create_to']) {
            $query->where($tableName . '.created_at', '=<', $filter['create_to']);
        }
        if (array_key_exists('update_from', $filter) && $filter['update_from']) {
            $query->where($tableName . '.update_at', '>=', $filter['update_from']);
        }
        if (array_key_exists('update_to', $filter) && $filter['update_to']) {
            $query->where($tableName . '.update_at', '=<', $filter['update_to']);
        }
        if (array_key_exists('id', $filter) && $filter['id']) {
            $query->where($tableName . '.id', '=', $filter['id']);
        }
        if (array_key_exists('ids', $filter)) {
            if(!$filter['ids']){
                $filter['ids'] = [-1];
            }
            $query->whereIn($tableName . '.id', $filter['ids']);
        }
        return $query;
    }


    public function buildGatewayUrl($path, $params = []) {
        $gatewayUrl = env('GATEWAY_URL');
        $gatewayToken = env('GATEWAY_TOKEN', "xLhkjyKa");
        $fullUrl = $gatewayUrl . $path . '?token=' . $gatewayToken;
        if(count($params) > 0) {
            foreach ($params as $key => $value) {
                $fullUrl .= '&' . $key . '=' . $value;
            }
        }
        return $fullUrl;
    }

    public function generatorMatchFtSearch($searchText, $column = 'search'){
        $searchText = str_replace( '-', ' ', $searchText);
        $searchTextArr = explode(' ', $searchText);
        $searchTextBuiled  = '';
        foreach ($searchTextArr as $key => $word){
            $searchTextBuiled .= '+' . $word;
            $searchTextBuiled .= ' ';
        }
        $searchTextBuiled = trim($searchTextBuiled);
        $result = "MATCH ({$column}) AGAINST ('{$searchTextBuiled}' IN BOOLEAN MODE)";
        return $result;
    }

    function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateRandomNumber($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= $characters[rand(0, $charactersLength - 1)];
        }
        return $random;
    }
}
