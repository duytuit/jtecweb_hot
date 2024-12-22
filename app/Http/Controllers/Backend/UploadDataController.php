<?php
namespace App\Http\Controllers\Backend;

use App\Helpers\ArrayHelper;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\uploadData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class UploadDataController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // Phân trang
       $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
       $data['keyword'] = $request->input('keyword', null);
       $data['advance'] = 0;
       if (count($request->except('keyword')) > 0) {
           // Tìm kiếm nâng cao
           $data['advance'] = 1;
           $data['filter'] = $request->all();
       }
       $data['models'] = ArrayHelper::getModels();
       $data['lists'] = uploadData::where(function ($query) use ($request) {
            if (isset($request->code) && $request->code != null) {
                $query->where('code', 'like','%'.$request->code.'%');
            }
            if (isset($request->type) && $request->type != null) {
                $query->where('type', $request->type);
            }
           if (isset($request->status) && $request->status != null) {
               $query->where('status', $request->status);
           }
       })->orderBy('updated_at','desc')->paginate($data['per_page']);
       return view('backend.pages.uploadData.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('upload_data.create')) {
            return abort(403, 'Bạn không có quyền truy cập trang này !');
        }
        return view('backend.pages.uploadData.create');
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function restartWebPdf(Request $request)
    {
        $scriptPath = 'D:\\JtecData\\JTEC_PD_PROGAM\\CMSWeb\\ResetWebsitePdfToPng.ps1';

        // Command to execute the PowerShell script
        $command = "powershell.exe -ExecutionPolicy Bypass -File $scriptPath";

        // Execute the command and capture the output
        $output = shell_exec($command);

        return redirect()->back()->with('success', $output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $scriptPath = 'D:\\JtecData\\JTEC_PD_PROGAM\\CMSWeb\\ResetWebsitePdfToPng.ps1';
        // Command to execute the PowerShell script
        $command = "powershell.exe -ExecutionPolicy Bypass -File $scriptPath";
        // Execute the command and capture the output
        $output = shell_exec($command);
        $getFile = @$request->filepdf1 ?? @$request->filepdf2 ?? @$request->filepdf3;
        $infoFile = pathinfo( $getFile->getClientOriginalName());
        $_uploadData = uploadData::where('code',$infoFile['filename'])->first();
        if($_uploadData){
            $_path_file = '//192.168.207.6/jtecdata/JTEC_PD_PROGAM/CMSWeb/jtecweb/public/public/data_laprap/'.$infoFile['filename'].'-v'.($_uploadData->version+1).'.pdf';
            $_path_file_db = 'public/data_laprap/'.$infoFile['filename'].'-v'.($_uploadData->version+1).'.pdf';
        }else{
            $_path_file = '//192.168.207.6/jtecdata/JTEC_PD_PROGAM/CMSWeb/jtecweb/public/public/data_laprap/'.$infoFile['filename'].'-v1.pdf';
            $_path_file_db = 'public/data_laprap/'.$infoFile['filename'].'-v1.pdf';
        }

        $post_fields['PdfFile'] = new \CurlFile($getFile->path(), $getFile->getClientMimeType(), $getFile->getClientOriginalName());
        $post_fields['OutputPath'] = $_path_file;
        $post_fields['X'] = '0';
        $post_fields['Y'] = (@$request->filepdf1 ? '940' : (@$request->filepdf2 ? '1330' : '1440'));
        $post_fields['Width'] = '2384';
        $post_fields['Height'] =   (@$request->filepdf1 ? '1485' : (@$request->filepdf2 ? '710' : '490'));
        $curl_handle = curl_init('http://192.168.207.6:8092/pdfcrop/to-pdf');
        curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        curl_setopt($curl_handle, CURLOPT_VERBOSE, 0);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl_handle, CURLOPT_POST, true);
        @curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_fields);
        $returned_data = curl_exec($curl_handle);
        curl_close($curl_handle);
        if($returned_data != 'thành công'){
            return $this->success([
                'status'=>false,
                'message'=>json_encode($returned_data)
            ]);
        }
        try {

            if(!$_uploadData){
                uploadData::create([
                    'code'=> $infoFile['filename'],
                    'url'=>  $_path_file_db,
                    'type'=> (@$request->filepdf1 ? 1 : (@$request->filepdf2 ? 2 : 3)),
                    'created_by'=>Auth::user()->id,
                    'version'=>1,
                ]);
            }else{
                $_uploadData->url =  $_path_file_db;
                $_uploadData->type = (@$request->filepdf1 ? 1 : (@$request->filepdf2 ? 2 : 3));
                $_uploadData->updated_by = Auth::user()->id;
                $_uploadData->version = $_uploadData->version+1;
                $_uploadData->save();
            }

            unlink($getFile->path());
            return $this->success([
                'status'=>true,
                'message'=>'Tải dữ liệu thành công !'
            ]);
        } catch (\Exception $e) {
            return $this->success([
                'status'=>false,
                'message'=>$e->getMessage()
            ]);
        }
    }
    public function store_new(Request $request)
    {
        $upload_file = null;
        $infoFile = null;
        $_uploadData=null;
        if (!is_null($request->filepdf1)) {
            $infoFile = pathinfo($request->file('filepdf1')->getClientOriginalName());
            $_uploadData = uploadData::where('code',$infoFile['filename'])->first();
            if(!$_uploadData){
                $upload_file = UploadHelper::upload('file', $request->file('filepdf1'), $infoFile['filename'].'-v1', 'public/data_laprap');
            }else{
                $upload_file = UploadHelper::upload('file',$request->file('filepdf1'), $infoFile['filename'].'-v'.($_uploadData->version+1), 'public/data_laprap');
            }

        }
        if (!is_null($request->filepdf2)) {
            $infoFile = pathinfo($request->file('filepdf2')->getClientOriginalName());
            $_uploadData = uploadData::where('code',$infoFile['filename'])->first();
            if(!$_uploadData){
                $upload_file = UploadHelper::upload('file', $request->file('filepdf1'), $infoFile['filename'].'-v1', 'public/data_laprap');
            }else{
                $upload_file = UploadHelper::upload('file',$request->file('filepdf1'), $infoFile['filename'].'-v'.($_uploadData->version+1), 'public/data_laprap');
            }
        }
        if (!is_null($request->filepdf3)) {
            $infoFile = pathinfo($request->file('filepdf3')->getClientOriginalName());
            $_uploadData = uploadData::where('code',$infoFile['filename'])->first();
            if(!$_uploadData){
                $upload_file = UploadHelper::upload('file', $request->file('filepdf1'), $infoFile['filename'].'-v1', 'public/data_laprap');
            }else{
                $upload_file = UploadHelper::upload('file',$request->file('filepdf1'), $infoFile['filename'].'-v'.($_uploadData->version+1), 'public/data_laprap');
            }
        }
        try {
            if($upload_file){
                if(!$_uploadData){
                    uploadData::create([
                        'code'=> $infoFile['filename'],
                        'url'=>  'public/data_laprap/'.$infoFile['filename'].'-v1.'.$infoFile['extension'],
                        'type'=> (@$request->filepdf1 ? 1 : (@$request->filepdf2 ? 2 : 3)),
                        'created_by'=>Auth::user()->id,
                        'version'=>1,
                    ]);
                }else{
                    $_uploadData->url =  'public/data_laprap/'.$infoFile['filename'].'-v'.($_uploadData->version+1).'.'.$infoFile['extension'];
                    $_uploadData->type = (@$request->filepdf1 ? 1 : (@$request->filepdf2 ? 2 : 3));
                    $_uploadData->updated_by = Auth::user()->id;
                    $_uploadData->version = $_uploadData->version+1;
                    $_uploadData->save();
                }
                return $this->success([
                    'status'=>true,
                    'message'=>'Tải dữ liệu thành công!'
                ]);
            }
            return $this->success([
                'status'=>true,
                'message'=>'Tải Thất bại!'
            ]);
        } catch (\Exception $e) {
            return $this->success([
                'status'=>false,
                'message'=>$e->getMessage()
            ]);
        }
    }
    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        } else {
            return back();
        }
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('upload_data.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $exam = uploadData::find($id);
        if (is_null($exam)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.uploadDatas.index');
        }
        $exam->deleted_at = Carbon::now();
        $exam->deleted_by =  Auth::user()->id;
        $exam->save();

        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.uploadDatas.index');
    }
     /**
     * destroyTrash
     *
     * @param integer $id
     * @return void Destroy the data permanently
     */
    public function destroyTrash($id)
    {
        if (is_null($this->user) || !$this->user->can('upload_data.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $record = uploadData::find($id);
        if (is_null($record)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.uploadDatas.index');
        }

        // Delete exam permanently
        $record->delete();

        session()->flash('success', 'Bản ghi đã được xóa!!');
        return redirect()->route('admin.uploadDatas.index');
    }
}
