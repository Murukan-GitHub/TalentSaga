<?php

namespace App\Console\Commands;

use DB;
use Mail;
use App\Repositories\NewsletterRepository;
use Illuminate\Console\Command;

class SendNewsletter extends Command
{
    protected $newsletter;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Newsletter Email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NewsletterRepository $newsletter)
    {
        parent::__construct();
        $this->newsletter = $newsletter;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('FIRE!!!');
        $nbNewsletterExecuted = $this->newsletter->execute();
        if ($nbNewsletterExecuted) {
            $this->info($nbNewsletterExecuted . ' newsletter terkirim!');
        } else {
            $this->info('Tidak ada newsletter yang terkirim');
        }
    }
}
