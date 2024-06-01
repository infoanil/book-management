<?php

namespace App\Console\Commands;

use App\Traits\CandidateApiTrait;
use Illuminate\Console\Command;

class CreateAuthor extends Command
{

    use CandidateApiTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:author';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Auther';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $body = [
            'first_name' => 'Royal',
            'last_name' => 'Apps',
            'birthday' => '1995-05-05',
            'biography' => 'Full Stack developer',
            'gender' => 'male',
            'place_of_birth' => 'Surat',
        ];
        $response = $this->createAuthor($body);

        if ($response->status() === 200) {
            return $this->info('Auther created successfully..!!');
        }

        return $this->error('failure ==> '.@$response->object()->exception);
    }
}
