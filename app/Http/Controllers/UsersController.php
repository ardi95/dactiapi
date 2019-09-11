<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\RoleUser;
use Validator;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $row_per_page = $request->rowperpage;
        $search = $request->search;
        if (trim($search) != '' OR trim($search) != null) {
            $data['users'] = User::where('name','like','%'.$search.'%')
            ->orWhere('email','like','%'.$search.'%')
            ->orderBy('name','asc')
            ->paginate($row_per_page);
        }
        else {
            $data['users'] = User::orderBy('name','asc')->paginate($row_per_page);
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::all();

        return response()->json($role);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
        	'password' => 'required|min:8',
            'name' => 'required'
        ]);

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $value) {
                if ($value[0] == 'The email has already been taken.') {
                    $data['errors'][$a] = "The username has already been taken.";
                    $a++;
                }
                else {
                    $data['errors'][$a] = $value[0];
                    $a++;
                }
            }

            $error = 1;
        }

        if (trim($request->no_pegawai) != '' OR $request->no_pegawai != NULL) {
            $validator2 = Validator::make($request->all(), [
                'no_pegawai' => 'unique:users'
            ]);

            if ($validator2->fails()) {
                $error = 1;
                $errors2 = $validator2->errors()->getMessages();

                foreach ($errors2 as $value2) {
                    $data['errors'][$a] = $value2[0];
                    $a++;
                }
            }
        }

        if ($request->photo != null) {
            $validator3 = Validator::make($request->all(), [
                'photo' => 'mimes:png'
            ]);

            if ($validator3->fails()) {
                $error = 1;
                $errors3 = $validator3->errors()->getMessages();

                foreach ($errors3 as $value3) {
                    $data['errors'][$a] = $value3[0];
                    $a++;
                }
            }
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        }
        else {
            // STATUS
            if ($request->status == TRUE) {
                $status = '1';
            }
            else {
                $status = '0';
            }
            // PHOTO
            if ($request->file('photo') !== NULL) {
                $uploadFile = $request->file('photo');

                $nameFile = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $extensionFile = $uploadFile->getClientOriginalExtension();

                $resultNameFile = $nameFile.".".$extensionFile;

                $nameFile2 = $nameFile;

                $i = 2;
                while(file_exists(base_path().'/public/profile/'.$nameFile.".".$extensionFile))
                {
                    $nameFile = (string)$nameFile2.$i;
                    $resultNameFile = $nameFile.".".$extensionFile;
                    $i++;
                }

                $destinationPath = base_path().'/public/profile/';

                $uploadFile->move($destinationPath, $resultNameFile);
            }
            else {
                $resultNameFile = NULL;
            }

            $user = new User();
            $user->name = $request->name;
    		$user->email = $request->email;
    		$user->password = bcrypt($request->password);
            $user->no_pegawai = $request->no_pegawai;
            $user->photo = $resultNameFile;
            $user->status = $status;
            $user->save();

            // $count_role = count($request->role);

            if (!empty($request->role)) {
                foreach ($request->role as $r) {
                    $role = Role::find($r);
                    $user->attachRole($role);
                }
            }

            $data['status'] = 'success';
            $data['message'] = 'success add data user';

            return response()->json($data, 200);
        }
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
        $data['user'] = User::find($id);

        $role = RoleUser::where('user_id','=',$id)->get();
        $data['role'] = array();
        foreach ($role as $key => $r) {
            $data['role'][$key] = $r->role_id;
        }

        return response()->json($data, 200);
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$id,
            'name' => 'required'
        ]);

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $value) {
                if ($value[0] == 'The email has already been taken.') {
                    $data['errors'][$a] = "The username has already been taken.";
                    $a++;
                }
                else {
                    $data['errors'][$a] = $value[0];
                    $a++;
                }
            }
            $error = 1;
        }

        if (trim($request->no_pegawai) != '' OR $request->no_pegawai != NULL) {
            $validator2 = Validator::make($request->all(), [
                'no_pegawai' => 'unique:users,no_pegawai,'.$id
            ]);

            if ($validator2->fails()) {
                $error = 1;
                $errors2 = $validator2->errors()->getMessages();

                foreach ($errors2 as $value2) {
                    $data['errors'][$a] = $value2[0];
                    $a++;
                }
            }
        }

        if ($request->status_photo == 1) {
            if ($request->photo != null) {
                $validator3 = Validator::make($request->all(), [
                    'photo' => 'mimes:png'
                ]);

                if ($validator3->fails()) {
                    $error = 1;
                    $errors3 = $validator3->errors()->getMessages();

                    foreach ($errors3 as $value3) {
                        $data['errors'][$a] = $value3[0];
                        $a++;
                    }
                }
            }
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {

            $user = User::find($id);
            if ($user->photo !== NULL) {
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'profile'. DIRECTORY_SEPARATOR . $user->photo;

                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                    // File sudah dihapus/tidak ada
                }
            }

            // PHOTO
            if ($request->status_photo == 1) {
                if ($request->file('photo') !== NULL) {
                    $uploadFile = $request->file('photo');

                    $nameFile = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $extensionFile = $uploadFile->getClientOriginalExtension();

                    $resultNameFile = $nameFile.".".$extensionFile;

                    $nameFile2 = $nameFile;

                    $i = 2;
                    while(file_exists(base_path().'/public/profile/'.$nameFile.".".$extensionFile))
                    {
                        $nameFile = (string)$nameFile2.$i;
                        $resultNameFile = $nameFile.".".$extensionFile;
                        $i++;
                    }

                    $destinationPath = base_path().'/public/profile/';

                    $uploadFile->move($destinationPath, $resultNameFile);
                }
                else {
                    $resultNameFile = NULL;
                }
            } else {
                $resultNameFile = NULL;
            }

            $role = RoleUser::where('user_id', '=', $id);
            $role->delete();

            // echo $request->status;die();

            $user->update([
                'email' => $request->email,
                'name' => $request->name,
                'no_pegawai' => $request->no_pegawai,
                'status' => $request->status,
                'photo' => $resultNameFile
            ]);

            if (!empty($request->role)) {
                foreach ($request->role as $r) {
                    $role = Role::find($r);
                    $user->attachRole($role);
                }
            }

            $data['status'] = 'success';
            $data['message'] = 'success edit data user';

            return response()->json($data, 200);
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
        //
    }
}
