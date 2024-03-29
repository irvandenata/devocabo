<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
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
            'name' => ['Kata'],
            'column' => ['word'],
        ];

        $this->updateLink = 'words.update';
        $this->editLink = 'words.edit';
    }

    protected static function validateRequest($request, $type)
    {
        if ($type == 'create') {

            $result = Validator::make($request->all(), [
                'word' => 'required',
                'group_word_id' => 'required|exists:group_words,id',
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
    public function index(Request $request, $id)
    {
        $data['title'] = $this->name;
        $data['breadcrumb'] = $this->breadcrumb;
        // $prep = $this->model->where('word','like','%determiner%')->get();
        // foreach ($prep as $key => $item) {
        //     $item->word = str_replace('determiner','',$item->word);
        //     $item->save();
        // }
        if ($request->ajax()) {
            $group = GroupWord::where('slug', $id)->first();
            $items = $this->model->where('group_word_id', $group->id)->latest();

            return DataTables::of($items)
                ->addColumn('action', function ($item) use ($group) {
                    if (auth()->user()->id == $group->user_id) {
                        return '
                           <a class="inline-flex items-center p-2 text-sm font-medium bg-gray  text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:bg-orange hover:text-white cursor-pointer"  onclick="deleteItem(' . $item->id . ')">Hapus</a>';
                    } else {
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
        return view($this->view . '.index', $data);
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
    public function store(Request $request, $slug)
    {
        TranslateTextHelper::setSource('id')->setTarget('en');
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
            return response()->json($e->getOptions(), $e->getCode());
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return response()->json(['message' => "$this->name has been created !", "data" => $item, 'status' => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['group'] = GroupWord::where('slug', $id)->first();

        $data['words'] = $data['group']->words()->orderBy('word','asc')->get();
        if(isset(request()->type) && (request()->type != -1 || request()->type == 0) ){
            $data['words'] = $data['group']->words()->where('type',request()->type)->orderBy('word','asc')->get();
        }
        // dd($data['words']);
        //mapping words
        $country1 = request()->country1 ?? 'id';
        $country2 = request()->country2 ?? 'en';
        $words = '';
        $count = 0;
        $resWord = '';
        $resMean = '';
        $i = 0;
        if(count($data['words']) != 0){
        foreach ($data['words'] as $key => $word) {
            $words .= $word->word . ' | ';
            if ($count > 1000) {
                $resWord .= TranslateTextHelper::setSource('en')->setTarget($country1)->translate($words);
                $resMean .= TranslateTextHelper::setSource('en')->setTarget($country2)->translate($words);
                $count = 0;
                $words = '';
                $i++;
            }
// if($i == 2){
//     $means= explode(',',$resMean);
//                 $words= explode(',',$resWord);
//                 dd($means,$words);
// }
            $count++;
        }

        $resWord .= TranslateTextHelper::setSource('en')->setTarget($country1)->translate($words);
        $resMean .= TranslateTextHelper::setSource('en')->setTarget($country2)->translate($words);
        $words = str_replace(' | ', '|', $resWord);
        $means = str_replace(' | ', '|', $resMean);
        $words = explode('|', $resWord);
        $means = explode('|', $resMean);
        // dd(count($words),count($means));
        //mapping means
        foreach ($words as $key => $word) {
            $data['means'][$key]['word'] = $words[$key];
            $data['means'][$key]['mean'] = $means[$key];
        }
        }else{
            $data['means'] = [
                [
                    'word' => 'Data Kosong',
                    'mean' => 'Data Kosong',
                ],
            ];
        }
        //last means
        $data['means'] = array_slice($data['means'], 0, -1);
        $data['amount'] = count($data['means']);
        return view($this->view . '.detail', $data);
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
            return response()->json($e->getOptions(), $e->getCode());
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
            if (!$item) {
                throw new CustomException("error", 404, null, ["Data not found"]);
            }
            $item->delete();
            return response()->json(['message' => "$this->name berhasil dihapus gan !", 'status' => 200], 200);
        } catch (Exception $e) {
            return response()->json($e->getOptions(), 500);
        } catch (CustomException $e) {
            return response()->json($e->getOptions(), 500);
        }
    }

    public function changeType(Request $request,$word,$group,$lang)
    {
        $word = TranslateTextHelper::setSource($lang)->setTarget('en')->translate($word);
        $item = Word::where('word',$word)->where('group_word_id',$group);
        if($request->old_type != -1 ){
            $item = $item->where('type',$request->old_type);
        }
        $item = $item->first();
        $item->type = $request->type;
        $item->save();
        if(!$item){
            return response()->json(['message' => "Tipe kata gagal diubah gan !", 'status' => 500], 500);
        }
        // $test = Word::where('word',$word)->where('group_word_id',$group)->first();
        // dd($test,$item);
        return response()->json(['message' => "Tipe kata berhasil diubah gan !", 'status' => 200,'data'
        => $item
    ], 200);
    }
}
