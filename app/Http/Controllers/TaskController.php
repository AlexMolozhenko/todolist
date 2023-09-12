<?php

namespace App\Http\Controllers;


use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/task/list",
     *      operationId="getTasks",
     *      tags={"Tasks"},
     *      summary="Get list of tasks based on filters",
     *      description="Returns list of tasks",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="in:todo,done|nullable"),
     *              @OA\Property(property="priority_min", type="integer", example="integer|min:1|max:5|nullable"),
     *              @OA\Property(property="priority_max", type="integer", example="integer|min:1|max:5|nullable"),
     *              @OA\Property(property="title", type="string", example="string|nullable"),
     *              @OA\Property(property="order_by", type="string", example="in:created_at,completedAt,priority|nullable"),
     *              @OA\Property(property="order_direction", type="string", example="in:asc,desc|nullable")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=94),
     *                  @OA\Property(property="user_id", type="integer", example=12),
     *                  @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="todo"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="completedAt", type="string", example="null"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *
     *              )
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function getTasks(Request $request){
        $fields = $request->validate([
            'status' => 'in:todo,done|nullable',
            'priority_min' => 'integer|min:1|max:5|nullable',
            'priority_max' => 'integer|min:1|max:5|nullable',
            'title' => 'string|nullable',
            'order_by' => 'in:created_at,completedAt,priority|nullable',
            'order_direction' => 'in:asc,desc|nullable',
        ]);
        $taskData = TaskService::getAllTaskListByFilter($fields);
        return response()->json($taskData['data'], $taskData['status']);
    }

    /**
     * @OA\Post(
     *      path="/api/task/user_task",
     *      operationId="getUserTasks",
     *      tags={"Tasks"},
     *      summary="Get user tasks based on filters",
     *      description="Returns user tasks",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="in:todo,done|nullable"),
     *              @OA\Property(property="priority_min", type="integer", example="integer|min:1|max:5|nullable"),
     *              @OA\Property(property="priority_max", type="integer", example="integer|min:1|max:5|nullable"),
     *              @OA\Property(property="title", type="string", example="string|nullable"),
     *              @OA\Property(property="order_by", type="string", example="in:created_at,completedAt,priority|nullable"),
     *              @OA\Property(property="order_direction", type="string", example="in:asc,desc|nullable")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=94),
     *                  @OA\Property(property="user_id", type="integer", example=12),
     *                  @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="todo"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="completedAt", type="string", example="null"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *
     *              )
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function getUserTasks(Request $request){
        $fields = $request->validate([
            'status' => 'in:todo,done|nullable',
            'priority_min' => 'integer|min:1|max:5|nullable',
            'priority_max' => 'integer|min:1|max:5|nullable',
            'title' => 'string|nullable',
            'order_by' => 'in:created_at,completedAt,priority|nullable',
            'order_direction' => 'in:asc,desc|nullable',
        ]);

        $user = $request->user();

        $taskData = TaskService::getUserTaskByFilter($user->id,$fields);

        return response()->json($taskData['data'], $taskData['status']);
    }

    /**
     * @OA\Post(
     *      path="/api/task/subtask",
     *      operationId="getSubtasks",
     *      tags={"Tasks"},
     *      summary="Get Subtasks based on filters and task_id",
     *      description="Returns subtasks",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="task_id", type="string", example="required|integer"),
     *              @OA\Property(property="status", type="string", example="in:todo,done|nullable"),
     *              @OA\Property(property="priority_min", type="integer", example="integer|min:1|max:5|nullable"),
     *              @OA\Property(property="priority_max", type="integer", example="integer|min:1|max:5|nullable"),
     *              @OA\Property(property="title", type="string", example="string|nullable"),
     *              @OA\Property(property="order_by", type="string", example="in:created_at,completedAt,priority|nullable"),
     *              @OA\Property(property="order_direction", type="string", example="in:asc,desc|nullable")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=94),
     *                  @OA\Property(property="user_id", type="integer", example=12),
     *                  @OA\Property(property="parent_id", type="integer", nullable=true, example=92),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="todo"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="completedAt", type="string", example="null"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *
     *              )
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function getSubtasks(Request $request){
        $fields = $request->validate([
            'task_id'=>'required|integer',
            'status' => 'in:todo,done|nullable',
            'priority_min' => 'integer|min:1|max:5|nullable',
            'priority_max' => 'integer|min:1|max:5|nullable',
            'title' => 'string|nullable',
            'order_by' => 'in:created_at,completedAt,priority|nullable',
            'order_direction' => 'in:asc,desc|nullable',
        ]);

        $taskData = TaskService::getSubtuskForTuskById($fields);

        return response()->json($taskData['data'], $taskData['status']);
    }

    /**
     * @OA\Get(
     *      path="/api/task/{task_id}",
     *      operationId="getTaskId",
     *      tags={"Tasks"},
     *      summary="Get task by task_id",
     *      description="Returns task",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="task_id",
     *          in="path",
     *          required=true,
     *          description="The ID of the task",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=94),
     *                  @OA\Property(property="user_id", type="integer", example=12),
     *                  @OA\Property(property="parent_id", type="integer", nullable=true, example=92),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="todo"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="completedAt", type="string", nullable=true, example="null"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function getTaskById(Request $request,int $task_id){

        $taskData = TaskService::getTaskOrSubtaskById($task_id);

        return response()->json($taskData['data'], $taskData['status']);
    }

    /**
     * @OA\Post(
     *      path="/api/task/create_task",
     *      operationId="create task",
     *      tags={"Tasks"},
     *      summary="create task ",
     *      description="create task",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="priority", type="integer", example="required|integer|min:1|max:5"),
     *              @OA\Property(property="title", type="string", example="required|string"),
     *              @OA\Property(property="description", type="integer", example="required|string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=120),
     *                  @OA\Property(property="user_id", type="integer", nullable=true, example=12),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="todo"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *
     *              )
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function createTask(Request $request){
        $fields = $request->validate([
            'priority' => 'required|integer|min:1|max:5',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $user = $request->user();
        $taskData = TaskService::createTask($user->id,$fields);

        return response()->json($taskData['data'], $taskData['status']);
    }

    /**
     * @OA\Post(
     *      path="/api/task/create_subtask",
     *      operationId="create subtask",
     *      tags={"Tasks"},
     *      summary="create subtask ",
     *      description="create subtask",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="task_id", type="integer", example="required|integer"),
     *              @OA\Property(property="priority", type="integer", example="required|integer|min:1|max:5"),
     *              @OA\Property(property="title", type="string", example="required|string"),
     *              @OA\Property(property="description", type="integer", example="required|string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=120),
     *                  @OA\Property(property="user_id", type="integer", nullable=true, example=12),
     *     @OA\Property(property="parent_id", type="integer", nullable=true, example=119),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="todo"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *
     *              )
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function createSubtask(Request $request){
        $fields = $request->validate([
            'priority' => 'required|integer|min:1|max:5',
            'title' => 'required|string',
            'description' => 'required|string',
            'task_id'=>'required|integer'
        ]);

        $user = $request->user();
        $subtaskData = TaskService::createSubtask($user->id,$fields);

        return response()->json($subtaskData['data'], $subtaskData['status']);
    }


    /**
     * @OA\Post(
     *      path="/api/task/update",
     *      operationId="update subtask",
     *      tags={"Tasks"},
     *      summary="update subtask ",
     *      description="update subtask",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="task_id", type="integer", example="required|integer"),
     *              @OA\Property(property="priority", type="integer", example="integer|min:1|max:5"),
     *              @OA\Property(property="title", type="string", example="string"),
     *              @OA\Property(property="description", type="integer", example="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=120),
     *                  @OA\Property(property="user_id", type="integer", nullable=true, example=12),
     *     @OA\Property(property="parent_id", type="integer", nullable=true, example=119),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="todo"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="completedAt", type="string", format="datetime",example="null"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *
     *              )
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function updateTask(Request $request){
        $fields = $request->validate([
            'priority' => 'integer|min:1|max:5',
            'title' => 'string',
            'description' => 'string',
            'task_id'=>'required|integer'
        ]);

        $user = $request->user();
        $subtaskData = TaskService::updateTask($user->id,$fields);

        return response()->json($subtaskData['data'], $subtaskData['status']);
    }

    /**
     * @OA\Post(
     *      path="/api/task/completed",
     *      operationId="completed task",
     *      tags={"Tasks"},
     *      summary="completed task ",
     *      description="completed task",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="task_id", type="integer", example="required|integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=120),
     *                  @OA\Property(property="user_id", type="integer", nullable=true, example=12),
     *     @OA\Property(property="parent_id", type="integer", nullable=true, example=119),
     *                  @OA\Property(property="title", type="string", example="Task 0 for UserWest Nataliaville"),
     *                  @OA\Property(property="description", type="string", example="Description for task 0 of UserEast Francescaton"),
     *                  @OA\Property(property="status", type="string", example="done"),
     *                  @OA\Property(property="priority", type="string", example="1"),
     *                  @OA\Property(property="completedAt", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:31.000000Z"),
     *
     *              )
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function completedTask(Request $request){
        $fields = $request->validate([
            'task_id'=>'required|integer'
        ]);

        $user = $request->user();
        $subtaskData = TaskService::completedTask($user->id,$fields['task_id']);

        return response()->json($subtaskData['data'], $subtaskData['status']);
    }


    /**
     * @OA\Post(
     *      path="/api/task/delete",
     *      operationId="delete task",
     *      tags={"Tasks"},
     *      summary="delete task ",
     *      description="delete task",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="task_id", type="integer", example="required|integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          )),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function deleteTask(Request $request){
        $fields = $request->validate([
            'task_id'=>'required|integer'
        ]);

        $user = $request->user();
        $subtaskData = TaskService::deleteTask($user->id,$fields['task_id']);

        return response()->json($subtaskData['data'], $subtaskData['status']);
    }



}
