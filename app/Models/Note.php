<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class Note
 *
 * @package App\Models
 * @property int         $id
 * @property int         $user_id
 * @property int         $link_id
 * @property string      $note
 * @property int         $visibility
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Link   $link
 * @property-read User   $user
 * @method static Builder|Note byUser($user_id = null)
 * @method static Builder|Note privateOnly()
 * @method static Builder|Note internalOnly()
 * @method static Builder|Note publicOnly()
 */
class Note extends Model
{
    use HasFactory;
    use ScopesForUser;
    use ScopesVisibility;
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'link_id',
        'note',
        'visibility',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'link_id' => 'integer',
        'visibility' => 'integer',
    ];

    public string $langBase = 'note';

    /*
     * ========================================================================
     * RELATIONSHIPS
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class, 'link_id');
    }

    /*
     * ========================================================================
     * METHODS
     */

    /**
     * Get the formatted note content of the note
     *
     * @return string
     */
    public function getFormattedNoteAttribute(): string
    {
        if ($this->note === null) {
            return '';
        }

        if (usersettings('markdown_for_text') === false) {
            return htmlentities($this->note);
        }

        return Str::markdown($this->note, ['html_input' => 'escape']);
    }

    /**
     * Output a relative time inside a span with real time information
     *
     * @return string
     */
    public function addedAt(): string
    {
        $output = '<time-ago class="cursor-help"';
        $output .= ' datetime="' . $this->created_at->toIso8601String() . '"';
        $output .= ' title="' . formatDateTime($this->created_at) . '">';
        $output .= formatDateTime($this->created_at, true);
        $output .= '</time-ago>';

        return $output;
    }
}
