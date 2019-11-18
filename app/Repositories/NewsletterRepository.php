<?php

namespace App\Repositories;

use Suitcore\Repositories\SuitRepository;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Mail;

class NewsletterRepository extends SuitRepository
{
    public $email;

    public function __construct()
    {
        $this->mainModel = new Newsletter;
    }

    public function getRecipients(Newsletter $newsletter)
    {
        $recipientType = $newsletter->recipient;
        $prevNbRecepient = ($newsletter->actual_nb_recepient ? $newsletter->actual_nb_recepient : 0);
        $maxNbRecepient = ($newsletter->limited_nb_sent && $newsletter->limited_nb_sent >= 0 ? ($newsletter->limited_nb_sent - $prevNbRecepient) : Newsletter::UNLIMITED_SENT);
        $emailList = [];
        if ($recipientType == Newsletter::GUEST_SUBSCRIBER) {
            $emailList = $this->getGuestSubscriberEmails($maxNbRecepient);
        }
        if ($recipientType == Newsletter::USER) {
            $emailList = $this->getUserEmails($maxNbRecepient);
        }
        if ($recipientType == Newsletter::ADMIN) {
            $emailList = $this->getAdminEmails($maxNbRecepient);
        }
        if ($recipientType == Newsletter::ALL) {
            $emailList = array_merge(
                $this->getGuestSubscriberEmails(Newsletter::UNLIMITED_SENT),
                $this->getUserEmails(Newsletter::UNLIMITED_SENT)
            );
            $emailList = array_slice($emailList, 0, $maxNbRecepient);
        }
        foreach ($emailList as $email => $name) {
            if (!$this->validateEmail($email)) {
                unset($emailList[$email]);
            }
        }
        return $emailList;
    }

    private function validateEmail($email) {
        $pattern = '/^("[\w-\.\s]+")|(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/i';
        if(preg_match($pattern, $email))
            return true;
        else
            return false;
    }

    protected function getGuestSubscriberEmails($maxNbRecepient)
    {
        $emails = NewsletterSubscriber::whereRaw("email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$'");
        if ($maxNbRecepient > Newsletter::UNLIMITED_SENT) {
            $emails = $emails->take($maxNbRecepient);
        }
        $emails = $emails->pluck('name', 'email')->toArray();
        return $emails;
    }

    protected function getUserEmails($maxNbRecepient)
    {
        $emails = User::whereRaw("email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$'");
        if ($maxNbRecepient > Newsletter::UNLIMITED_SENT) {
            $emails = $emails->take($maxNbRecepient);
        }
        $emails = $emails->pluck('name', 'email')->toArray();
        return $emails;
    }

    protected function getAdminEmails($maxNbRecepient)
    {
        $emails = User::where('role', User::ADMIN)->whereRaw("email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$'")->where('newsletter', 1);
        if ($maxNbRecepient > Newsletter::UNLIMITED_SENT) {
            $emails = $emails->take($maxNbRecepient);
        }
        $emails = $emails->pluck('name', 'email')->toArray();
        return $emails;
    }

    public function manualSingleExecution($emailAddressList, $recepientType)
    {
        $nbExecuted = 0;
        if (is_array($emailAddressList)) {
            $newsletters = $this->mainModel->where('status', Newsletter::READYTOEXECUTE)->where('recipient', '=', $recepientType)->get();
            foreach ($newsletters as $key => $newsletter) {
                $newsletter->actual_nb_recepient = ($newsletter->actual_nb_recepient ? $newsletter->actual_nb_recepient : 0);
                if (($newsletter->limited_nb_sent == Newsletter::UNLIMITED_SENT) || 
                    ($newsletter->limited_nb_sent > Newsletter::UNLIMITED_SENT && $newsletter->actual_nb_recepient < $newsletter->limited_nb_sent)) {
                    $nbFreeSlot = $newsletter->limited_nb_sent - $newsletter->actual_nb_recepient;
                    $this->email = array_slice($emailAddressList, 0, $nbFreeSlot);
                    // dd($newsletter->actual_nb_recepient, $newsletter->limited_nb_sent, $nbFreeSlot, $this->email);
                    $this->notify($newsletter);
                    $nextActualNbRecepient = ($newsletter->actual_nb_recepient + ($this->email && is_array($this->email) ? count($this->email) : 0) );
                    // dd($newsletter->actual_nb_recepient, ($this->email && is_array($this->email) ? count($this->email) : 0), $nextActualNbRecepient);
                    $newsletter->update([
                        'actual_nb_recepient' => $nextActualNbRecepient,
                        'status' => ($newsletter->limited_nb_sent != Newsletter::UNLIMITED_SENT && $nextActualNbRecepient >= $newsletter->limited_nb_sent ? Newsletter::EXECUTED : Newsletter::READYTOEXECUTE)
                    ]);
                    $nbExecuted++;
                }
            }
        }
        return $nbExecuted;
    }

    public function execute()
    {
        $newsletters = $this->mainModel->where('status', Newsletter::READYTOEXECUTE)->where('recipient', '<>', Newsletter::NEW_GUEST_SUBSCRIBER)->get();
        $nbExecuted = 0;
        foreach ($newsletters as $key => $newsletter) {
            $this->email = $this->getRecipients($newsletter);
            $this->notify($newsletter);
            $newsletter->update([
                'actual_nb_recepient' => ($this->email && is_array($this->email) ? count($this->email) : 0),
                'status' => Newsletter::EXECUTED
            ]);
            $nbExecuted++;
        }
        return $nbExecuted;
    }

    protected function notify(Newsletter $newsletter) {
        $destinationEmailAddresses = array_keys($this->email);
        Mail::queue('emails.newsletter', [
            'newsletter' => $newsletter
        ], function ($m) use ($destinationEmailAddresses, $newsletter) {
            $m->to([env('EMAIL_ADDRESS', 'info@talentsage.com')])->bcc($destinationEmailAddresses)->subject($newsletter->email_subject);
        });
    }
}
