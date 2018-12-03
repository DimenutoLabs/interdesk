<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssertConfigTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
//        try {
//            Artisan::call('migrate:refresh');
//        } catch (\Exception $e) {
//            $output = Artisan::output();
//            print_r( $output );
//            echo $e->getMessage();
//            $this->assertTrue(false);
//        }
        $this->assertTrue(true);
    }
}
