<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Filmmodifications;
use Illuminate\Http\Request;

class FilmmodificationsController extends Controller {

    /**
     * GET|HEAD  /filmmodifications
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("filmmodifications.list", ['filmmodifications' => Filmmodifications::all()]);
    }

    /**
     * GET|HEAD  /filmmodifications/create
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("filmmodifications.edit");
    }

    /**
     * POST  /filmmodifications
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // TODO complete validation
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        Filmmodifications::create($data);

        return redirect(route("filmmodifications.index"));
    }

    /**
     * GET|HEAD /filmmodifications/{filmmodification}
     * Display the specified resource.
     *
     * @param  Filmmodifications $filmmodification
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Filmmodifications $filmmodification) {
        return view("filmmodifications.view", ["filmmodifications" => $filmmodification]);
    }

    /**
     * GET|HEAD /filmmodifications/{filmmodification}/edit
     * Show the form for editing the specified resource.
     *
     * @param  Filmmodifications $filmmodification
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Filmmodifications $filmmodification) {
        return view("filmmodifications.edit", ["filmmodifications" => $filmmodification]);
    }

    /**
     * PUT|PATCH /filmmodifications/{filmmodification}
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Filmmodifications $filmmodification
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Filmmodifications $filmmodification) {
        $newData = $request->except(['_method', '_token', 'id']);
        $filmmodification->fill($newData);
        $filmmodification->save();
        return redirect(route("filmmodifications.show", [$filmmodification->id]));
    }

    /**
     * DELETE /filmmodifications/{filmmodification}
     * Remove the specified resource from storage.
     *
     * @param  Filmmodifications $filmmodification
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy() {
        $filmmodification->delete();
        return redirect(route("filmmodifications.index"));
    }
}
