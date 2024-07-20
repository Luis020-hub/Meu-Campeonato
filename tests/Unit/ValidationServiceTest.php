<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ValidationService;

class ValidationServiceTest extends TestCase
{
    public function testValidateTeamsWithValidInput()
    {
        $validationService = new ValidationService();
        $teams = ['Team1', 'Team2', 'Team3', 'Team4', 'Team5', 'Team6', 'Team7', 'Team8'];
        $result = $validationService->validateTeams($teams);

        $this->assertTrue($result['isValid']);
        $this->assertEquals('', $result['message']);
    }

    public function testValidateTeamsWithInvalidInput()
    {
        $validationService = new ValidationService();
        $teams = ['Team1', 'Team2', 'Team3', 'Team4'];
        $result = $validationService->validateTeams($teams);

        $this->assertFalse($result['isValid']);
        $this->assertEquals('You must enter with 8 teams.', $result['message']);
    }
}