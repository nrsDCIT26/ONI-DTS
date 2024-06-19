<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Document;
use App\File;
use App\Http\Requests\UpdateProfileRequest;
use App\Rules\CurrentPassword;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use ZipArchive;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('auth');*/
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $documents = Document::all();
        
        // Fetch activities with filters
        $activities = Activity::with(['createdBy', 'document']);
        if ($request->has('activity_range')) {
            $dates = explode("to", $request->get('activity_range'));
            $activities->whereDate('created_at', '>=', $dates[0] ?? '');
            $activities->whereDate('created_at', '<=', $dates[1] ?? '');
        }
        $activities = $activities->orderByDesc('id')->paginate(25);
        
        // Fetch tags and related document counts
        $allTags = Tag::with('documents.files')->withCount('documents')->get();
        $tagCounts = $allTags->count();
        
        // Fetch documents and related file counts
        $allDocs = Document::whereHas('tags', function ($q) use ($allTags) {
            return $q->whereIn('tag_id', $allTags->pluck('id')->toArray());
        })->pluck('id');

        if ($user) {
            if (auth()->user()->is_super_admin) {
                // If user is a super admin, count all documents
                $documentCounts = Document::whereHas('tags', function ($q) use ($allTags) {
                    $q->whereIn('tag_id', $allTags->pluck('id')->toArray());
                })->count();
                
                // Count all related files
                $filesCounts = File::whereIn('document_id', $allDocs->toArray())->count();
            } else {
                // Count documents associated with the currently logged-in user
                $documentCounts = Document::where('created_by', $user->id)
                    ->whereHas('tags', function ($q) use ($allTags) {
                        $q->whereIn('tag_id', $allTags->pluck('id')->toArray());
                    })->count();
        
                // Count files related to the documents owned by the user
                $userDocs = Document::where('created_by', $user->id)
                    ->whereHas('tags', function ($q) use ($allTags) {
                        $q->whereIn('tag_id', $allTags->pluck('id')->toArray());
                    })->pluck('id');
                    
                $filesCounts = File::whereIn('document_id', $userDocs->toArray())->count();
            }
        } else {
            // Handle case where user is not logged in
            $documentCounts = 0;
            $filesCounts = 0;
        }

        // Fetch monthly activities count
        $monthlyActivities = Activity::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                    ->where('created_by', $user->id)
                                    ->whereYear('created_at', date('Y'))
                                    ->groupBy('month')
                                    ->get();
    
        // Transform numeric month to month name
        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
        $monthlyActivities->transform(function ($item) use ($monthNames) {
            $item->month_name = $monthNames[$item->month];
            return $item;
        });
    
        // Fetch uploaded documents grouped by month
        $uploadedDocuments = Document::where('created_by', $user->id)
                            ->whereYear('created_at', date('Y'))
                            ->get()
                            ->groupBy(function ($date) {
                                return Carbon::parse($date->created_at)->format('m');
                            });
    
        // Initialize uploaded documents data with all months
        $uploadedDocumentsData = [];
        foreach ($monthNames as $monthNum => $monthName) {
            $key = str_pad($monthNum, 2, '0', STR_PAD_LEFT); // Ensure two-digit month format
            $count = isset($uploadedDocuments[$key]) ? $uploadedDocuments[$key]->count() : 0;
            $uploadedDocumentsData[] = [
                'month' => $key,
                'month_name' => $monthName,
                'count' => $count,
            ];
        }
    
        // Pass all necessary data to the view
        return view('home', compact('documents', 'activities', 'tagCounts', 'documentCounts', 'filesCounts', 'monthlyActivities', 'uploadedDocumentsData'));
    }     

    public function welcome()
    {
        \Artisan::call("inspire");
        return view('welcome');
    }

    public function profile(UpdateProfileRequest $request)
    {
        $profile = User::findOrFail(\Auth::id());
        $data = $request->all();
        if($request->isMethod('POST')){
            if($request->has('btnprofile')){
                \Flash::success("Profile Updated Successfully");
            }elseif ($request->has('btnpass')){
                $data['password'] = bcrypt($data['new_password']);
                \Flash::success('Password Updated Successfully');
            }
            $profile->update($data);
            return redirect()->route('profile.manage');
        }

        return view('profile',compact('profile'));
    }

    /**
     * Show Or Download File.
     * @param Request $request
     * @param string $dir
     * @param null $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function showFile(Request $request, $dir = 'original', $file = null)
    {
        $name = $file;
        $attachment = 'inline';
        if($request->has('force')){//for force download
            $attachment = 'attachment';
        }
        if (!empty($file)) {
            $fileModels = File::where('file', $file)->get();
            if ($fileModels->isNotEmpty()) {
                $fileModel = $fileModels[0];
                $name = Str::slug($fileModel->document->name). "_" .$fileModel->document->id . "_" . $dir . "_" . Str::slug($fileModel->name);
                $name .= "." . last(explode('.', $file));
            }
        }
        $file = storage_path('app/files/' . $dir . '/') . $file;
        return response()->file($file, ['Content-disposition' => $attachment.'; filename="' . $name . '"']);
    }

    public function downloadZip(Request $request, $id, $dir = 'all')
    {
        $document = Document::findOrFail($id);
        $tmpDir = storage_path('app/tmp/');
        if(!file_exists($tmpDir)){
            mkdir($tmpDir,0755,true);
        }
        $docFileTitle = Str::slug($document->name)."_".Str::slug($dir)."_".$document->id.".zip";
        $zip_file = $tmpDir.$docFileTitle;

        $directories = [];
        $imageVariants = explode(",",config('settings.image_files_resize'));
        if($dir=='all' || $dir=='original'){
            $directories[] = "original";
        }else{
            $directories[] = $dir;
        }
        if($dir=='all'){
            foreach ($imageVariants as $imageVariant) {
                $directories[] = $imageVariant;
            }
        }

        /*Create a zip archive*/
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if(!empty($dir) && !empty($directories)){
            foreach ($directories as $directory) {
                foreach ($document->files as $file) {
                    $newName = $directory."/".Str::slug($file->name). "_" .$file->id;
                    $newName .= "." . last(explode('.', $file->file));
                    $existingFile = storage_path("app/files/$directory/$file->file");
                    if(file_exists($existingFile)) {
                        $zip->addFile($existingFile, $newName);
                    }
                }
            }
        }

        $zip->close();
        return response()->download($zip_file)->deleteFileAfterSend();
    }

    public function downloadPdf(Request $request)
    {
        $files = $request->get('images','');
        $varient = $request->get('images_varient','original');
        if(empty($files)){
            return redirect()->back();
        }
        $files = explode(",",$files);
        $docName = Document::whereHas('files',function ($q) use ($files){
            return $q->where('file',$files[0]);
        })->pluck('name')->first();
        $docName = Str::slug($docName)."_".$varient;
        $filePaths = [];
        foreach ($files as $file) {
            $filePaths[] = Image::make(storage_path("app/files/$varient/$file"))->encode('data-url');
        }
        $pdf = PDF::loadView('pdf', compact('docName','filePaths'));
        return $pdf->download($docName.".pdf");
    }
}
