<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Storage, Validator};

class ProfileController extends Controller
{
    private function ab($sts)
    {
        return view($this->folder.'ab', [
            'sts'       => $sts,
            'profile'   => Profile::where('user_id', auth()->user()->id)->first()
        ]);
    }

    public function __construct()
    {
        $this->link = request()->segment(1);
        $this->folder = "pages.profile.";
    }

    public function index()
    {
        $profile = Profile::where('user_id', auth()->user()->id);
        return view($this->folder.'index', [
            'actionName'        => route($this->link.'.update', 'name'),
            'actionEmail'       => route($this->link.'.update', 'email'),
            'actionUsername'    => route($this->link.'.update', 'username'),
            'actionPassword'    => route($this->link.'.update', 'password'),
            'profile'           => $profile->get()->count() ? Profile::find($profile->first()->id) : new Profile,
            'urlProfile'        => $profile->get()->count() ? route($this->link.'.update.about.you') : route($this->link.'.store.about.you'),
            'methodProfile'     => $profile->get()->count() ? 'PUT' : 'POST',
            'imageProfile'      => $profile->get()->count() ? (Profile::find($profile->first()->id)->image ? asset('images/'.Profile::find($profile->first()->id)->image) : asset('images/default/user.png')) : asset('images/default/user.png'),
            'ab'                => $this->ab($profile->get()->count()),
            'image'             => $profile->get()->count() ? Profile::find($profile->first()->id) : new Profile,
            'urlImage'          => $profile->get()->count() ? route($this->link.'.update.image') : route($this->link.'.store.image'),
            'methodImage'       => $profile->get()->count() ? 'PUT' : 'POST',
        ]);
    }

    public function update(Request $request, $slug)
    {
        $id = auth()->user()->id;

        if ($slug == 'name') {
            $validator = Validator::make($request->all(), [
                'name'             => ['required']
            ],[
                'name.required' => 'Harus diisi.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $modelName         = User::findOrFail($id);
            $modelName->name   = request('name');
            $modelName->save();

            return json_encode(
                array(
                    'sts'   => 'success',
                    'msg'   => 'Email berhsil diperbaharui.',
                    'value' => request('name')
                )
            );
        } else if ($slug == 'email') {
            $validator = Validator::make($request->all(), [
                'email'             => ['required', 'unique:users,email,'.$id],
                'password_email'    => ['required']
            ],[
                'email.required' => 'Harus diisi.',
                'password_email.required' => 'Harus diisi.',
                'email.unique' => 'Email sudah digunakan.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $checkPass = Hash::check(request('password_email'), auth()->user()->password);
            if (!$checkPass) {
                return json_encode(array('sts' => 'warning', 'msg' => 'Password yang anda masukan salah.'));
            }

            $modelEmail         = User::findOrFail($id);
            $modelEmail->email  = request('email');
            $modelEmail->save();

            return json_encode(
                array(
                    'sts'   => 'success',
                    'msg'   => 'Email berhsil diperbaharui.',
                    'value' => request('email')
                )
            );
        } else if ($slug == 'username') {
            $validator = Validator::make($request->all(), [
                'username'             => ['required', 'unique:users,username,'.$id],
                'password_username'    => ['required']
            ],[
                'username.required' => 'Harus diisi.',
                'password_username.required' => 'Harus diisi.',
                'username.unique' => 'Username sudah digunakan.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $checkPass = Hash::check(request('password_username'), auth()->user()->password);
            if (!$checkPass) {
                return json_encode(array('sts' => 'warning', 'msg' => 'Password yang anda masukan salah.'));
            }

            $modelUsername              = User::findOrFail($id);
            $modelUsername->username    = request('username');
            $modelUsername->save();

            return json_encode(
                array(
                    'sts'   => 'success',
                    'msg'   => 'Username berhsil diperbaharui.',
                    'value' => request('username')
                )
            );
        } else if ($slug == 'password') {
            $validator = Validator::make($request->all(), [
                'password_confirmation'  => ['required', 'min:8'],
                'password'  => ['confirmed'],
                'old_password' => ['required']
            ],[
                'password_confirmation.required' => 'Harus diisi.',
                'old_password.required' => 'Harus diisi.',
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $checkPass = Hash::check(request('old_password'), auth()->user()->password);
            if (!$checkPass) {
                return json_encode(array('sts' => 'warning', 'msg' => 'Password lama yang anda masukan salah.'));
            }

            $modelPassword              = User::findOrFail($id);
            $modelPassword->password    = Hash::make(request('password'));
            $modelPassword->save();

            return json_encode(
                array(
                    'sts'   => 'success',
                    'msg'   => 'Password berhsil diperbaharui.'
                )
            );
        }
    }

    public function storeAboutYou(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'place_of_birth'    => ['required'],
            'date_of_birth'     => ['required'],
            'gender'            => ['required'],
            'address'           => ['required'],
            'hp'                => ['required', 'numeric']
        ],[
            'place_of_birth.required'   => 'Harus diisi.',
            'date_of_birth.required'    => 'Harus diisi.',
            'gender.required'           => 'Harus diisi.',
            'address.required'          => 'Harus diisi.',
            'hp.required'               => 'Harus diisi.',

            'hp.numeric'                => 'Harus angka.'
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model                  = new Profile;
        $model->user_id         = auth()->user()->id;
        $model->place_of_birth  = request('place_of_birth');
        $model->date_of_birth   = $this->dateYmd(request('date_of_birth'));
        $model->gender          = request('gender');
        $model->address         = request('address');
        $model->hp              = request('hp');
        $model->created_by      = auth()->user()->id;
        $model->updated_by      = auth()->user()->id;
        $model->save();

        return json_encode(
            array(
                'icon'      => 'success',
                'sts'       => 'store',
                'msg'       => 'Berhsil disimpan.',
                'action'    => route($this->link.'.update.about.you'),
                'ab'        => $this->ab(1)->render()
            )
        );
    }

    public function updateAboutYou(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'place_of_birth'    => ['required'],
            'date_of_birth'     => ['required'],
            'gender'            => ['required'],
            'address'           => ['required'],
            'hp'                => ['required', 'numeric']
        ],[
            'place_of_birth.required'   => 'Harus diisi.',
            'date_of_birth.required'    => 'Harus diisi.',
            'gender.required'           => 'Harus diisi.',
            'address.required'          => 'Harus diisi.',
            'hp.required'               => 'Harus diisi.',

            'hp.numeric'                => 'Harus angka.'
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model                  = Profile::find(Profile::where('user_id', auth()->user()->id)->first()->id);
        $model->place_of_birth  = request('place_of_birth');
        $model->date_of_birth   = $this->dateYmd(request('date_of_birth'));
        $model->gender          = request('gender');
        $model->address         = request('address');
        $model->hp              = request('hp');
        $model->updated_by      = auth()->user()->id;
        $model->save();

        return json_encode(
            array(
                'icon'      => 'success',
                'sts'       => 'update',
                'msg'       => 'Berhsil diperbaharui.',
                'ab'        => $this->ab(1)->render()
            )
        );
    }

    public function storeImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_profile'   => ['required', 'mimes:png,jpg,jpeg'],
        ], [
            'image_profile.required' => 'Harus diisi.',
            'image_profile.mimes' => 'Harus file bertipe : png, jpg, jpeg.'
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $image = request()->file('image_profile')->store('profile', 'public_image');

        $model                  = new Profile;
        $model->user_id         = auth()->user()->id;
        $model->image           = $image;
        $model->created_by      = auth()->user()->id;
        $model->updated_by      = auth()->user()->id;
        $model->save();

        return json_encode(
            array(
                'icon'      => 'success',
                'sts'       => 'store',
                'msg'       => 'Berhsil disimpan.',
                'action'    => route($this->link.'.update.image'),
                'image'     => asset('images/'.$image)
            )
        );
    }

    public function updateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_profile'   => ['required', 'mimes:png,jpg,jpeg'],
        ], [
            'image_profile.required' => 'Harus diisi.',
            'image_profile.mimes' => 'Harus file bertipe : png, jpg, jpeg.'
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        Storage::disk('public_image')->delete(Profile::where('user_id', auth()->user()->id)->first()->image);
        $image = request()->file('image_profile')->store('profile', 'public_image');

        $model                  = Profile::find(Profile::where('user_id', auth()->user()->id)->first()->id);
        $model->image           = $image;
        $model->updated_by      = auth()->user()->id;
        $model->save();

        return json_encode(
            array(
                'icon'      => 'success',
                'sts'       => 'store',
                'msg'       => 'Berhsil disimpan.',
                'image'     => asset('images/'.$image)
            )
        );
    }
}
