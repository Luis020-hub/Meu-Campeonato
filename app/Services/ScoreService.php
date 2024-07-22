<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ScoreService
{
    public function generateScore(): array
    {
        $output = [];
        $returnVar = null;
        $pythonPath = 'python';
        $scriptPath = base_path('teste.py');
        $command = "$pythonPath $scriptPath 2>&1";

        exec($command, $output, $returnVar);

        Log::info('Python script command:', [$command]);
        Log::info('Python script output:', $output);
        Log::info('Python script return var:', [$returnVar]);

        if ($returnVar !== 0) {
            Log::error('Python script failed to execute', [
                'command' => $command,
                'output' => $output,
                'return_var' => $returnVar
            ]);
            throw new \Exception('Python script failed to execute');
        }

        return array_map('intval', $output);
    }
}