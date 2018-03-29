<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class BaseModel extends Model
{
    /**
     *
     * rewrite firstOrCreate
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @return static
     * @throws QueryException
     */
    public static function firstOrCreate(array $attributes)
    {
        try {
            $instance = parent::firstOrCreate($attributes);
            return parent::find($instance->getKey());
        } catch (QueryException $e) {
            // 保存失败时, 再能过openid查一次
            $instance = (new static)->newQueryWithoutScopes()->where($attributes)->first();
            if (!$instance) {
                throw $e;
            }
            return $instance;
        }
    }

    /**
     * 强制使用指定索引, 必须首先执行
     * @param $indexName
     * @return static
     */
    public static function forceIndex($indexName)
    {
        $model = new static();
        $model->setTable(\DB::raw($model->getTable() . ' FORCE INDEX (' . $indexName . ')'));
        return $model;
    }

}
