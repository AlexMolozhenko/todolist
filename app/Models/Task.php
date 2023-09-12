<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    const DONE_STATUS = 'done';

    protected $fillable = ['title', 'description', 'status', 'priority', 'createdAt', 'completedAt'];

    /**
     * Boot method with model events.
     * -Before deleting a task, delete its subtasks
     */
    protected static function booted()
    {
        static::deleting(function ($task) {
            $task->subtasks()->delete();
        });
    }

    /**
     * Get the parent task.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Get the subtasks of the task.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Get the user associated with the task.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to get subtasks by parent ID.
     * @param $query
     * @param $parentId
     * @return mixed
     */
    public function scopeSubtuskByParent($query,$parentId){
        return $query->where('parent_id', '=', $parentId);
    }

    /**
     * Scope a query to get tasks by user ID.
     * @param $query
     * @param $userId
     * @return mixed
     */
     public function scopeOfUser($query,$userId){
        return $query->where('user_id', '=', $userId);
    }

    /**
     * Scope a query to get tasks without parents.
     * @param $query
     * @return mixed
     */
     public function scopeNoParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to filter tasks by status.
     * @param $query
     * @param $status
     * @return mixed
     */
     public function scopeStatus($query, $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope a query to filter tasks by priority range.
     * @param $query
     * @param $min
     * @param $max
     * @return mixed
     */
     public function scopePriorityBetween($query, $min, $max)
    {
        return $query->when($min, function ($q, $min) {
            return $q->where('priority', '>=', $min);
        })
            ->when($max, function ($q, $max) {
                return $q->where('priority', '<=', $max);
            });
    }

    /**
     * Scope a query to search tasks by title
     * @param $query
     * @param $title
     * @return mixed
     */
     public function scopeTitleSearch($query, $title)
    {
        if ($title) {
            $query->whereRaw("MATCH (title) AGAINST (? IN BOOLEAN MODE)", [$title . '*']);
        }
        return $query;
    }

    /**
     * Scope a query to order tasks by given parameters.
     * @param $query
     * @param string $order_by
     * @param string $order_direction
     * @return mixed
     */
     public function scopeOrderByCustom($query, $order_by = 'created_at',$order_direction = 'desc')
    {
            $query->orderBy($order_by, $order_direction);

        return $query;
    }

    /**
     * Update a task.
     * @param $userId
     * @param $updateData
     * @return bool
     */
    public function updateTask($userId , $updateData)
    {
        if ($this->user_id !== $userId) {
            return false;
        }

        $this->title       = $updateData['title'] ?? $this->title;
        $this->description = $updateData['description'] ?? $this->description;
        $this->priority    = $updateData['priority'] ?? $this->priority;

        $this->save();

        return true;
    }

    /**
     * Mark a task as done.
     * @param $userId
     * @return bool
     */
    public function completeTask($userId){

        if ($this->user_id !== $userId) {
            return false;
        }

        $unfinishedSubtasks = Task::where('parent_id', $this->id)
            ->where('status', '!=', self::DONE_STATUS)
            ->count();

        if ($unfinishedSubtasks > 0) {
            return false;
        }
        $time = $this->freshTimestamp();

        $this->status = self::DONE_STATUS;
        $this->completedAt = $time;
        $this->save();

        return true;
    }

    /**
     * Delete a task.
     * @param $userId
     * @return bool|null
     */
    public function deleteTask($userId){
        if ($this->user_id !== $userId) {
            return false;
        }
        if($this->status == self::DONE_STATUS ){
            return false;
        }

        $result =  $this->delete();
        return $result;
    }
}
