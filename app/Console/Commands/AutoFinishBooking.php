<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\UserBookingRepository;
use App\Models\UserBooking;
use Carbon\Carbon;

class AutoFinishBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finishing expired booking';

    protected $bookingRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserBookingRepository $bookingRepo)
    {
        parent::__construct();
        $this->bookingRepo = $bookingRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userBookings = UserBooking::whereIn('status', [UserBooking::STATUS_CREATED, UserBooking::STATUS_APPROVED])
                       ->whereNotNull('event_date_end')
                       ->where('event_date_end', '<', Carbon::now()->addHours(24)->toDateString())
                       ->orderBy('event_date_end')
                       ->get();
        foreach ($userBookings as $booking) {
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', ( ($booking->event_date_end ? $booking->event_date_end->format('Y-m-d') : $booking->event_date_start->format('Y-m-d')) . " " . ($booking->event_end_time ? $booking->event_end_time : "23:59:59") ) );
            $hourDuration = Carbon::now()->diffInHours($endTime, false);
            if ($hourDuration >= 24) {
                if ($booking->status == UserBooking::STATUS_CREATED) $booking->status = UserBooking::STATUS_CANCELED;
                elseif ($booking->status == UserBooking::STATUS_APPROVED) $booking->status = UserBooking::STATUS_DONE;
                $booking->save();
                $this->info('Finishing booking #' . $booking->id);
            }
        }
    }
}
