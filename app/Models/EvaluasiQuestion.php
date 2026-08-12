<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class EvaluasiQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluasi_menu_id',
        'question_key',
        'pertanyaan',
    ];

    public function evaluasiMenu()
    {
        return $this->belongsTo(EvaluasiMenu::class, 'evaluasi_menu_id');
    }

    /**
     * Get rating questions (q1, q2, ...) for a specific menu.
     * If custom questions exist in DB, return ONLY DB rating questions.
     * Fallback to default static array if no custom configuration exists.
     */
    public static function getQuestionsForMenu(int $menuId, array $defaultQuestions): array
    {
        try {
            if (!Schema::hasTable('evaluasi_questions')) {
                return $defaultQuestions;
            }

            $dbQuestions = static::where('evaluasi_menu_id', $menuId)
                ->where('question_key', 'LIKE', 'q%')
                ->orderBy('id')
                ->pluck('pertanyaan', 'question_key')
                ->toArray();

            if (!empty($dbQuestions)) {
                return $dbQuestions;
            }

            return $defaultQuestions;
        } catch (\Throwable $e) {
            return $defaultQuestions;
        }
    }

    /**
     * Get essay/saran questions (s1, s2, ...) for a specific menu.
     * If custom config exists, return DB saran questions (could be empty array if all removed).
     * Fallback to default saran array if no custom config exists.
     */
    public static function getSaranQuestions(int $menuId, array $defaultSaran = ['s1' => 'Berikan saran dan masukan terhadap pemateri']): array
    {
        try {
            if (!Schema::hasTable('evaluasi_questions')) {
                return $defaultSaran;
            }

            $hasCustomConfig = static::where('evaluasi_menu_id', $menuId)->exists();
            if (!$hasCustomConfig) {
                return $defaultSaran;
            }

            $dbSaran = static::where('evaluasi_menu_id', $menuId)
                ->where(function ($q) {
                    $q->where('question_key', 'LIKE', 's%')
                      ->orWhere('question_key', 'saran_dan_masukan');
                })
                ->orderBy('id')
                ->pluck('pertanyaan', 'question_key')
                ->toArray();

            return $dbSaran;
        } catch (\Throwable $e) {
            return $defaultSaran;
        }
    }
}
