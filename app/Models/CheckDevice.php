<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckDevice extends Model
{
    use HasFactory, ActivityLogger,SoftDeletes;
    protected $guarded = [];

    protected $memoryType;
    public function scopeFilter($query, $input)
    {
        foreach ($this->seachable as $value) {
            if (isset($input[$value])) {
                $query->where($value, $input[$value]);
            }
        }
        if (isset($input['keyword'])) {
            $search = $input['keyword'];
            $query->where(function ($q) use ($search) {
                foreach ($this->seachable as $value) {
                    $q->orWhere($value, 'LIKE', '%' . $search . '%');
                }
            });
        }
        return $query;
    }
    protected $format;



    //get wifi name
    // OK
    public function getWifiSSID()
    {
        $output = shell_exec('netsh wlan show interfaces');
        if ($output) {
            if (preg_match('/SSID\s*:\s*(.*)/i', $output, $matches)) {
                return $matches[1];
            } else {
                return 'SSID not found';
            }
        } else {
            return 'Command failed';
        }
    }
    public function getComputerName(): ?string
    {
        try {
            $output = shell_exec('hostname');
            return trim($output);
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    public function getProcessorInfo(): ?string
    {
        try {
            $output = shell_exec('wmic cpu get name');
            return trim($output);
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    public function getOSInfo(): ?string
    {
        try {
            $output = shell_exec('wmic os get caption');
            return trim($output);
        } catch (\Throwable $throwable) {
            return null;
        }
    }
}
