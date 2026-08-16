<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index(): string
    {
        $documents = [
            ['title' => 'Project Operations Manual', 'description' => 'Governance, processes, and implementation guidance.', 'file' => 'project-operations-manual.pdf'],
            ['title' => 'Application Format and Annexes', 'description' => 'Proposal template and all required supporting annexes.', 'file' => 'application-format-annexes.pdf'],
            ['title' => 'Evaluation Guidelines', 'description' => 'Evaluation criteria, scoring, and selection process.', 'file' => 'evaluation-guidelines.pdf'],
        ];
        foreach ($documents as &$document) {
            $document['available'] = is_file(FCPATH . 'uploads/documents/' . $document['file']);
            $document['url'] = base_url('uploads/documents/' . $document['file']);
        }
        return view('home/index', [
            'title' => 'Institutional Support and Services Project',
            'documents' => $documents,
            'errors' => session('errors') ?? [],
            'success' => session('success'),
            'activeForm' => session('activeForm'),
        ]);
    }

    public function contact()
    {
        $rules = ['name' => 'required|min_length[2]|max_length[80]', 'email' => 'required|valid_email|max_length[150]', 'message' => 'required|min_length[10]|max_length[1000]'];
        if (! $this->validate($rules)) {
            return redirect()->to(site_url('/') . '#support')->withInput()->with('errors', $this->validator->getErrors())->with('activeForm', 'contact');
        }
        return redirect()->to(site_url('/') . '#support')->with('success', 'Thank you. Your support request has been received.')->with('activeForm', 'contact');
    }

    public function health(): ResponseInterface
    {
        return $this->response->setJSON(['status' => 'ok', 'application' => 'ISSP']);
    }
}
