<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Models\Personel;
use Illuminate\Console\Command;
use MailchimpMarketing\ApiClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SyncUserToMailchimp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:user-to-mailchimp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user DTBS to Mailchimp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $mailchimp = new ApiClient();

            $mailchimp->setConfig([
                'apiKey' => config('app.mailchimp_api_key'),
                'server' => config('app.mailchimp_server_prefix'),
            ]);

            $listId = config('app.mailchimp_list_id');

            // $response = $mailchimp->lists->getListInterestCategories($listId);
            // print_r($response);

            // $response = $mailchimp->lists->listInterestCategoryInterests(
            //     $listId,
            //     // TO DO
            // );
            // print_r($response);

            $pastoralGroupId = config('app.mailchimp_pastoral_group_id');
            
            $chunkPersonels = Personel::select('first_name', 'last_name', 'email', 'date_of_birth')->cursor()->chunk(400);

            $emails = [];
            $offset = 0;
            $statuses = ["unsubscribed", "cleaned", "pending", "transactional", "archived"];
            do{
                $members = $mailchimp->lists->getListMembersInfo($listId, ['members.email_address', 'members.status'], null, 500, $offset, null, $statuses);
                $members = $members->members;
                $countMembers = count($members);
                $offset += $countMembers;
                foreach($members as $member){
                    $emails[$member->email_address] = $member->status;
                }
            }
            while($countMembers > 0);

            foreach($chunkPersonels as $personels){
                $members = [];
                foreach($personels as $personel){
                    if(filter_var($personel->email, FILTER_VALIDATE_EMAIL) && (!isset($emails[strtolower($personel->email)])
                     || $emails[strtolower($personel->email)] == "unsubscribed" || $emails[strtolower($personel->email)] == "cleaned" || $emails[strtolower($personel->email)] == "pending")){
                        $dateOfBirth = '';
                        if($personel->date_of_birth != null){
                            try{
                                $dateOfBirth = Carbon::parse($personel->date_of_birth)->format('m/d');
                            }
                            catch(Exception $e){
                                $dateOfBirth = '';
                            }
                        }
                        $members[] = [
                            "email_address" => $personel->email,
                            "status" => ($emails[strtolower($personel->email)] ?? 'subscribed'),
                            "merge_fields" => [
                                "FNAME" => $personel->first_name,
                                "LNAME" => $personel->last_name,
                                "BIRTHDAY" => $dateOfBirth,
                            ],
                            "interests" => [
                                $pastoralGroupId => true
                            ]
                        ];
                    }
                }
                if(count($members) > 0){
                    $response = $mailchimp->lists->batchListMembers($listId, ["members" => $members, "update_existing" => true]);
                }
            }
       
            Log::channel('mailchimp')->info('Sync DTBS user to Mailchimp success.');
        }
        catch(Exception $exception){
            Log::channel('mailchimp')->error('Sync DTBS user to Mailchimp failed. Error : ' . $exception);
            Mail::send([], [], function ($message) use ($exception) {
                $text = '<h4>ERROR</h4>';
                $text .= '<p>' . ($exception->getMessage() ?? 'Unknown error') . '</p>';
                $message->subject('ERROR SYNC DTBS USER TO MAILCHIMP')
                    ->to(config('app.email_developer'))
                    ->setBody($text, 'text/html');
            });
        }
    }
}
