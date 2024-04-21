<?php

namespace App\Http\Controllers;

use App\Models\Programmblocks;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgrammblocksController extends Controller {

    public function index(): \Inertia\Response  {
        return Inertia::render('Program', [
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    /**
     * GET|HEAD  /programmblocks/create
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("programmblocks.edit");
    }

    /**
     * POST  /programmblocks
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // TODO complete validation
        $data = $this->validate($request, [
            'films_id' => 'required',
        ]);

        Programmblocks::create($data);

        return redirect(route("programmblocks.index"));
    }

    /**
     * GET|HEAD /programmblocks/{programmblock}
     * Display the specified resource.
     *
     * @param  Programmblocks $programmblock
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Programmblocks $programmblock) {
        return view("programmblocks.view", ["programmblocks" => $programmblock]);
    }

    /**
     * GET|HEAD /programmblocks/{programmblock}/edit
     * Show the form for editing the specified resource.
     *
     * @param  Programmblocks $programmblock
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Programmblocks $programmblock) {
        return view("programmblocks.edit", ["programmblocks" => $programmblock]);
    }

    /**
     * PUT|PATCH /programmblocks/{programmblock}
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Programmblocks $programmblock
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programmblocks $programmblock) {
        $newData = $request->except(['_method', '_token', 'id']);
        $programmblock->fill($newData);
        $programmblock->save();
        return redirect(route("programmblocks.show", [$programmblock->id]));
    }

    /**
     * DELETE /programmblocks/{programmblock}
     * Remove the specified resource from storage.
     *
     * @param  Programmblocks $programmblock
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy() {
        $programmblock->delete();
        return redirect(route("programmblocks.index"));
    }
}
