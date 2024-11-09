<?php

namespace App\Http\Traits;
use App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
trait DataTrait
{

    public function get($model,$number_item = 10)
    {
        return $model::paginate($number_item);
    }

    /*
        Get list data with limit and offset
    */
    public function getByPage($model, $skip, $take) {
        return $model::skip($skip)->take($take)->get();
    }

    /*
        Get all list data
    */
    public function getAll($model) {
        return $model::get();
    }

    /*
        Count list data
    */
    public function count($model) {
        return $model::count();
    }
    
    /*
        Get Single record with param
    */
    public function getSigleRecord($model,$id){
        return $model::find($id);
    }

    /*
        Create Single record with param
    */
    public function createSingleRecord($model, $params){
        $params['created_by'] = Auth::user()->id;
        return $model::create($params);
    }

    /*
        Create Muti records with array param
    */
    public function createMutiRecord($model, $arrayparams = array() ){ // ex: (new Roles(), array( ['role_name'=>'1'], ['role_name'=>'2']));
        return $model::insert($arrayparams);
    }

    /*
        Delete Single record By Id
    */
    public function deleteById($model, $id){
        return $model::find($id)->delete();
        // return $model::destroy($id);
    }

    /*
        Delete Muti records with array id
    */
    public function deleteMutiRecord($model,$arrayIds = array() ){
        return DB::table($model->getTable())->whereIn('id',$arrayIds)->update([
            'deleted_at' => now()
        ]);
    }

    /*
        Update Single record
    */
    public function updateMutipleRecord($model, $arrayIds = array(), $params = array() ){ // ex: (new Roles(), [10,11], ['role_name'=>'4567']);
        return DB::table($model->getTable())
        ->whereIn('id', $arrayIds)
        ->update($params);
    }

    /*
        Update Record
    */
    public function updateById($model, $id, $params = array() ){ // ex: (new Roles(), [10,11], ['role_name'=>'4567']);
        if(isset($params['_token'])){
            unset($params['_token']);
        };
        if(isset($params['_method'])){
            unset($params['_method']);
        };
        if(isset($params['password_confirmation']))  {
            unset($params['password_confirmation']);
        };
        $params['updated_by'] = Auth::user()->id;
        return $model::where('id', $id)->update($params);
    }

    // public function upSert($model,$params = array(),$keys){
    //     return $model::upsert($params,$keys);
    // }
}