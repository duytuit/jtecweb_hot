<?php

namespace App\Repositories;

use App\Models\Exam;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ExamRepository
{
    /**
     * @var exam
     */
    protected $exam;

    /**
     * Instantiate a new Repository instance.
     *
     * @param exam $exam
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get all exams
     *
     * @return array
     */
    public function getAll()
    {
        return $this->exam->all();
    }

    /**
     * List exam names
     *
     * @param array $ids
     *
     * @return array
     */
    public function listByName($ids = [])
    {
        if (count($ids)) {
            return $this->exam->whereIn('id', $ids)->get()->pluck('name')->all();
        } else {
            return $this->exam->all()->pluck('name')->all();
        }
    }

    /**
     * List all exam names
     *
     * @return array
     */
    public function listName()
    {
        return $this->exam->all()->pluck('name')->all();
    }

    /**
     * Find exam with given id.
     *
     * @param int $id
     *
     * @return exam
     * @throws ValidationException
     */
    public function findOrFail($id)
    {
        $exam = $this->exam->find($id);

        if (!$exam) {
            throw ValidationException::withMessages(['message' => trans('exam.could_not_find')]);
        }

        return $exam;
    }

    /**
     * Paginate all exams using given params.
     *
     * @param array $params
     *
     * @return LengthAwarePaginator
     */
    public function paginate($params)
    {
        $sort_by = isset($params['sort_by']) ? $params['sort_by'] : 'created_at';
        $order = isset($params['order']) ? $params['order'] : 'desc';
        $page_length = isset($params['page_length']) ? $params['page_length'] : config('config.page_length');

        return $this->exam->orderBy($sort_by, $order)->paginate($page_length);
    }

    /**
     * Record a new exam.
     *
     * @param array $params
     *
     * @return exam
     */
    public function record($params)
    {
        return $this->exam->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     *
     * @return array
     */
    private function formatParams($params)
    {
        $formatted = [
            'user_id' => isset($params['userId']) ? $params['userId'] : Auth::user()->id,
            'module' => isset($params['module']) ? $params['module'] : null,
            'module_id' => isset($params['module_id']) ? $params['module_id'] : null,
            'sub_module' => isset($params['sub_module']) ? $params['sub_module'] : null,
            'sub_module_id' => isset($params['sub_moduleId']) ? $params['sub_moduleId'] : null,
            'activity' => isset($params['activity']) ? $params['activity'] : null
            // 'user_agent' => \Request::header('User-Agent')
        ];

        return $formatted;
    }

    /**
     * Delete exam.
     *
     * @param exam $exam
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(exam $exam)
    {
        return $exam->delete();
    }

    /**
     * Delete multiple exams.
     *
     * @param array $ids
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteMultiple($ids)
    {
        return $this->exam->whereIn('id', $ids)->delete();
    }
}