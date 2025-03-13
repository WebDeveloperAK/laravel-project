<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{

        public function onlineUsers(Request $request){
            $users = DB::table('users')
            // ->where('last_login_at', '>=', now()->subMinutes(20)) 
            ->where('id', $request->id)
            ->select('name','id') 
            ->get();
    
        return response()->json(['users' => $users]); 
        }

        public function typing(Request $request)
        {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
    
            if ($request->typing) {
                Cache::put("typing_{$user->id}", $user->name, now()->addSeconds(3));
    
                // Store the key in a separate list (array in cache)
                $typingUsers = Cache::get('typing_users', []);
                if (!in_array($user->id, $typingUsers)) {
                    $typingUsers[] = $user->id;
                    Cache::put('typing_users', $typingUsers, now()->addSeconds(3));
                }
            } else {
                Cache::forget("typing_{$user->id}");
    
                // Remove the key from the list
                $typingUsers = Cache::get('typing_users', []);
                if (($key = array_search($user->id, $typingUsers)) !== false) {
                    unset($typingUsers[$key]);
                    Cache::put('typing_users', array_values($typingUsers), now()->addSeconds(3));
                }
            }
    
            return response()->json(['success' => true]);
        }
    
        public function fetchTyping()
        {
            $typingUsers = [];

            $typingUserIds = Cache::get('typing_users', []);
        
            if (!is_array($typingUserIds)) {
                $typingUserIds = [];
            }
        
            foreach ($typingUserIds as $userId) {
                $userName = Cache::get("typing_{$userId}");
                if ($userName) {
                    $typingUsers[] = $userName;
                }
            }
        
            return response()->json(['typingUsers' => $typingUsers]);
        }
    
    
   
    public function activeUsers()
    {
        $id = auth()->id();
      
    $users =DB::table('message')
    ->where(function ($query) use ($id) {
        $query->orWhere('recivId', $id);
    })
    ->leftJoin('users', 'message.user_id', '=', 'users.id')
    ->select('message.*', 'users.name', 'users.id')
    ->whereRaw('message.id IN (SELECT MAX(id) FROM message GROUP BY user_id)') 
    ->orderBy('message.created_at', 'DESC') 
    ->get();

    $allusers = DB::table('users')
                    // ->leftJoin('users', 'message.user_id', '=', 'users.id')
                    ->select('users.name', 'users.id')
                    ->get();
                    return response()->json([
                        'users' => $users,
                        'allusers' => $allusers
                    ]);
    }
    

    public function index()
    {
        return view('message');
    }

    public function allmessages(Request $request){
        // return $allmessages = DB::table('message')
        // ->leftJoin('users', 'message.user_id', '=', 'users.id')
        // ->select('message.*', 'users.name')
        // ->get();
        if ($request->ajax()) {
            try {
                $allmessages =DB::table('message')
                ->leftJoin('users as u1', 'message.user_id', '=', 'u1.id')  
                ->leftJoin('users as u2', 'message.recivId', '=', 'u2.id')  
                ->select(
                    'message.*', 
                    'u1.name as sender_name', 
                    'u2.name as receiver_name'
                )
                ->get();
        
                return DataTables::of($allmessages)
                    ->editColumn('created_at', function ($message) {
                        return date('Y-m-d', strtotime($message->created_at));
                    })
                    ->addColumn('action', function ($message) {
                        return '<a href="" class="btn btn-primary btn-sm">Edit</a>
                                <a href="javascript:void(0);" onclick="deleteMessage('.$message->id.')" class="btn btn-danger btn-sm">Delete</a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        return view('all_messages');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            // 'file'    => 'nullable|image|mimes:jpeg,png,jpg,gif',
            
        ]);
        
        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audio = $request->file('audio');
            $audioName = time() . '.' . $audio->getClientOriginalExtension();
            $audio->move(public_path('uploads/audio'), $audioName);
            $audioPath = 'uploads/audio/' . $audioName;
        }
        
        $imagePath = null;
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/messages'), $imageName);
            $imagePath = 'uploads/messages/' . $imageName;
        }
        
        $messageId = DB::table('message')->insertGetId([
            'user_id'    => auth()->id(),
            'recivId'    => $request->recivId,
            'message'    => $request->message,
            'images'     => $imagePath,
            'audio'      => $audioPath,
            'created_at' => now()
        ]);
        
        return response()->json(['success' => true, 'messageId' => $messageId]);
        
        
    }

    public function fetchMessages()
    {
        $id = auth()->id();

        $messages = DB::table('message')
            ->where(function ($query) use ($id) {
                $query->where('user_id', $id) 
                      ->orWhere('recivId', $id); 
            })
            ->leftJoin('users', 'message.user_id', '=', 'users.id')
            ->select('message.*', 'users.name')
            ->orderBy('message.created_at', 'asc')
            ->get();

        $output = "";
        foreach ($messages as $msg) {
            $alignment = ($msg->user_id == auth()->id()) ? 'sent' : 'reciv';
            
            // Message text
            if (!empty($msg->message)) {
            $output .= "<div class='message {$alignment}'>
                            <strong>{$msg->name}:</strong> {$msg->message}
                        </div>";
            }
            // Show image only if it exists
            if (!empty($msg->images)) {
                $output .= "<div class='image-container message {$alignment}'>
                                <a href='{$msg->images}' style='bottom: 0px;top: auto;height:auto;left: 0px;right: 0px;display: flex;justify-content: center;border-bottom-right-radius: 15px;border-bottom-left-radius: 15px;' download='message-image.png' class='download-btn'>
                                    📥 Download
                                </a>
                                <img src='{$msg->images}' style='margin: 0;width: 180px;' alt='Message Image'>
                            </div>";
            }
            if (!empty($msg->audio)) {
                $output .= "<div class='message {$alignment}' style='display:flex;'>
                                <audio id='audio' class='' style='width: 269px;' src='{$msg->audio}' controls></audio>
                                </div>
                                
                            ";
            }
        }
        
        
        return response()->json(['messages' => $output]);
    }
}

