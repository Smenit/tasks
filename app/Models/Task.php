<?php

namespace App\Models;

use App\Models\Concerns\UuidTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constants\Task as TaskConstants;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * Class Builder
 *
 * @method static Task|Builder orderByStatus()
 * @method static Task|Builder orderByPriority()
 * @property string $id
 * @property string $name
 * @property string $priority
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereName($value)
 * @method static Builder|Task wherePriority($value)
 * @method static Builder|Task whereStatus($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use UuidTrait;

    protected $fillable = [
        'name',
        'priority',
        'status',
    ];
    protected $with = ['tags'];

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tasks_tags', 'task_id', 'tag_id');
    }

    /**
     * @param array $newTags
     */
    public function syncTags(array $newTags): void
    {
        /** @var Collection $currentTags */
        $currentTags = $this->tags;
        $newTags     = new Collection($newTags);

        $actualIdsTags = $currentTags->map(function ($tag) use ($newTags) {
            return $newTags->search($tag->name) !== false ? $tag->id : false;
        });

        $notSyncTags = $newTags->diff($currentTags->pluck('name'));

        $countActual = $actualIdsTags->count();
        $countNew    = $newTags->count() + $notSyncTags->count();

        if ($countActual === $countNew) {
            return;
        }

        foreach ($notSyncTags as $tagName) {
            $tagId = Tag::firstOrCreate(['name' => $tagName])->id;
            $actualIdsTags->add($tagId);
        }

        $this->tags()->sync($actualIdsTags->toArray());
    }

    /**
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderByStatus(Builder $query): Builder
    {
        $sortedStatuses = implode('", "', TaskConstants::STATUSES);
        return $query->orderByRaw(sprintf('FIELD(`status`, "%s")', $sortedStatuses));
    }

    /**
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderByPriority(Builder $query): Builder
    {
        $sortedPriorities = implode('", "', TaskConstants::PRIORITIES);
        return $query->orderByRaw(sprintf('FIELD(`priority`, "%s")', $sortedPriorities));
    }
}
