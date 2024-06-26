<?php

namespace App\Http\Controllers;

use App\CustomField;
use App\Document;
use App\File;
use App\FileType;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\CreateFilesRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\FileTypeRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\TagRepository;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Spatie\Permission\Models\Permission;

class DocumentController extends Controller
{
    /** @var  TagRepository */
    private $tagRepository;

    /** @var DocumentRepository */
    private $documentRepository;

    /** @var CustomFieldRepository */
    private $customFieldRepository;

    /** @var FileTypeRepository */
    private $fileTypeRepository;

    /** @var PermissionRepository $permissionRepository */
    private $permissionRepository;

    public function __construct(TagRepository $tagRepository,
                                DocumentRepository $documentRepository,
                                CustomFieldRepository $customFieldRepository,
                                FileTypeRepository $fileTypeRepository,
                                PermissionRepository $permissionRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->documentRepository = $documentRepository;
        $this->customFieldRepository = $customFieldRepository;
        $this->fileTypeRepository = $fileTypeRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $this->authorize('viewAny', Document::class);
        
        // Get the currently authenticated user's id
        $userId = auth()->id();
        
        // Fetch received documents for the current user
        $rcvDocuments = DB::table('received_documents')
                        ->where('receiver_id', $userId)
                        ->get();
        
        // Paginate documents
        $documents = $this->documentRepository->searchDocuments(
            $request->get('search'),
            $request->get('tags'),
            $request->get('status')
         );
        
        $tags = $this->tagRepository->all();
        
        return view('documents.index', compact('rcvDocuments', 'documents', 'tags'));
    }
    public function recievedindex(Request $request)
    {
        $this->authorize('viewAny', Document::class);
        
        // Get the currently authenticated user's id
        $userId = auth()->id();
        
        // Fetch received documents for the current user excluding those they created
        $rcvDocuments = DB::table('received_documents')
                        ->join('documents', 'received_documents.document_id', '=', 'documents.id')
                        ->where('received_documents.receiver_id', $userId)
                        ->where('documents.created_by', '!=', $userId)
                        ->select('received_documents.*')
                        ->get();
        
        // Paginate documents
        $documents = $this->documentRepository->searchDocuments(
            $request->get('search'),
            $request->get('tags'),
            $request->get('status')
        );
        
        $tags = $this->tagRepository->all();
        
        return view('documents.received_index', compact('rcvDocuments', 'documents', 'tags'));
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Document::class);
        $tags = $this->tagRepository->all();
        $users = User::where('id', '!=', auth()->id())->get();
        $customFields = $this->customFieldRepository->getForModel('documents');
        return view('documents.create', compact('tags', 'customFields','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDocumentRequest $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['status'] = config('constants.STATUS.PENDING');
        
        // Generate document ID
        $currentYear = date('Y');
        $documentsCount = Document::whereYear('created_at', $currentYear)->count() + 1;
        $documentId = sprintf("%s-%02d%02d-%03d", $currentYear, now()->format('m'), now()->format('d'), $documentsCount);
        $data['document_id'] = $documentId;
    
        // Convert tags_string to an array
        if (isset($data['tags_string']) && is_string($data['tags_string'])) {
            $tags = explode(',', $data['tags_string']);
        } else {
            $tags = [];
        }
    
        // $this->authorize('store', [Document::class, $tags]);
    
        $document = $this->documentRepository->createWithTags($data);
        Flash::success(ucfirst(config('settings.document_label_singular')) . " Saved Successfully");
        $document->newActivity(ucfirst(config('settings.document_label_singular')) . " Created");
    
        foreach (config('constants.DOCUMENT_LEVEL_PERMISSIONS') as $perm_key => $perm) {
            $permissionName = $perm_key . $document->id;
    
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create(['name' => $permissionName]);
            }      
        } 
    
        $receiverId = Auth::id();  
    
        // Process tags if they exist
        if (!empty($tags)) {
            foreach ($tags as $tagId) {
                $receiver_id = $tagId;
                DB::table('received_documents')->insert([
                    'document_id' => $document->id,
                    'creator_id' => $receiverId, 
                    'sender_id' => $receiverId, 
                    'receiver_id' => $receiver_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        if ($request->has('savnup')) {
            return redirect()->route('documents.files.create', $document->id);
        }
        return redirect()->route('documents.index');
    }
    
        
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var Document $document */
        $document = $this->documentRepository
            ->getOneEagerLoaded($id,['files', 'files.fileType', 'files.createdBy', 'activities', 'activities.createdBy', 'tags']);
        if (empty($document)) {
            abort(404);
        }
        $this->authorize('viewAny', $document);

        // $missigDocMsgs = $this->documentRepository->buildMissingDocErrors($document);
        $dataToRet = compact('document');

        if (auth()->user()->can('user manage permission') || auth()->user()->can('view', $document)) {
            $users = User::where('id', '!=', 1)->get();
            $thisDocPermissionUsers = $this->permissionRepository->getUsersWiseDocumentLevelPermissionsForDoc($document);
            //Tag Level permission
            $tagWisePermList = $this->permissionRepository->getTagWiseUsersPermissionsForDoc($document);
            //Global Permission
            $globalPermissionUsers = $this->permissionRepository->getGlobalPermissionsForDoc($document);

            $dataToRet = array_merge($dataToRet, compact('users', 'thisDocPermissionUsers', 'tagWisePermList', 'globalPermissionUsers'));
        }
        return view('documents.show', $dataToRet);
    }

    public function storePermission($id, Request $request)
    {
        abort_if(!auth()->user()->can('user manage permission'), 403, 'This action is unauthorized .');
        $input = $request->all();
        $user = User::findOrFail($input['user_id']);
        $doc_permissions = $input['document_permissions'];
        $document = Document::findOrFail($id);
        $this->permissionRepository->setDocumentLevelPermissionForUser($user,$document,$doc_permissions);
        Flash::success(ucfirst(config('settings.document_label_singular')) . " Permission allocated");
        return redirect()->back();
    }

    public function deletePermission($documentId, $userId)
    {
        abort_if(!auth()->user()->can('user manage permission'), 403, 'This action is unauthorized.');
        $user = User::findOrFail($userId);
        $document = Document::findOrFail($documentId);
        $this->permissionRepository->deleteDocumentLevelPermissionForUser($document,$user);
        Flash::success(ucfirst(config('settings.document_label_singular')) . " Permission removed");
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $this->authorize('edit', $document);
        $tags = Tag::all();
        $customFields = $this->customFieldRepository->getForModel('documents');
        return view('documents.edit', compact('tags', 'customFields', 'document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentRequest $request, $id)
    {
        $document = Document::findOrFail($id);
        $data = $request->all();
        $this->authorize('update', [$document, $data['tags']]);
        $this->documentRepository->updateWithTags($data,$document);
        $document->newActivity(ucfirst(config('settings.document_label_singular')) . " Updated");
        Flash::success(ucfirst(config('settings.document_label_singular')) . " Updated Successfully");
        return redirect()->route('documents.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $this->authorize('delete', $document);
        $document->newActivity(ucfirst(config('settings.document_label_singular')) . " Deleted");
        $this->documentRepository->deleteWithFiles($document,true);
        Flash::success(ucfirst(config('settings.document_label_singular')) . " Deleted Successfully");
        return redirect()->route('documents.index');
    }

    public function verify($id, Request $request)
    {
        $document = Document::findOrFail($id);
        $this->authorize('verify', $document);
        $action = $request->get('action');
        $comment = $request->get('vcomment', "");
    
        // Get the authenticated user's name
        $userName = auth()->user()->name;
    
        if (!empty($comment)) {
            $comment = " by $userName: <i>" . $comment . "</i>";
        } else {
            $comment = " by $userName";
        }
    
        $msg = "";
        if ($action == 'approve') {
            $this->documentRepository->approveDoc($document);
            $msg = "Approved";
        } elseif ($action == 'decline') {
            $this->documentRepository->rejectDoc($document);
            $msg = "Declined";
        } elseif ($action == 'approvef') {
            $this->documentRepository->approvedFDoc($document);
            $msg = "Forwarded to" ."</i>";
        } elseif ($action == 'return') {
            $this->documentRepository->returnDoc($document);
            $msg = "Returned";
        } else {
            abort(404);
        }
        $document->newActivity(" $msg $comment");
    
        Flash::success(ucfirst(config('settings.document_label_singular')) . " $msg Successfully");
        return redirect()->back();
    }    

    public function showUploadFilesUi($id)
    {
        $document = Document::find($id);
        if(empty($document)){
            Flash::error("Oh No..., try to create some ".config('settings.document_label_singular')." before uploading ".config('settings.file_label_plural'));
            return redirect()->route('documents.index');
        }
        $this->authorize('update', [$document, $document->tags->pluck('id')]);
        $fileTypes = FileType::pluck('name', 'id');
        $customFields = $this->customFieldRepository->getForModel('files');
        return view('documents.file_upload', compact('document', 'fileTypes', 'customFields'));
    }

    public function storeFiles($id, CreateFilesRequest $request)
    {
        $document = Document::findOrFail($id);
        $userName = auth()->user()->name;
        $this->authorize('update', [$document, $document->tags->pluck('id')]);
        $filesData = $request->all('files')['files'] ?? [];
        /* Prepare final data */
        $filesData = $this->prepareFilesData($filesData);
        $this->documentRepository->saveFilesWithDoc($filesData, $document);
        $document->newActivity(count($filesData) . " New " . ucfirst(config('settings.file_label_plural')) . " Uploaded<br><span style='font-size: 80%;'>by: $userName</span>");
        Flash::success(ucfirst(config('settings.file_label_plural')) . " Uploaded Successfully");
        if (!$request->ajax()) {
            return redirect()->route('documents.show', ['id' => $document->id]);
        } else {
            return ["msg" => "Success"];
        }
    }

    private function prepareFilesData($filesData){
        $imageVariants = explode(',', config('settings.image_files_resize'));
        foreach ($filesData as $i => $fileData) {
            /** @var UploadedFile $file */
            $file = $filesData[$i]['file'];
            $filePath = $file->store('files/original');
            if (isImage($file->getMimeType())) {
                /*Image intervention resize*/
                foreach ($imageVariants as $imageVariant) {
                    $resizeSavePath = "app/files/$imageVariant/";
                    if (!file_exists(storage_path($resizeSavePath))) {
                        mkdir(storage_path($resizeSavePath), 0755, true);
                    }
                    $imageIntervention = Image::make(storage_path('app/' . $filePath));
                    $imageIntervention->resize($imageVariant, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(storage_path($resizeSavePath . $file->hashName()));
                }

                //create thumb
                $thumbPath = "app/files/thumb/";
                if (!file_exists(storage_path($thumbPath))) {
                    mkdir(storage_path($thumbPath), 0755, true);
                }
                Image::make(storage_path('app/' . $filePath))
                    ->resize(193, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(storage_path($thumbPath . $file->hashName()));
            }

            $filesData[$i]['custom_fields'] = json_encode($filesData[$i]['custom_fields'] ?? []);
            $filesData[$i]['file'] = $file->hashName();
            $filesData[$i]['created_by'] = Auth::id();
            $filesData[$i]['created_at'] = now();
            $filesData[$i]['updated_at'] = now();
        }

        return $filesData;
    }

    public function storePermissionAndVerify($id, Request $request)
    {

        abort_if(!auth()->user()->can('user manage permission'), 403, 'This action is unauthorized .');
        $input = $request->all();
        $user = User::findOrFail($input['user_id']);
        $doc_permissions = $input['document_permissions'];
        $document = Document::findOrFail($id);
        $this->permissionRepository->setDocumentLevelPermissionForUser($user, $document, $doc_permissions);
        
        $action = $request->get('action');
        $comment = $request->get('vcomment', "");
    
        $userName = auth()->user()->name;
    
        if (!empty($comment)) {
            $comment = " by $userName: <i>" . $comment . "</i>";
        } else {
            $comment = " by $userName";
        }
    
        $msg = "";
        if ($action == 'approve') {

            $this->documentRepository->approveDoc($document);
            $msg = "Approved";

        } elseif ($action == 'decline') {

            $this->documentRepository->declineDoc($document);
            $msg = "Declined";

        } elseif ($action == 'approvef') {

            $this->documentRepository->approvedFDoc($document);
            $selectedUserId = $request->get('user_id');
            $selectedUser = User::findOrFail($selectedUserId);
            $selectedUserName = $selectedUser->name;    
            $msg = "Forwarded to $selectedUserName";
            
        } elseif ($action == 'return') {

            $this->documentRepository->returnDoc($document);
            $msg = "Returned";
            
        } else {
            abort(404);
        }
        $document->newActivity(" $msg $comment");
    
        Flash::success(ucfirst(config('settings.document_label_singular')) . " $msg Successfully");
        return redirect()->back();
    }
    
    public function deleteFile($id)
    {
        $file = File::findOrFail($id);
        $userName = auth()->user()->name;
        $this->authorize('delete', $file->document);
        $file->document->newActivity($file->name . " Deleted " . "<br><span style='font-size: 80%;'>by: $userName</span>");
        $this->documentRepository->deleteFile($file);
        Flash::success(ucfirst(config('settings.file_label_singular')) . " Deleted Successfully");
        return redirect()->back();
    }
}
