<?php

namespace App\Controllers;

use App\Models\ApplicationRoundModel;
use App\Models\DepartmentModel;
use App\Models\InternshipApplicationModel;
use App\Models\UniversityModel;
use App\Models\UserModel;

class Internship extends BaseController
{
    private const GENDER_IDENTITIES = ['Woman', 'Man', 'Gender Diverse Individuals'];
    private const INTERNSHIP_TYPES = ['Industry', 'Capstone'];
    private const ROUND_STATUSES = ['Draft', 'Open', 'Closed'];
    private const MIN_CGPA = 2.75;
    private const MIN_CREDIT_COMPLETION = 75.00;
    private const DEPARTMENTS = [
        'Computer Science and Engineering (CSE)',
        'Computer Science (CS)',
        'Computer Engineering (CE)',
        'Information Technology (IT)',
        'Information and Communication Technology (ICT)',
        'Software Engineering (SWE)',
        'Software Engineering and Information Systems',
        'Data Science (DS)',
        'Cyber Security',
        'Cyber Security Engineering',
        'Robotics and Mechatronics Engineering',
        'Other',
    ];

    public function apply(string $roundCode = null): string
    {
        $user = $this->currentUser();
        $eligibleRounds = $this->eligibleRoundsForUser($user['id']);

        if ($roundCode !== null) {
            $round = $this->eligibleRoundByCode($roundCode);
            if (! $round) {
                return redirect()->to(site_url('dashboard'))->with('error', 'That application call is no longer available.');
            }

            $application = $this->userApplicationForRound($user['id'], (int) $round['id']);
            if ($application) {
                return redirect()->to(site_url('applications/' . $application['application_code']))
                    ->with('info', 'You have already submitted an application for this round.');
            }
        } else {
            $round = $this->openRound();
        }

        $application = $round ? $this->userApplicationForRound($user['id'], (int) $round['id']) : null;

        return view('internship/apply', [
            'title' => 'Apply',
            'openRound' => $round,
            'application' => $application,
            'eligibleRounds' => $eligibleRounds,
            'user' => $user,
            'universities' => (new UniversityModel())
                ->where('is_active', 1)
                ->orderBy('type', 'asc')
                ->orderBy('name', 'asc')
                ->findAll(),
            'errors' => session('errors') ?? [],
        ]);
    }

    public function departments(int $universityId)
    {
        try {
            $departments = (new DepartmentModel())
                ->where('university_id', $universityId)
                ->join('universities', 'universities.id = departments.university_id')
                ->where('universities.is_active', 1)
                ->orderBy('name', 'asc')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'departments' => $departments,
            ]);
        } catch (\Throwable) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'departments' => [],
            ]);
        }
    }

    public function submit(string $roundCode = null)
    {
        $user = $this->currentUser();
        $round = $this->eligibleRoundByCode($roundCode);
        $returnToApply = $roundCode !== null && $roundCode !== '' ? site_url('apply/' . $roundCode) : rtrim(site_url('apply'), '/') . '/';

        if (! $round) {
            return redirect()->to(site_url('dashboard'))->with('error', 'That application call is no longer available.');
        }

        if (! $this->validate($this->applicationRules())) {
            return redirect()->to($returnToApply)->setHeader('Location', $returnToApply)
                ->withInput($this->preserveApplicationInput())
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please check the highlighted fields.');
        }

        $input = $this->normalizedApplicationInput();
        if (! $this->universityExistsAndActive((int) $input['university_id'])) {
            return redirect()->to($returnToApply)->setHeader('Location', $returnToApply)
                ->withInput($this->preserveApplicationInput())
                ->with('errors', ['university_id' => 'Please choose a valid university.'])
                ->with('error', 'Please check the highlighted fields.');
        }
        if ($input['internship_type'] !== 'Capstone') {
            $input['team_member_count'] = null;
        }

        if ($input['internship_end_date'] <= $input['internship_start_date']) {
            return redirect()->to($returnToApply)->setHeader('Location', $returnToApply)
                ->withInput($this->preserveApplicationInput())
                ->with('errors', ['internship_end_date' => 'The internship end date must be later than the start date.'])
                ->with('error', 'Please check the highlighted fields.');
        }

        $eligibility = $this->academicEligibility((string) $input['current_cgpa'], (string) $input['total_credits'], (string) $input['earned_credits']);
        if ($eligibility['state'] !== 'eligible') {
            $errors = [];
            if ($eligibility['state'] === 'invalid_credits') {
                $errors['current_cgpa'] = $eligibility['message'];
                $errors['total_credits'] = $eligibility['message'];
                $errors['earned_credits'] = $eligibility['message'];
            } else {
                if ($eligibility['cgpa_failed']) {
                    $errors['current_cgpa'] = 'You are not eligible to apply because a minimum CGPA of 2.75 is required.';
                }
                if ($eligibility['credit_failed']) {
                    $errors['total_credits'] = 'You are not eligible to apply because you must complete at least 75% of your total program credits.';
                    $errors['earned_credits'] = 'You are not eligible to apply because you must complete at least 75% of your total program credits.';
                }
            }

            return redirect()->to($returnToApply)->setHeader('Location', $returnToApply)
                ->withInput($this->preserveApplicationInput())
                ->with('errors', $errors)
                ->with('error', $eligibility['message']);
        }

        $applicationModel = new InternshipApplicationModel();
        $db = db_connect();

        try {
            $db->transStart();

            $existing = $applicationModel->where('round_id', $round['id'])->where('user_id', $user['id'])->first();
            if ($existing) {
                $db->transRollback();
                return redirect()->to(site_url('applications/' . $existing['application_code']))->with('info', 'You already submitted an application for this round.');
            }

            $data = [
                'round_id' => $round['id'],
                'user_id' => $user['id'],
                'full_name' => $user['full_name'],
                'gender_identity' => $user['gender_identity'] ?? '',
                'student_id' => trim((string) $input['student_id']),
                'university_id' => (int) $input['university_id'],
                'department_id' => null,
                'department' => $input['department'],
                'other_department' => $input['department'] === 'Other' ? trim((string) $input['other_department']) : null,
                'current_cgpa' => $input['current_cgpa'],
                'total_credits' => $input['total_credits'],
                'earned_credits' => $input['earned_credits'],
                'credit_completion_percentage' => $eligibility['credit_completion_percentage'],
                'information_declaration' => 1,
                'declared_at' => date('Y-m-d H:i:s'),
                'internship_type' => $input['internship_type'],
                'team_member_count' => $input['team_member_count'],
                'supervisor_name' => trim((string) $input['supervisor_name']),
                'supervisor_email' => strtolower(trim((string) $input['supervisor_email'])),
                'supervisor_university' => trim((string) $input['supervisor_university']),
                'supervisor_department' => trim((string) $input['supervisor_department']),
                'supervisor_designation' => trim((string) $input['supervisor_designation']),
                'supervisor_phone' => $this->normalizeBangladeshiPhone((string) $input['supervisor_phone']),
                'internship_start_date' => $input['internship_start_date'],
                'internship_end_date' => $input['internship_end_date'],
                'placement_organization_name' => trim((string) $input['placement_organization_name']),
                'organization_website_url' => $input['organization_website_url'] !== '' ? trim((string) $input['organization_website_url']) : null,
                'mentor_name' => trim((string) $input['mentor_name']),
                'mentor_email' => strtolower(trim((string) $input['mentor_email'])),
                'status' => 'Submitted',
                'submitted_at' => date('Y-m-d H:i:s'),
            ];

            $inserted = $applicationModel->insert($data, true);
            if ($inserted === false) {
                $modelErrors = $applicationModel->errors();
                $dbError = $applicationModel->db->error();
                throw new \RuntimeException('Application insert failed: ' . json_encode(['modelErrors' => $modelErrors, 'dbError' => $dbError], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Application transaction failed.');
            }
        } catch (\Throwable $e) {
            $message = 'Unable to submit your application. Please try again.';
            if ((int) $e->getCode() === 1062 || str_contains(strtolower($e->getMessage()), 'duplicate')) {
                $message = 'You have already applied for this round.';
            }
            log_message('error', 'Internship submit failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to($returnToApply)->setHeader('Location', $returnToApply)->withInput($this->preserveApplicationInput())->with('error', $message);
        }

        return redirect()->to(site_url('applications'))->with('success', 'Your application has been submitted successfully.');
    }

    public function index(): string
    {
        $user = $this->currentUser();
        $applications = (new InternshipApplicationModel())
            ->select('internship_applications.*, application_rounds.round_code, application_rounds.title as round_title')
            ->join('application_rounds', 'application_rounds.id = internship_applications.round_id')
            ->where('internship_applications.user_id', $user['id'])
            ->where('internship_applications.deleted_at', null)
            ->orderBy('application_rounds.round_code', 'desc')
            ->findAll();

        return view('internship/index', [
            'title' => 'My Applications',
            'user' => $user,
            'applications' => $applications,
            'errors' => session('errors') ?? [],
        ]);
    }

    public function show(string $applicationCode): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $user = $this->currentUser();
        if (ctype_digit($applicationCode)) {
            $legacy = (new InternshipApplicationModel())->where('id', (int) $applicationCode)->where('deleted_at', null)->first();
            if ($legacy && (int) $legacy['user_id'] === $user['id']) {
                return redirect()->to(site_url('applications/' . $legacy['application_code']), 301);
            }
        }
        $application = $this->applicationByPublicCode($applicationCode);

        if (! $application) {
            return $this->response->setStatusCode(404)->setBody(view('errors/html/error_404'));
        }

        if ((int) $application['user_id'] !== $user['id']) {
            return $this->response->setStatusCode(404)->setBody(view('errors/html/error_404'));
        }

        return view('internship/show', [
            'title' => 'Application Details',
            'user' => $user,
            'application' => $application,
        ]);
    }

    public function edit(string $applicationCode): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $user = $this->currentUser();
        $application = $this->applicationByPublicCode($applicationCode);

        if (! $application || (int) $application['user_id'] !== $user['id']) {
            return $this->response->setStatusCode(404)->setBody(view('errors/html/error_404'));
        }

        if ((int) ($application['edit_enabled'] ?? 0) !== 1) {
            return redirect()->to(site_url('applications/' . $application['application_code']))->with('error', 'Editing is currently locked. Please contact the administrator if a correction is required.');
        }

        return $this->show($applicationCode);
    }

    private function applicationByPublicCode(string $applicationCode): ?array
    {
        return (new InternshipApplicationModel())
            ->select('internship_applications.*, application_rounds.round_code, application_rounds.title as round_title, users.gender_identity as profile_gender_identity, users.disability_status, users.disability_type, users.ethnic_minority_status, users.ethnic_group_name')
            ->join('application_rounds', 'application_rounds.id = internship_applications.round_id')
            ->join('users', 'users.id = internship_applications.user_id')
            ->where('internship_applications.application_code', $applicationCode)
            ->where('internship_applications.deleted_at', null)
            ->first();
    }

    public function rounds(): string
    {
        return view('internship/rounds', [
            'title' => 'Application Calls',
            'rounds' => $this->roundsWithStats(),
            'round' => null,
            'mode' => 'create',
            'errors' => session('errors') ?? [],
        ]);
    }

    public function createRound(): string
    {
        return view('internship/rounds', [
            'title' => 'Create Call',
            'rounds' => $this->roundsWithStats(),
            'round' => null,
            'mode' => 'create',
            'errors' => session('errors') ?? [],
        ]);
    }

    public function editRound(int $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $round = (new ApplicationRoundModel())->find($id);

        if (! $round) {
            return $this->response->setStatusCode(404)->setBody(view('errors/html/error_404'));
        }

        return view('internship/rounds', [
            'title' => 'Edit Call',
            'rounds' => $this->roundsWithStats(),
            'round' => $this->decorateRound($round),
            'mode' => 'edit',
            'errors' => session('errors') ?? [],
        ]);
    }

    public function storeRound()
    {
        return $this->persistRound();
    }

    public function updateRound(int $id)
    {
        return $this->persistRound($id);
    }

    public function toggleRoundStatus(int $id)
    {
        $round = (new ApplicationRoundModel())->find($id);
        if (! $round) {
            return redirect()->to(site_url('admin/calls'))->with('error', 'Call not found.');
        }

        $desired = trim((string) $this->request->getPost('status'));
        if (! in_array($desired, self::ROUND_STATUSES, true)) {
            return redirect()->to(site_url('admin/calls'))->with('error', 'Please check the highlighted fields.');
        }

        if ($desired === 'Open') {
            $now = date('Y-m-d H:i:s');
            if (empty($round['title']) || empty($round['description']) || empty($round['opens_at']) || empty($round['closes_at'])) {
                return redirect()->to(site_url('admin/calls'))->with('error', 'Please complete the call details before opening it.');
            }
            if ($round['closes_at'] <= $round['opens_at']) {
                return redirect()->to(site_url('admin/calls'))->with('error', 'Closing time must be later than opening time.');
            }
            if ($round['closes_at'] < $now) {
                return redirect()->to(site_url('admin/calls'))->with('error', 'This call has already expired.');
            }
        }

        (new ApplicationRoundModel())->update($id, [
            'status' => $desired,
            'updated_by' => (int) session('user_id'),
        ]);

        return redirect()->to(site_url('admin/calls'))->with('success', $desired === 'Open' ? 'Call opened successfully.' : 'Call closed successfully.');
    }

    private function persistRound(?int $id = null)
    {
        if (! $this->validate($this->roundRules($id))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors())->with('error', 'Please check the highlighted fields.');
        }

        $opensAt = str_replace('T', ' ', (string) $this->request->getPost('opens_at')) . ':00';
        $closesAt = str_replace('T', ' ', (string) $this->request->getPost('closes_at')) . ':00';
        if ($closesAt <= $opensAt) {
            return redirect()->back()->withInput()->with('errors', ['closes_at' => 'Closing date and time must be later than opening date and time.'])->with('error', 'Please check the highlighted fields.');
        }
        if (($this->request->getPost('status') ?? '') === 'Open' && $closesAt < date('Y-m-d H:i:s')) {
            return redirect()->back()->withInput()->with('errors', ['closes_at' => 'An open call must end in the future.'])->with('error', 'Please check the highlighted fields.');
        }

        $data = [
            'round_code' => trim((string) $this->request->getPost('round_code')),
            'title' => trim((string) $this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'opens_at' => $opensAt,
            'closes_at' => $closesAt,
            'status' => $this->request->getPost('status'),
            'updated_by' => (int) session('user_id'),
        ];

        if ($id === null) {
            $data['created_by'] = (int) session('user_id');
        }

        $model = new ApplicationRoundModel();
        $db = db_connect();

        try {
            $db->transStart();
            if ($id === null) {
                $model->insert($data, true);
            } else {
                $model->update($id, $data);
            }
            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Call transaction failed.');
            }
        } catch (\Throwable $e) {
            $message = (int) $e->getCode() === 1062 || str_contains(strtolower($e->getMessage()), 'duplicate')
                ? 'A call with this round code already exists.'
                : 'Unable to save the call. Please try again.';
            log_message('error', 'Call save failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', $message);
        }

        return redirect()->to(site_url('admin/calls'))->with('success', $id === null ? 'Call created successfully.' : 'Call updated successfully.');
    }

    private function roundRules(?int $excludeId = null): array
    {
        $unique = 'is_unique[application_rounds.round_code,id,' . (int) $excludeId . ']';
        return [
            'round_code' => 'required|regex_match[/^\d{6}$/]|' . $unique,
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[3]',
            'opens_at' => 'required|valid_date[Y-m-d\TH:i]',
            'closes_at' => 'required|valid_date[Y-m-d\TH:i]',
            'status' => 'required|in_list[' . implode(',', self::ROUND_STATUSES) . ']',
        ];
    }

    private function roundPayload(): array
    {
        return [
            'round_code' => trim((string) $this->request->getPost('round_code')),
            'title' => trim((string) $this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'opens_at' => str_replace('T', ' ', (string) $this->request->getPost('opens_at')) . ':00',
            'closes_at' => str_replace('T', ' ', (string) $this->request->getPost('closes_at')) . ':00',
            'status' => trim((string) $this->request->getPost('status')),
        ];
    }

    private function roundsWithStats(): array
    {
        $rows = db_connect()->table('application_rounds ar')
            ->select('ar.*, COUNT(ia.id) as applications_count')
            ->join('internship_applications ia', 'ia.round_id = ar.id', 'left')
            ->groupBy('ar.id')
            ->orderBy('ar.opens_at', 'desc')
            ->get()
            ->getResultArray();

        return array_map(fn (array $round) => $this->decorateRound($round), $rows);
    }

    private function decorateRound(array $round): array
    {
        $round['round_code'] = $round['round_code'] ?? (string) ($round['round_number'] ?? '');
        $round['applications_count'] = (int) ($round['applications_count'] ?? 0);
        $round['effective_status'] = $this->effectiveRoundStatus($round);
        $round['can_open'] = $round['status'] !== 'Open';
        $round['can_close'] = $round['status'] === 'Open';
        return $round;
    }

    private function effectiveRoundStatus(array $round): string
    {
        $now = date('Y-m-d H:i:s');
        if (($round['status'] ?? '') === 'Draft') {
            return 'Draft';
        }
        if (($round['status'] ?? '') === 'Closed') {
            return 'Closed';
        }
        if (($round['closes_at'] ?? '') < $now) {
            return 'Expired';
        }
        if (($round['opens_at'] ?? '') > $now) {
            return 'Upcoming';
        }
        return 'Accepting Applications';
    }

    private function applicationRules(): array
    {
        $rules = [
            'student_id' => 'required|trim|min_length[3]|max_length[50]',
            'university_id' => 'required|is_natural_no_zero',
            'department' => 'required|in_list[' . implode(',', self::DEPARTMENTS) . ']',
            'other_department' => 'permit_empty|trim|max_length[150]',
            'current_cgpa' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[4]',
            'total_credits' => 'required|decimal|greater_than[0]',
            'earned_credits' => 'required|decimal|greater_than_equal_to[0]',
            'internship_type' => 'required|in_list[' . implode(',', self::INTERNSHIP_TYPES) . ']',
            'internship_start_date' => 'required|valid_date[Y-m-d]',
            'internship_end_date' => 'required|valid_date[Y-m-d]',
            'information_declaration' => 'required|in_list[1]',
            'supervisor_name' => 'required|trim|min_length[3]|max_length[150]',
            'supervisor_email' => 'required|valid_email|max_length[190]',
            'supervisor_university' => 'required|trim|max_length[190]',
            'supervisor_department' => 'required|trim|max_length[190]',
            'supervisor_designation' => 'required|trim|max_length[150]',
            'supervisor_phone' => 'required|regex_match[/^(?:01[3-9]\d{8}|\+8801[3-9]\d{8})$/]|max_length[20]',
            'placement_organization_name' => 'required|trim|max_length[190]',
            'organization_website_url' => 'permit_empty|valid_url_strict[http,https]|max_length[255]',
            'mentor_name' => 'required|max_length[150]',
            'mentor_email' => 'required|valid_email|max_length[190]',
        ];

        if (($this->request->getPost('internship_type') ?? '') === 'Capstone') {
            $rules['team_member_count'] = 'required|is_natural_no_zero';
        } else {
            $rules['team_member_count'] = 'permit_empty|is_natural_no_zero';
        }

        if (($this->request->getPost('department') ?? '') === 'Other') {
            $rules['other_department'] = 'required|trim|min_length[3]|max_length[150]';
        }

        return $rules;
    }

    private function normalizedApplicationInput(): array
    {
        return [
            'student_id' => trim((string) $this->request->getPost('student_id')),
            'university_id' => (int) $this->request->getPost('university_id'),
            'department' => trim((string) $this->request->getPost('department')),
            'other_department' => trim((string) $this->request->getPost('other_department')),
            'current_cgpa' => number_format((float) $this->request->getPost('current_cgpa'), 2, '.', ''),
            'total_credits' => number_format((float) $this->request->getPost('total_credits'), 2, '.', ''),
            'earned_credits' => number_format((float) $this->request->getPost('earned_credits'), 2, '.', ''),
            'information_declaration' => $this->request->getPost('information_declaration') === '1' ? '1' : '',
            'internship_type' => trim((string) $this->request->getPost('internship_type')),
            'team_member_count' => $this->request->getPost('team_member_count') !== null && $this->request->getPost('team_member_count') !== '' ? (int) $this->request->getPost('team_member_count') : null,
            'internship_start_date' => (string) $this->request->getPost('internship_start_date'),
            'internship_end_date' => (string) $this->request->getPost('internship_end_date'),
            'supervisor_name' => trim((string) $this->request->getPost('supervisor_name')),
            'supervisor_email' => strtolower(trim((string) $this->request->getPost('supervisor_email'))),
            'supervisor_university' => trim((string) $this->request->getPost('supervisor_university')),
            'supervisor_department' => trim((string) $this->request->getPost('supervisor_department')),
            'supervisor_designation' => trim((string) $this->request->getPost('supervisor_designation')),
            'supervisor_phone' => $this->normalizeBangladeshiPhone((string) $this->request->getPost('supervisor_phone')),
            'placement_organization_name' => trim((string) $this->request->getPost('placement_organization_name')),
            'organization_website_url' => trim((string) $this->request->getPost('organization_website_url')),
            'mentor_name' => trim((string) $this->request->getPost('mentor_name')),
            'mentor_email' => strtolower(trim((string) $this->request->getPost('mentor_email'))),
        ];
    }

    private function preserveApplicationInput(): array
    {
        return array_merge(['full_name' => $this->currentUser()['full_name'], 'gender_identity' => $this->currentUser()['gender_identity'] ?? ''], $this->normalizedApplicationInput());
    }

    private function universityExistsAndActive(int $universityId): bool
    {
        return (new UniversityModel())
            ->where('id', $universityId)
            ->where('is_active', 1)
            ->first() !== null;
    }

    private function academicEligibility(string $cgpa, string $totalCredits, string $earnedCredits): array
    {
        if ($cgpa === '' || $totalCredits === '' || $earnedCredits === '') {
            return [
                'state' => 'pending',
                'message' => '',
                'cgpa_failed' => false,
                'credit_failed' => false,
                'credit_completion_percentage' => '0.00',
            ];
        }

        if (! is_numeric($cgpa) || ! is_numeric($totalCredits) || ! is_numeric($earnedCredits)) {
            return [
                'state' => 'invalid_credits',
                'message' => 'Please enter valid credit information. Earned credits cannot exceed total credits, and total credits must be greater than zero.',
                'cgpa_failed' => false,
                'credit_failed' => false,
                'credit_completion_percentage' => '0.00',
            ];
        }

        $cgpaValue = (float) $cgpa;
        $total = (float) $totalCredits;
        $earned = (float) $earnedCredits;

        if ($total <= 0 || $earned < 0 || $earned > $total) {
            return [
                'state' => 'invalid_credits',
                'message' => 'Please enter valid credit information. Earned credits cannot exceed total credits, and total credits must be greater than zero.',
                'cgpa_failed' => false,
                'credit_failed' => false,
                'credit_completion_percentage' => '0.00',
            ];
        }

        $creditCompletion = ($earned * 100) / $total;
        $cgpaFailed = $cgpaValue < self::MIN_CGPA;
        $creditFailed = ($earned * 100) < ($total * self::MIN_CREDIT_COMPLETION);

        if ($cgpaFailed || $creditFailed) {
            return [
                'state' => 'ineligible',
                'message' => $cgpaFailed && $creditFailed
                    ? 'You are not eligible to apply. A minimum CGPA of 2.75 and completion of at least 75% of total program credits are required.'
                    : ($cgpaFailed
                        ? 'You are not eligible to apply because a minimum CGPA of 2.75 is required.'
                        : 'You are not eligible to apply because you must complete at least 75% of your total program credits.'),
                'cgpa_failed' => $cgpaFailed,
                'credit_failed' => $creditFailed,
                'credit_completion_percentage' => number_format($creditCompletion, 2, '.', ''),
            ];
        }

        return [
            'state' => 'eligible',
            'message' => 'You meet the academic eligibility requirements for this application.',
            'cgpa_failed' => false,
            'credit_failed' => false,
            'credit_completion_percentage' => number_format($creditCompletion, 2, '.', ''),
        ];
    }

    private function openRound(): ?array
    {
        try {
            return (new ApplicationRoundModel())
                ->where('status', 'Open')
                ->where('opens_at <=', date('Y-m-d H:i:s'))
                ->where('closes_at >=', date('Y-m-d H:i:s'))
                ->orderBy('round_code', 'desc')
                ->first();
        } catch (\Throwable) {
            return null;
        }
    }

    private function eligibleRoundByCode(?string $roundCode): ?array
    {
        if ($roundCode === null || $roundCode === '') {
            return $this->openRound();
        }

        try {
            return (new ApplicationRoundModel())
                ->where('round_code', $roundCode)
                ->where('status', 'Open')
                ->where('opens_at <=', date('Y-m-d H:i:s'))
                ->where('closes_at >=', date('Y-m-d H:i:s'))
                ->first();
        } catch (\Throwable) {
            return null;
        }
    }

    private function eligibleRoundsForUser(int $userId): array
    {
        $db = db_connect();
        if (! $db->tableExists('application_rounds') || ! $db->tableExists('internship_applications')) {
            return [];
        }

        try {
            return $db->table('application_rounds ar')
                ->select('ar.id, ar.round_code, ar.title, ar.description, ar.opens_at, ar.closes_at')
                ->where('ar.status', 'Open')
                ->where('ar.opens_at <=', date('Y-m-d H:i:s'))
                ->where('ar.closes_at >=', date('Y-m-d H:i:s'))
                ->where('NOT EXISTS (SELECT 1 FROM internship_applications ia WHERE ia.round_id = ar.id AND ia.user_id = ' . (int) $userId . ')', null, false)
                ->orderBy('ar.closes_at', 'asc')
                ->get()
                ->getResultArray();
        } catch (\Throwable) {
            return [];
        }
    }

    private function userApplicationForRound(int $userId, int $roundId): ?array
    {
        if (! db_connect()->tableExists('internship_applications')) {
            return null;
        }

        try {
            return (new InternshipApplicationModel())
                ->where('user_id', $userId)
                ->where('round_id', $roundId)
                ->first();
        } catch (\Throwable) {
            return null;
        }
    }

    private function currentUser(): array
    {
        $userId = (int) session('user_id');
        $user = (new UserModel())->find($userId);

        if (! $user) {
            redirect()->to(site_url('login'))->with('error', 'Your session has expired. Please refresh and try again.')->send();
            exit;
        }

        return $user;
    }

    private function normalizeBangladeshiPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)]/', '', trim($phone)) ?? '';

        if (preg_match('/^(\+8801[3-9]\d{8})$/', $phone)) {
            return $phone;
        }

        if (preg_match('/^(01[3-9]\d{8})$/', $phone)) {
            return $phone;
        }

        if (preg_match('/^(8801[3-9]\d{8})$/', $phone)) {
            return '+' . $phone;
        }

        return $phone;
    }
}
