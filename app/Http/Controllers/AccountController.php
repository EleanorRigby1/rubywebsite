<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\CharactersOnline;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Account::latest('updated_at')->get();

        return view('admin.accounts.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Account::rules());
        
        Account::create($request->all());

        return back()->withSuccess(trans('admin.success_store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $view = ($this->isAdminRequest()) ? 'admin.accounts.show' : 'site.accounts.show';

        $account = ($id) ? Account::find($id) : auth()->user();
        $characters = $account->characters()->get();
        $charactersOnline = CharactersOnline::all();

        return view($view, compact('account', 'characters', 'charactersOnline'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Account::findOrFail($id);

        return view('admin.accounts.edit', compact('item'));
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
        $this->validate($request, Account::rules(true, $id));

        $item = Account::findOrFail($id);

        $item->update($request->all());

        return redirect()->route(ADMIN . '.accounts.index')->withSuccess(trans('admin.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Account::destroy($id);

        return back()->withSuccess(trans('admin.success_destroy'));
    }


    public function generate_rk($id = null)
    {
        $account = ($id) ? Account::find($id) : auth()->user();
        
        if (!$account->generate_rk()) {
            return back()->withErrors(trans('account.error_generate_rk'));
        }

        return back()->withSuccess(trans('account.success_generate_rk'));
    }
}

