<?php

namespace Leobsst\LaravelCmsCore\Models;

use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Mail\ExportLogs;
use Leobsst\LaravelCmsCore\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Log model
 *
 * @property string $type
 * @property string $message
 * @property int $creator_id
 * @property string $ip_address
 * @property string $reference_table
 * @property int $reference_id
 * @property array $data
 * @property string $status
 * @property User $creator
 */
class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'message',
        'creator_id',
        'reference_table',
        'reference_id',
        'ip_address',
        'data',
        'status',
    ];

    protected $casts = [
        'type' => LogType::class,
        'data' => 'array',
        'status' => LogStatus::class,
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public static function sendLogsToEmail()
    {
        Mail::to([auth()->user()->email])->send(new ExportLogs(Log::orderByDesc('created_at')->get()));
        auth()->user()->log(LogType::INFO, 'Logs exported to email', LogStatus::SUCCESS);
    }
}
