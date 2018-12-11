<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Assert0000ConfigTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
//        /*echo "Making migrations refresh";
        Artisan::call('migrate:refresh');

        echo "Making seed";
        Artisan::call('db:seed');
        $this->assertTrue(true);
    }
}
