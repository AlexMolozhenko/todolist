<?php


namespace App\Services;


use App\Models\Task;
use Illuminate\Database\Eloquent\Model;

class TaskService
{

    /**
     * Get all tasks based on the specified parameters
     * @param $dataFilter
     * @return array
     */
    static public function getAllTaskListByFilter($dataFilter){
        $tasks = Task::noParent()
            ->status($dataFilter['status'] ?? null)
            ->priorityBetween($dataFilter['priority_min'] ?? null, $dataFilter['priority_max'] ?? null)
            ->titleSearch($dataFilter['title'] ?? null)
            ->orderByCustom($dataFilter['order_by'] ?? 'created_at', $dataFilter['order_direction'] ?? 'desc')
            ->get();

        if (!$tasks) {
            return [
                'data'=>[
                    'message' => 'tasks not found '
                ],
                'status'=>401
            ];
        }

        return [
            'data'=>$tasks,
            'status'=>200
        ];

    }

    /**
     * Retrieve all tasks for the specified parameters for the user
     * @param $userId
     * @param $dataFilter
     * @return array
     */
    static public function getUserTaskByFilter($userId,$dataFilter){

        $tasks = Task::ofUser($userId)
            ->noParent()
            ->status($dataFilter['status'] ?? null)
            ->priorityBetween($dataFilter['priority_min'] ?? null, $dataFilter['priority_max'] ?? null)
            ->titleSearch($dataFilter['title'] ?? null)
            ->orderByCustom($dataFilter['order_by'] ?? 'created_at', $dataFilter['order_direction'] ?? 'desc')
            ->get();
        if (!$tasks) {
            return [
                'data'=>[
                    'message' => 'User-tasks not found '
                ],
                'status'=>401
            ];
        }
        return [
            'data'=>$tasks,
            'status'=>200
        ];
    }

    /**
     * Get all subtasks according to the specified parameters for a given $taskId
     * @param $tuskId
     * @return array
     */
    static public function getSubtuskForTuskById($dataFilter){
        $tasks = Task::subtuskByParent($dataFilter['task_id'])
            ->status($dataFilter['status'] ?? null)
            ->priorityBetween($dataFilter['priority_min'] ?? null, $dataFilter['priority_max'] ?? null)
            ->titleSearch($dataFilter['title'] ?? null)
            ->orderByCustom($dataFilter['order_by'] ?? 'created_at', $dataFilter['order_direction'] ?? 'desc')
            ->get();
        if (!$tasks) {
            return [
                'data'=>[
                    'message' => 'subtasks not found '
                ],
                'status'=>401
            ];
        }
        return [
            'data'=>$tasks,
            'status'=>200
        ];
    }

    /**
     * Get task or subtask by id
     * @param $id
     * @return array
     */
    static public function getTaskOrSubtaskById($id){

        $task = Task::where('id','=',$id)->first();
        if (!$task) {
            return [
                'data'=>[
                    'message' => 'task or subtask not found '
                ],
                'status'=>401
            ];
        }
        return [
            'data'=>$task,
            'status'=>200
        ];
    }

    /**
     * create a task
     * @param $user_id
     * @param $dataTask
     * @return array
     */
    static public function createTask($user_id,$dataTask){
        $task = new Task;
        $task->user_id = $user_id;
        $task->title = $dataTask['title'];
        $task->description = $dataTask['description'];
        $task->priority = $dataTask['priority'];
        $task->status = 'todo';
        $task->save();

        return [
            'data'=>$task,
            'status'=>200
        ];
    }
    /**
     * create a subtask for task_id
     * @param $user_id
     * @param $dataTask
     * @return array
     */
    static public function createSubtask($user_id,$dataTask){
        $task = new Task;
        $task->user_id = $user_id;
        $task->parent_id = $dataTask['task_id'];
        $task->title = $dataTask['title'];
        $task->description = $dataTask['description'];
        $task->priority = $dataTask['priority'];
        $task->status = 'todo';
        $task->save();

        return [
            'data'=>$task,
            'status'=>200
        ];
    }

    /**
     * updating a task that belongs to the user
     * @param $user_id
     * @param $updateDataTask
     * @return array
     */
    static public function updateTask($user_id,$updateDataTask){

        $task = Task::find($updateDataTask['task_id']);

        if ($task && $task->updateTask($user_id,$updateDataTask)) {
            return [
                'data'=>$task,
                'status'=>200
            ];
        } else {
            return [
                'data'=>[
                    'message' => 'access to changing the task is blocked'
                ],
                'status'=>403
            ];
        }
    }

    /**
     * change the task status to "done"
     * @param $user_id
     * @param $task_id
     * @return array
     */
    static public function completedTask($user_id,$task_id){
        $task = Task::find($task_id);

        if ($task && $task->completeTask($user_id)) {
            return [
                'data'=>$task,
                'status'=>200
            ];
        } else {
            return [
                'data'=>[
                    'message' => 'this task cannot be completed'
                ],
                'status'=>403
            ];
        }
    }

    /**
     * deleting a task if the task is not completed
     * @param $user_id
     * @param $task_id
     * @return array
     */
    static public function deleteTask($user_id,$task_id){
        $task = Task::find($task_id);

        $result = $task->deleteTask($user_id);
        if ($task && $result) {
            return [
                'data'=>$result,
                'status'=>200
            ];
        } else {
            return [
                'data'=>[
                    'message' => 'this task cannot be deleted'
                ],
                'status'=>403
            ];
        }
    }
}
