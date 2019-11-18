<?php
namespace App\Repositories;

use Cache;
use App\Models\UserBooking;
use Suitcore\Repositories\SuitRepository;

class UserBookingRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserBooking;
    }

    // is[somestatus] == 1 : true  ,,, is[somestatus] == 2 : false ,,, 0 : ignored
    public function getLatestCandidates($nbFetch, $isCreated = 0, $isApproved = 0, $isRejected = 0, $isCanceled = 0, $isDone = 0, $minutesCached = 1, $paginated = false) {
        $baseObject = clone $this->mainModel;
        if (!$paginated && $minutesCached) {
            // cached (temporary solution, should using revalidate pattern)
            return Cache::remember('latest_bookings_'.$minutesCached, $minutesCached, function() use ($nbFetch, $isCreated, $isApproved, $isRejected, $isCanceled, $isDone) {
                    $userBookings = UserBooking::with(['user', 'talentUser'])->distinct('user_bookings.user_id')->orderBy('user_bookings.created_at', 'desc');
                    if ($isCreated) $userBookings = $userBookings->where('status', UserBooking::STATUS_CREATED );
                    elseif ($isApproved) $userBookings = $userBookings->where('status', UserBooking::STATUS_APPROVED );
                    elseif ($isRejected) $userBookings = $userBookings->where('status', UserBooking::STATUS_REJECTED );
                    elseif ($isCanceled) $userBookings = $userBookings->where('status', UserBooking::STATUS_CANCELED );
                    elseif ($isDone) $userBookings = $userBookings->where('status', UserBooking::STATUS_DONE );
                    $userBookings = $userBookings->take($nbFetch)->get();
                    return $userBookings;
                }
            );
        }
        // non cached
        $userBookings = VacancyCandidate::with(['vacancy', 'candidate'])->distinct('user_bookings.user_id')->join('vacancies', 'vacancies.id', '=', 'user_bookings.vacancy_id')->where('vacancies.company_id', $companyId)->orderBy('user_bookings.matched_at', 'desc');
        if ($isCreated) $userBookings = $userBookings->where('status', UserBooking::STATUS_CREATED );
        elseif ($isApproved) $userBookings = $userBookings->where('status', UserBooking::STATUS_APPROVED );
        elseif ($isRejected) $userBookings = $userBookings->where('status', UserBooking::STATUS_REJECTED );
        elseif ($isCanceled) $userBookings = $userBookings->where('status', UserBooking::STATUS_CANCELED );
        elseif ($isDone) $userBookings = $userBookings->where('status', UserBooking::STATUS_DONE );
        
        if ($paginated) {
            $userBookings = $userBookings->paginate($nbFetch);
        } else {
            $userBookings = $userBookings->take($nbFetch)->get();
        }
        return $userBookings;
    }
}
