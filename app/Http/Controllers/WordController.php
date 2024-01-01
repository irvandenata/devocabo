<?php

namespace App\Http\Controllers;
use App\Exceptions\CustomException;
use App\Helpers\GlobalFunction;
use App\Helpers\TranslateTextHelper;
use App\Http\Controllers\Controller;
use App\Models\GroupWord;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WordController extends Controller
{
    protected $name = 'Word';
    protected $breadcrumb = '<strong>Data</strong> Word';
    protected $modul = 'message';
    protected $route = 'messages';
    protected $view = 'word';
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
        $this->newModel = new Word();
        $this->model = Word::query()->latest();
        $this->rows = [
            'name'=>['Kata'],
            'column' => ['word']
        ];

        $this->updateLink = 'words.update';
        $this->editLink = 'words.edit';
    }

    protected static function validateRequest($request, $type)
    {
        if ($type == 'create') {

            $result = Validator::make($request->all(), [
                'word' => 'required',
                'group_word_id' => 'required|exists:group_words,id'
            ]);
        } else {
            $result = Validator::make($request->all(), [
                'word' => 'required',
            ]);
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
    public function index(Request $request,$id)
    {
        $data['title'] = $this->name;
        $data['breadcrumb'] = $this->breadcrumb;
        if ($request->ajax()) {
            $group = GroupWord::where('slug', $id)->first();
            $items = $this->model->where('group_word_id',$group->id)->latest();
            return DataTables::of($items)
                ->addColumn('action', function ($item) use ($group) {
                    if(auth()->user()->id == $group->user_id){
                        return '
                           <a class="inline-flex items-center p-2 text-sm font-medium bg-gray  text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:bg-orange hover:text-white cursor-pointer"  onclick="deleteItem(' . $item->id . ')">Hapus</a>';
                    }else{
                        return '-';
                    }

                })
                ->removeColumn('id')
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['groupId'] = $id;
        $data['group'] = GroupWord::where('slug', $id)->first();
        $data['createLink'] = $this->createLink;
        $data['rows'] = $this->rows;
        $data['view'] = $this->view;
        return view($this->view.'.index', $data);
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
    public function store(Request $request,$slug)
    {
        TranslateTextHelper::setSource('en')->setTarget('id');
        DB::beginTransaction();
        try {
            $translatedText = TranslateTextHelper::translate($request->word);
            $v = $this->validateRequest($request, 'create');
            if ($v->fails()) {
                throw new CustomException("error", 422, null, $v->errors()->all());
            }
            $item = $this->newModel;
            $item->word = $translatedText;
            $item->group_word_id = $request->group_word_id;
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
        $data['group']= GroupWord::where('slug',$id)->first();
        $data['words'] = $data['group']->words;
        //mapping words
        $country1 = request()->country1??'id';
        $country2 = request()->country2??'en';
        // dd(TranslateTextHelper::setSource('ind')->setTarget('en')->translate("lari"));
        $data['words'] = $data['words']->map(function ($item) use ($country1,$country2) {
            $word = $item->word;
            $item->word = TranslateTextHelper::setSource('id')->setTarget($country1)->translate($word);
            $item->mean = TranslateTextHelper::setSource('id')->setTarget($country2)->translate($word);
            return $item;
        });
        return view($this->view.'.detail',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoryQuestion = $this->findById($id);
        return $categoryQuestion;
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
            $item->description = $request->description;
            $item->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($e->getOptions(), 500);
        } catch (CustomException $e) {
            DB::rollback();
            return response()->json($e->getOptions(),$e->getCode());
        }
        return response()->json(['message' => "$this->name has been updated !", "data" => $item], 200);
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
            $item->delete();
            return response()->json(['message' => "$this->name berhasil dihapus gan !",'status'=>200], 200);
        }catch (Exception $e) {
            return response()->json($e->getOptions(), 500);
        } catch (CustomException $e) {
            return response()->json($e->getOptions(), 500);
        }
    }


    public function changeShow ($id){
        $item = $this->findById($id);
        if ($item->status == 1) {
            $item->status = 0;
        } else {
            $item->status = 1;
        }
        $item->save();
        return response()->json(['message' => 'Data berhasil diubah', 'status' => 200]);
    }
}
