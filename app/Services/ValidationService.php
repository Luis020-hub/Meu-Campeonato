<?php

namespace App\Services;

class ValidationService
{
    public function validateTeams(array $teams): array
    {
        $response = ['isValid' => true, 'message' => ''];

        if (count($teams) !== 8) {
            $response['isValid'] = false;
            $response['message'] = 'You must enter with 8 teams.';
        }

        return $response;
    }
}