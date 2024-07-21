<?php

namespace Tests\Unit;


use App\Exceptions\BaseMessageException;
use App\Services\CurrencyService;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    /**
     * A basic test example.
     * @throws BaseMessageException
     */
    public function testGetCourses(): void
    {
        $service = $this->app->make(CurrencyService::class);

        $courses = $service->getCourses();

        $this->assertNotEmpty($courses);
        $this->assertIsArray($courses);

        $firstCourse = array_shift($courses);
        $this->assertArrayHasKey('currency', $firstCourse);
        $this->assertArrayHasKey('nominal', $firstCourse);
        $this->assertArrayHasKey('value', $firstCourse);
        $this->assertArrayHasKey('name', $firstCourse);
        $this->assertArrayHasKey('rate', $firstCourse);
    }
}
