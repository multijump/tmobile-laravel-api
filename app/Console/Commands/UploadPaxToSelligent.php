<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class UploadPaxToSelligent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:participants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Writes all participants to a CSV file and transfers it to Selligent via SFTP/FTPS.';

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
     * @return mixed
     */
    public function handle()
    {
        $rawSqlStatement = '
                events.title as event_title,
                events.store_number as event_store_number,
                events.workfront_id as event_workfront_id,
                events.company as event_company,
                DATE_FORMAT(events.start_date, "%m/%d/%Y") as event_start_date,
                DATE_FORMAT(events.end_date, "%m/%d/%Y") as event_end_date,
                events.default_language as event_language,
                participants.first_name as participant_first_name,
                participants.last_name as participant_last_name,
                CASE WHEN participants.is_contact <> 0 THEN participants.email ELSE "NO CONTACT" END as participant_email,
                CASE WHEN participants.is_contact <> 0 THEN participants.phone ELSE "NO CONTACT" END as participant_phone,
                participants.zip_code as participant_zip_code,
                participants.language as participant_language,
                CASE WHEN participants.is_customer <> 0 THEN "yes" ELSE "no" END as is_customer,
                participants.current_carrier as participant_current_carrier,
                CASE WHEN participants.is_contact <> 0 THEN "yes" ELSE "no" END as participant_is_contact,
                DATE_FORMAT(participants.created_at, "%m/%d/%Y") as participant_created_date,
                cu.first_name as creating_user_first_name,
                cu.last_name as creating_user_last_name,
                cu.email as creating_user_email,
                DATE_FORMAT(participants.selected_date, "%m/%d/%Y") as participant_selected_date,
                su.first_name as selecting_user_first_name,
                su.last_name as selecting_user_last_name,
                su.email as selecting_user_email,
                events.description as event_description,
                events.region as event_region,
                events.state as event_state'
        ;

        $eventRecords = Participant::query()
        ->join('events', 'participants.event_id', '=', 'events.id')
        ->leftJoin('users as cu', 'participants.created_by_id', '=', 'cu.id')
        ->leftJoin('users as su', 'participants.selected_by_id', '=', 'su.id')
        // ->where('event_id', '=', $eventId)
        ->select(\DB::raw($rawSqlStatement))
        ->orderBy('participants.created_at')
        ->get()
        ->toArray();

        $rowTitles = [
            'Event Name', // A
            'Event Store Number', // B
            'Event Workfront ID', // C
            'Event Brand', // D
            'Event Start Date', // E
            'Event End Date', // F
            'Event Default Language', // G
            'Participant First Name', // H
            'Participant Last Name', // I
            'Participant Email', // J
            'Participant Phone', // K
            'Participant Zip Code', // L
            'Participant Language', // M
            'Participant Is Customer', // N
            'Participant Current Carrier', // O
            'Participant Can Contact', // P
            'Participant Created Date', // Q
            'Created By First Name', // R
            'Created By Last Name', // S
            'Created By Email', // T
            'Winner Selected Date', // U
            'Winner Selected By First', // V
            'Winner Selected By Last', // W
            'Winner Selected By Email', // X
            'Event Description', // Y
            'Event Region', // Z
            'Event State', // AA
        ];

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($rowTitles, null, 'A1');

        for ($i = 0; $i < (count($eventRecords)); $i++){
            $sheet->fromArray($eventRecords[$i], null, 'A' . (2 + $i));
        }

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25); // Event Name
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Event Store Number
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Event Workfront ID
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15); // Event Brand
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15); // Event Start Date
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15); // Event End Date
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15); // Event Language
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(18); // Participant First Name
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(18); // Participant Last Name
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25); // Participant Email
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15); // Participant Phone
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15); // Participant Zip Code
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15); // Participant Language
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15); // Participant Is Customer
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15); // Participant Current Carrier
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15); // Participant Can Be Contacted
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(20); // Participant Created Date
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(18); // Created By First Name
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(18); // Created By Last Name
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(20); // Created By Email
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(20); // Selected Date
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(15); // Selected By First
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(18); // Selected By Last
        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(18); // Selected By Email
        $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(25); // Event Description
        $spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(15); // Event Region
        $spreadsheet->getActiveSheet()->getColumnDimension('AA')->setWidth(15); // Event State

        // $writer = new Xls($spreadsheet);
        $writer = new Xlsx($spreadsheet);

        $timestamp = Carbon::now()->format('m-d-Y_') . time();

        $filename = 'EventReportGeneratedOn' . $timestamp ;

        /**
         * Create temporary files as we
         * save .xls file,
         * upload file to Selligent,
         * remove temporary files
         */

        //The name of the directory that we need to create.
        $directoryName = public_path() . '/filestorage/reports/selligent';

        // $savePath = $directoryName . '/' . $filename.'.xls';
        $savePath = $directoryName . '/' . $filename.'.xlsx';

        $writer->save($savePath);

        unset($sheet);

        try {
            echo "Beginning transfer...\r\n";
            Storage::disk('sftp')->put(basename($savePath), fopen($savePath, 'r+'));
            echo "File successfully transferred...\r\n";
        } catch (\Exception $ex) {
            echo "File transfer error:";
            echo $ex;
        }

        echo "Cleaning up...\r\n";

        unlink(realpath($savePath));

        echo "All clean...\r\n";
        echo "All done.";

        /**
         * Setup cURL to transfer the file over FTP
         *
         */
        // $ftpUrl = env('FTP_PROTOCOL')."://".env('FTP_HOSTNAME')."/".basename($savePath);
        // $ftpAuth = env('FTP_USERNAME').":".env('FTP_PASSWORD');

        // $openedFile = fopen($savePath, 'r');

        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $ftpUrl);
        // curl_setopt($curl, CURLOPT_PORT, env('FTP_PORT'));
        // curl_setopt($curl, CURLOPT_USERPWD, $ftpAuth);
        // curl_setopt($curl, CURLOPT_UPLOAD, true);
        // curl_setopt($curl, CURLOPT_INFILE, $openedFile);
        // curl_setopt($curl, CURLOPT_INFILESIZE, filesize($savePath));
        // curl_setopt($curl, CURLOPT_VERBOSE, true);

        // $response = curl_exec($curl);

        // curl_close($curl);

        // if($response) {
        //     echo "Data successfully transferred.";

        //     // Remove temporary files
        //     unlink($savePath);
        // } else {
        //     echo "Data could not be transferred. Something went wrong.";

        //     // Remove temporary files
        //     unlink($savePath);
        // }
    }

    /**
     * Removes emojis from strings before saving to Excel
     *
     * @return string
     */
    private function removeEmojis($string) {
        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}
