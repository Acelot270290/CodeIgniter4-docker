<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        return view('users');
    }

    public function getUsers()
{
    $request = service('request');
    $searchValue = $request->getPost('search')['value'];
    $start = $request->getPost('start');
    $length = $request->getPost('length');

    $model = new UserModel();

    if ($searchValue) {
        $model->like('name', $searchValue)
              ->orLike('email', $searchValue);
    }

    $data = $model->findAll($length, $start);

    $totalRecords = $model->countAllResults(false);
    $totalFiltered = $searchValue ? $model->countAllResults() : $totalRecords;

    return $this->response->setJSON([
        'draw' => intval($request->getPost('draw')),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalFiltered,
        'data' => $data
    ]);
}


    public function create()
    {
        $model = new UserModel();
        $data = $this->request->getPost();

        if ($model->insert($data)) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error'], 400);
    }

    public function update($id)
    {
        $model = new UserModel();
        $data = $this->request->getRawInput();

        if ($model->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error'], 400);
    }

    public function delete($id)
    {
        $model = new UserModel();

        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error'], 400);
    }
}
