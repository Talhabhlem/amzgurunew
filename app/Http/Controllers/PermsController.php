<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;

class PermsController extends Controller
{
  public function index(Request $request)
  {
    return Helper::index($request);
  }

  public function create(Request $request)
  {
    return Helper::create($request);
  }

  public function store(Request $request)
  {
    return Helper::store($request);
  }

  public function edit(Request $request, $id)
  {
    return Helper::edit($id, $request);
  }

  public function update(Request $request, $id)
  {
    return Helper::update($id, $request);
  }

  public function destroy(Request $request, $id)
  {
    return Helper::destroy($id, $request);
  }
}