<?php

namespace App\Http\Controllers;
use App\Exceptions\CustomException;
use App\Helpers\GlobalFunction;
use App\Http\Controllers\Controller;
use App\Models\GroupWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Finder\Glob;
use Yajra\DataTables\Facades\DataTables;

class GroupWordController extends Controller
{
    protected $name = 'GroupWord';
    protected $breadcrumb = '<strong>Data</strong> GroupWord';
    protected $modul = 'message';
    protected $route = 'messages';
    protected $view = 'group-word';
    protected $newModel;
    protected $model;
    protected $rows;
    protected $createLink;
    protected $storeLink;
    protected $indexLink;
    protected $updateLink;
    protected $editLink;
    public function __construct()
    {
        $this->newModel = new GroupWord();
        $this->model = GroupWord::query()->latest();
        $this->rows = [
            'name'=>['Name','Email','GroupWord'],
            'column' => ['name','email','message']
        ];
        $this->createLink = route('group-words.create');
        $this->storeLink = route('group-words.store');
        $this->indexLink = route('group-words.index');
        $this->updateLink = 'group-words.update';
        $this->editLink = 'group-words.edit';
    }

    protected static function validateRequest($request, $type)
    {
        if ($type == 'create') {

            $result = Validator::make($request->all(), [
                'name' => 'required',

            ]);
            if($request->hasFile('cover')){
                $result = Validator::make($request->all(), [
                    'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
            }
        } else {
            $result = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if($request->hasFile('cover')){
                $result = Validator::make($request->all(), [
                    'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
            }
        }

        return $result;
    }
    protected function findById($id)
    {
        $model = clone $this->model;
        return $model->where('id', $id)->first();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = $this->name;
        $data['breadcrumb'] = $this->breadcrumb;
        if($request->has('search')){
            $data['groupWords'] = GroupWord::where('user_id',auth()->user()->id)

            ->where(function($query){
                $query->where('name','like','%'.request()->search.'%')
                ->orWhere('description','like','%'.request()->search.'%');

            })->paginate(8);
        }else{
            $data['groupWords'] = GroupWord::where('user_id',auth()->user()->id)->paginate(8);
        }
        $data['createLink'] = $this->createLink;
        $data['view'] = $this->view;
        return view($this->view.'.index', $data);
    }

    public function search(Request $request)
    {
        $data['title'] = $this->name;
        $data['breadcrumb'] = $this->breadcrumb;

        if($request->has('search')){
            $data['groupWords'] = GroupWord::where('user_id',"!=",auth()->user()->id)
            ->where('access',1)
            ->where(function($query){
                $query->where('name','like','%'.request()->search.'%')
                ->orWhere('description','like','%'.request()->search.'%');

            })->orderBy('clone')->paginate(8);
        }else{
            $data['groupWords'] = GroupWord::where('user_id',"!=",auth()->user()->id)->where('access',1)->orderBy('clone')->paginate(8);
        }
        $data['createLink'] = $this->createLink;
        $data['view'] = $this->view;
        return view($this->view.'.search', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $v = $this->validateRequest($request, 'create');
            if ($v->fails()) {
                throw new CustomException("error", 422, null, $v->errors()->all());
            }
            $item = $this->newModel;
            $item->name = $request->name;
            $item->slug = GlobalFunction::makeSlug($this->newModel,$request->name);
            $item->description = $request->description;
            $item->access = $request->access;
            $item->user_id = auth()->user()->id;
            if ($request->hasFile('cover')) {
                $item->image = GlobalFunction::storeSingleImage($request->file('cover'), 'group-word');
            }
            $item->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($e->getOptions(), 500);
        } catch (CustomException $e) {
            DB::rollback();
            return response()->json($e->getOptions(),$e->getCode());
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return response()->json(['message' => "$this->name has been created !", "data" => $item ,'status'=>200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $groupWord = $this->findById($id);
        return $groupWord;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $v = $this->validateRequest($request, 'edit');
            if ($v->fails()) {
                throw new CustomException('error', 401, null, $v->errors()->all());
            }
            $item = $this->findById($id);
            $item->name = $request->name;
            $item->slug = GlobalFunction::makeSlug($this->newModel,$request->name);
            $item->description = $request->description;
            $item->access = $request->access;
            $item->user_id = auth()->user()->id;
            if ($request->hasFile('cover')) {
                $item->image = GlobalFunction::updateSingleImage($request->file('cover'), 'group-word',$item->image);
            }
            $item->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($e->getOptions(), 500);
        } catch (CustomException $e) {
            DB::rollback();
            return response()->json($e->getOptions(),$e->getCode());
        }
        return response()->json(['message' => "$this->name has been updated !", "data" => $item,'status'=>200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $item = $this->findById($id);
            if(!$item){
                throw new CustomException("error", 404, null, ["Data not found"]);
            }
            if($item->image){
                GlobalFunction::deleteSingleImage($item->image);
            }
            $item->delete();
            return response()->json(['message' => "$this->name berhasil dihapus gan !",'status'=>200], 200);
        }catch (Exception $e) {
            return response()->json($e->getOptions(), 500);
        } catch (CustomException $e) {
            return response()->json($e->getOptions(), 500);
        }
    }


   public function clone ($id){
    $item = $this->findById($id);
    $item->clone = $item->clone + 1;
    $item->save();
    $newItem = new GroupWord();
    $newItem->name = $item->name;
    $newItem->slug = GlobalFunction::makeSlug($this->newModel,$item->name);
    $newItem->description = $item->description;
    $newItem->access = $item->access;
    $newItem->user_id = auth()->user()->id;
    $newItem->image = $item->image;
    $newItem->save();
    foreach ($item->words as $value) {
        $newWord = new \App\Models\Word();
        $newWord->group_word_id = $newItem->id;
        $newWord->word = $value->word;
        $newWord->save();
    }
    return response()->json(['message' => 'Data berhasil diclone', 'status' => 200]);
   }
}
