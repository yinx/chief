<?php

namespace App\Http\Controllers\Back\Authorization;

use App\Http\Controllers\Controller;
use Chief\Authorization\Role;
use Chief\Users\Invites\Application\InviteUser;
use Chief\Users\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('back.users.index')->with('users', $users);
    }

    /**
     * Show the invite form
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-user');

        return view('back.authorization.users.create', [
            'roles'=>Role::all()
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:users',
            'roles' => 'required|array',
        ]);

        $user = User::create(
            $request->only(['firstname', 'lastname', 'email'])
        );

        $user->assignRole($request->get('roles', []));

        app(InviteUser::class)->handle($user, auth()->guard('admin')->user());

        return redirect()->route('back.users.index')
            ->with('messages.success', 'User successfully added.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();
        return view('back.users._partials.edituser', compact('user', 'roles'));
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
        $user = User::findOrFail($id);
        $this->validate($request, [
            'firstname'=>'required|max:120',
            'lastname'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
        ]);
        $input = $request->only(['firstname', 'lastname', 'email']);
        $roles = $request['roles'];

        $user->fill($input)->save();
        if (isset($roles)) {
            $user->roles()->sync($roles);
        }
        else {
            $user->roles()->detach();
        }
        return redirect()->route('back.users.index')
            ->with('flash_message',
                'User successfully edited.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('back.users.index')
            ->with('flash_message',
                'User successfully deleted.');
    }

    public function publish($user, Request $request){
      $user = User::findOrFail($user);
      $user->status = ($request->publishAccount == 'on' ? 'Active' : 'Blocked');
      $user->save();

      return redirect()->back();

    }
}
